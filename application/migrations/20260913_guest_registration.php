<?php
/** CLI: php application/migrations/20260913_guest_registration.php [--database=nombre] [--apply] */
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
$_SERVER['HTTP_HOST'] = 'localhost';
require_once dirname(__DIR__) . '/config/Config1.php';
$options = getopt('', ['database:', 'apply', 'clone:']);
$database = $options['database'] ?? DB_NAME;
foreach ([$database, $options['clone'] ?? $database] as $identifier) {
    if (!preg_match('/^[a-zA-Z0-9_]+$/D', $identifier)) {
        throw new RuntimeException('Nombre de base inválido.');
    }
}
$pdo = new PDO('mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . $database . ';charset=utf8mb4', DB_USER, DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
if (isset($options['clone'])) {
    $target = $options['clone'];
    if ($target === $database) {
        throw new RuntimeException('La copia debe tener otro nombre.');
    }
    $pdo->exec("CREATE DATABASE `$target` CHARACTER SET utf8mb4");
    $tables = $pdo->query("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'")->fetchAll(PDO::FETCH_NUM);
    foreach ($tables as $table) {
        $name = str_replace('`', '``', $table[0]);
        $pdo->exec("CREATE TABLE `$target`.`$name` LIKE `$database`.`$name`");
        $pdo->exec("INSERT INTO `$target`.`$name` SELECT * FROM `$database`.`$name`");
    }
    echo "Copia creada: $target\n";
    exit;
}
$audit = function () use ($pdo) {
    return [
        'duplicates' => $pdo->query('SELECT id_document_type, GROUP_CONCAT(id ORDER BY id) AS person_ids, COUNT(*) AS total
            FROM person GROUP BY id_document_type, UPPER(TRIM(document_number)) HAVING COUNT(*) > 1')->fetchAll(PDO::FETCH_ASSOC),
        'ambiguous_reservations' => $pdo->query('SELECT r.id_reservation, r.id_guest AS original_id
            FROM reservation r LEFT JOIN guest g ON g.id_guest = r.id_guest
            LEFT JOIN person p ON p.id = r.id_guest
            WHERE g.id_guest IS NULL OR (p.id IS NOT NULL AND UPPER(TRIM(p.document_number)) <> UPPER(TRIM(g.document_number)))')->fetchAll(PDO::FETCH_ASSOC)
    ];
};
echo json_encode($audit(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
if (!isset($options['apply'])) {
    echo "Solo diagnóstico. Use --apply para aplicar en la base seleccionada.\n";
    exit;
}
$column = function ($table, $name, $definition) use ($pdo) {
    $q = $pdo->prepare('SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?');
    $q->execute([$table, $name]);
    if (!$q->fetchColumn()) {
        $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$name` $definition");
    }
};
$column('person', 'first_names', 'VARCHAR(50) NULL');
$column('person', 'last_names', 'VARCHAR(50) NULL');
$column('reservation', 'id_person', 'INT NULL');

// Solo vincular cuando el documento identifica una única ficha y el ID antiguo no es ambiguo.
$pdo->exec('UPDATE reservation r JOIN guest g ON g.id_guest = r.id_guest
    JOIN document_type dt ON dt.description = g.document_type
    JOIN (SELECT id_document_type, UPPER(TRIM(document_number)) AS doc, MIN(id) AS id
        FROM person GROUP BY id_document_type, UPPER(TRIM(document_number)) HAVING COUNT(*) = 1) p
        ON p.id_document_type = dt.id AND p.doc = UPPER(TRIM(g.document_number))
    LEFT JOIN person conflicting ON conflicting.id = r.id_guest
    SET r.id_person = p.id WHERE r.id_person IS NULL
    AND (conflicting.id IS NULL OR UPPER(TRIM(conflicting.document_number)) = UPPER(TRIM(g.document_number)))');

// Registro único de documentos: conserva duplicados históricos sin permitir nuevas duplicaciones.
// Los triggers participan en la misma transacción InnoDB que la ficha.
$pdo->exec('CREATE TABLE IF NOT EXISTS person_document (
    id_document_type INT NOT NULL, document_number VARCHAR(45) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
    PRIMARY KEY (id_document_type, document_number)
) ENGINE=InnoDB');
$pdo->exec('INSERT IGNORE INTO person_document SELECT DISTINCT id_document_type, UPPER(TRIM(document_number)) FROM person');
$triggers = [
    'person_identity_insert' => 'BEFORE INSERT ON person FOR EACH ROW BEGIN
        SET NEW.document_number = UPPER(TRIM(NEW.document_number));
        INSERT INTO person_document VALUES (NEW.id_document_type, NEW.document_number);
    END',
    'person_identity_update' => 'BEFORE UPDATE ON person FOR EACH ROW BEGIN
        SET NEW.document_number = UPPER(TRIM(NEW.document_number));
        IF NEW.id_document_type <> OLD.id_document_type OR NEW.document_number <> UPPER(TRIM(OLD.document_number)) THEN
            INSERT INTO person_document VALUES (NEW.id_document_type, NEW.document_number);
        END IF;
    END',
    'person_identity_release_update' => 'AFTER UPDATE ON person FOR EACH ROW BEGIN
        IF NEW.id_document_type <> OLD.id_document_type OR NEW.document_number <> UPPER(TRIM(OLD.document_number)) THEN
            DELETE FROM person_document WHERE id_document_type = OLD.id_document_type AND document_number = UPPER(TRIM(OLD.document_number))
            AND NOT EXISTS (SELECT 1 FROM person p WHERE p.id_document_type = OLD.id_document_type AND UPPER(TRIM(p.document_number)) = UPPER(TRIM(OLD.document_number)));
        END IF;
    END',
    'person_identity_release_delete' => 'AFTER DELETE ON person FOR EACH ROW BEGIN
        DELETE FROM person_document WHERE id_document_type = OLD.id_document_type AND document_number = UPPER(TRIM(OLD.document_number))
        AND NOT EXISTS (SELECT 1 FROM person p WHERE p.id_document_type = OLD.id_document_type AND UPPER(TRIM(p.document_number)) = UPPER(TRIM(OLD.document_number)));
    END'
];
foreach ($triggers as $name => $sql) {
    $q = $pdo->prepare('SELECT COUNT(*) FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA = DATABASE() AND TRIGGER_NAME = ?');
    $q->execute([$name]);
    if (!$q->fetchColumn()) {
        $pdo->exec("CREATE TRIGGER `$name` $sql");
    }
}
$pdo->exec('CREATE OR REPLACE VIEW reservation_guest AS
    SELECT r.id_reservation, r.id_person, r.id_guest AS legacy_id_guest,
        COALESCE(p.name, NULLIF(TRIM(CONCAT(old.first_names, " ", old.last_names)), ""), old.company_name, "Huésped por revisar") AS name,
        CASE WHEN p.id IS NOT NULL THEN COALESCE(NULLIF(p.first_names, ""), p.name) ELSE COALESCE(old.first_names, "Huésped por revisar") END AS first_names,
        CASE WHEN p.id IS NOT NULL THEN COALESCE(p.last_names, "") ELSE COALESCE(old.last_names, "") END AS last_names,
        COALESCE(dt.description, old.document_type, "") AS document_type,
        COALESCE(p.document_number, old.document_number, "") AS document_number,
        COALESCE(p.address, old.address, "") AS address,
        CASE WHEN p.id IS NOT NULL THEN COALESCE(p.business_name, "") ELSE COALESCE(old.company_name, "") END AS company_name,
        p.nationality, p.phone, p.email, (r.id_person IS NULL) AS needs_review
    FROM reservation r LEFT JOIN person p ON p.id = r.id_person
    LEFT JOIN document_type dt ON dt.id = p.id_document_type
    LEFT JOIN guest old ON old.id_guest = r.id_guest AND r.id_person IS NULL');
echo "Migración aplicada en $database. Referencias antiguas conservadas.\n";

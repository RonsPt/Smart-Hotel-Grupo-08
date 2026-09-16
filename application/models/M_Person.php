<?php
/** Identidad compartida por Recepción y Clientes. */
class M_Person extends Model
{
    public function __construct($pdo = null)
    {
        if ($pdo) {
            $this->pdo = $pdo;
        } else {
            parent::__construct();
        }
    }

    public function document($type, $number)
    {
        $type = trim((string) $type);
        $number = strtoupper(trim((string) $number));
        $row = $this->pdo->fetchOne(
            'SELECT id, description FROM document_type WHERE status = 1 AND ' .
            (ctype_digit($type) ? 'id = :type' : 'description = :type'), ['type' => $type]
        );
        if (!$row) {
            throw new InvalidArgumentException('Seleccione un tipo de documento válido.');
        }
        $patterns = ['DNI' => '/^[0-9]{8}$/D', 'RUC' => '/^[0-9]{11}$/D', 'CE' => '/^[A-Z0-9]{6,12}$/D'];
        if (!isset($patterns[$row['description']]) || !preg_match($patterns[$row['description']], $number)) {
            throw new InvalidArgumentException('Documento inválido: DNI de 8 dígitos, RUC de 11 o CE de 6 a 12 caracteres alfanuméricos.');
        }
        return ['id_document_type' => (int) $row['id'], 'document_number' => $number, 'document_type' => $row['description']];
    }

    public function find($type, $number)
    {
        $doc = $this->document($type, $number);
        return $this->pdo->fetchAll('SELECT p.*, p.id AS id_guest, p.id AS id_clients,
            dt.description AS document_type, p.business_name AS company_name
            FROM person p JOIN document_type dt ON dt.id = p.id_document_type
            WHERE p.id_document_type = :id_document_type AND UPPER(TRIM(p.document_number)) = :document_number
            ORDER BY p.status DESC, p.id', [
                'id_document_type' => $doc['id_document_type'], 'document_number' => $doc['document_number']
            ]);
    }

    public function byId($id)
    {
        return $this->pdo->fetchOne('SELECT p.*, p.id AS id_guest, p.id AS id_clients,
            dt.description AS document_type, p.business_name AS company_name
            FROM person p JOIN document_type dt ON dt.id = p.id_document_type WHERE p.id = :id', ['id' => $id]);
    }

    /** 8.2 — Recupera las reservas y estadías anteriores de una ficha (persona). */
    public function stays($id)
    {
        if (!ctype_digit((string) $id)) {
            throw new InvalidArgumentException('Identificador de persona inválido.');
        }
        return $this->pdo->fetchAll(
            'SELECT r.id_reservation, r.checkin_date, r.checkin_time, r.checkout_date, r.checkout_time,
                r.status, room.room_number, room_type.type_name
             FROM reservation r
             JOIN room ON room.id_room = r.id_room
             JOIN room_type ON room_type.id_type = room.id_type
             WHERE r.id_person = :id
             ORDER BY r.checkin_date DESC, r.checkin_time DESC',
            ['id' => (int) $id]
        );
    }

    public function save($input, $id = null)
    {
        $doc = $this->document($input['document_type'] ?? $input['id_document_type'] ?? '', $input['document_number'] ?? '');
        $matches = $this->find($doc['id_document_type'], $doc['document_number']);
        if (!$id && $matches) {
            return $this->existing($matches);
        }
        foreach ($matches as $match) {
            if ($id && (int) $match['id'] !== (int) $id) {
                throw new InvalidArgumentException('El documento ya pertenece a otra ficha. Revise las coincidencias.');
            }
        }
        $old = $id ? $this->byId($id) : null;
        if ($id && !$old) {
            throw new InvalidArgumentException('La persona no existe.');
        }
        $data = [];
        $limits = ['first_names' => 50, 'last_names' => 50, 'nationality' => 50, 'phone' => 45,
            'email' => 50, 'address' => 100, 'business_name' => 50, 'birth_place' => 100];
        foreach ($limits as $field => $max) {
            $value = $input[$field] ?? ($field === 'business_name' ? ($input['company_name'] ?? '') : '');
            if (!is_scalar($value) && $value !== null) {
                throw new InvalidArgumentException('Formato de campo inválido.');
            }
            $data[$field] = trim((string) $value);
            if (mb_strlen($data[$field]) > $max) {
                throw new InvalidArgumentException('El campo ' . $field . ' supera ' . $max . ' caracteres.');
            }
        }
        $business = $doc['document_type'] === 'RUC';
        if ($business) {
            $data['business_name'] = $data['business_name'] ?: trim((string) ($input['name'] ?? ''));
            $data['first_names'] = $data['last_names'] = $data['nationality'] = '';
            $name = $data['business_name'];
        } else {
            $name = trim($data['first_names'] . ' ' . $data['last_names']);
            // Una ficha antigua conserva su nombre completo hasta que el operador lo separe.
            $legacy = $old && empty($old['first_names']) && empty($old['last_names']) && $name === '';
            if ($legacy) {
                $name = trim((string) ($input['name'] ?? $old['name']));
            } elseif (!$data['first_names'] || !$data['last_names']) {
                throw new InvalidArgumentException('Ingrese nombres y apellidos.');
            }
            if (!$data['nationality']) {
                throw new InvalidArgumentException('Ingrese la nacionalidad.');
            }
        }
        if ($name === '' || mb_strlen($name) > 100 || mb_strlen($data['business_name']) > 50) {
            throw new InvalidArgumentException('Ingrese un nombre o razón social dentro del tamaño permitido.');
        }
        if (!$data['phone'] && !$data['email']) {
            throw new InvalidArgumentException('Ingrese al menos un teléfono o correo de contacto.');
        }
        if ($data['email'] && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Correo electrónico inválido.');
        }
        if ($data['phone'] && !preg_match('/^[+0-9() .-]{6,45}$/D', $data['phone'])) {
            throw new InvalidArgumentException('Teléfono inválido.');
        }
        $birth = trim((string) ($input['birth_date'] ?? ''));
        if ($birth) {
            $date = DateTimeImmutable::createFromFormat('!Y-m-d', $birth);
            if (!$date || $date->format('Y-m-d') !== $birth || $birth > date('Y-m-d')) {
                throw new InvalidArgumentException('Fecha de nacimiento inválida.');
            }
        }
        $data += ['name' => $name, 'birth_date' => $birth ?: null,
            'id_document_type' => $doc['id_document_type'], 'document_number' => $doc['document_number']];
        if ($id) {
            $state = (string) ($input['status'] ?? $old['status']);
            if (!in_array($state, ['0', '1'], true)) { throw new InvalidArgumentException('Estado inválido.'); }
            $data['status'] = (int) $state;
        }
        try {
            if ($id) {
                $assignments = array_map(function ($key) { return $key . ' = :' . $key; }, array_keys($data));
                $data['id'] = (int) $id;
                $this->pdo->perform('UPDATE person SET ' . implode(', ', $assignments) . ' WHERE id = :id', $data);
            } else {
                $keys = array_keys($data);
                $this->pdo->perform('INSERT INTO person (' . implode(',', $keys) . ') VALUES (:' . implode(',:', $keys) . ')', $data);
                $id = $this->pdo->lastInsertId();
            }
        } catch (PDOException $e) {
            if (!$id && (string) $e->getCode() === '23000') {
                $matches = $this->find($doc['id_document_type'], $doc['document_number']);
                if ($matches) {
                    return $this->existing($matches);
                }
            }
            throw $e;
        }
        return ['status' => 'OK', 'existing' => false, 'data' => $this->byId($id), 'msg' => 'Datos guardados correctamente.'];
    }

    private function existing($matches)
    {
        if (count($matches) > 1) {
            return ['status' => 'CONFLICT', 'data' => $matches, 'msg' => 'Hay varias fichas históricas. Seleccione una coincidencia; no se creó otra.'];
        }
        if ((int) $matches[0]['status'] !== 1) {
            throw new InvalidArgumentException('El documento pertenece a una ficha inactiva. Revísela en Clientes.');
        }
        return ['status' => 'OK', 'existing' => true, 'data' => $matches[0], 'msg' => 'La persona ya existe. Se reutilizó su ficha.'];
    }
}

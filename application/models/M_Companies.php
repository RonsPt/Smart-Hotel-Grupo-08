<?php 
// --
class M_Companies extends Model {
    // --
    public function __construct() {
		parent::__construct();
    }

    // -- Normalizar el listado de huéspedes (ids de person)
    private function normalize_guests($value) {
        // --
        $ids = array();
        // --
        if (is_string($value) && $value !== '') {
            // --
            $decoded = json_decode($value, true);
            // --
            if (is_array($decoded)) {
                $value = $decoded;
            } else {
                $value = explode(',', $value);
            }
        }
        // --
        if (!is_array($value)) {
            return $ids;
        }
        // --
        foreach ($value as $item) {
            // --
            if (is_scalar($item) && ctype_digit((string) $item) && (int) $item > 0) {
                // --
                $ids[] = (int) $item;
            }
        }
        // --
        return array_values(array_unique($ids));
    }

    // -- Validar y normalizar campos comunes
    private function validate($bind) {
        // --
        $company_name = trim((string) ($bind['company_name'] ?? $bind['name'] ?? ''));
        // --
        if ($company_name === '' || mb_strlen($company_name) > 150) {
            throw new InvalidArgumentException('Ingrese el nombre de la empresa (máximo 150 caracteres).');
        }
        // --
        $ruc = trim((string) ($bind['ruc'] ?? ''));
        // --
        if (!preg_match('/^\d{11}$/D', $ruc)) {
            throw new InvalidArgumentException('El RUC debe tener 11 dígitos numéricos.');
        }
        // --
        if (isset($bind['id_company'])) {
            // --
            $row = $this->pdo->fetchOne('SELECT id FROM company_corporate WHERE ruc = :ruc AND id <> :id_company AND status = 1', array('ruc' => $ruc, 'id_company' => $bind['id_company']));
            // --
        } else {
            // --
            $row = $this->pdo->fetchOne('SELECT id FROM company_corporate WHERE ruc = :ruc AND status = 1', array('ruc' => $ruc));
            // --
        }
        // --
        if ($row) {
            throw new InvalidArgumentException('Ya existe una empresa registrada con ese RUC.');
        }
        // --
        $business_name = mb_substr(trim((string) ($bind['business_name'] ?? $bind['razon_social'] ?? '')), 0, 150);
        $contact_name = mb_substr(trim((string) ($bind['contact_name'] ?? $bind['contacto'] ?? '')), 0, 100);
        // --
        $contact_email = mb_substr(trim((string) ($bind['contact_email'] ?? '')), 0, 50);
        // --
        if ($contact_email !== '' && !filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Correo de contacto inválido.');
        }
        // --
        $contact_phone = mb_substr(trim((string) ($bind['contact_phone'] ?? '')), 0, 45);
        // --
        if ($contact_phone !== '' && !preg_match('/^[+0-9() .-]{6,45}$/D', $contact_phone)) {
            throw new InvalidArgumentException('Teléfono de contacto inválido.');
        }
        // --
        $tariff = trim((string) ($bind['corporate_tariff'] ?? '0'));
        // --
        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/D', $tariff) || (float) $tariff < 0) {
            throw new InvalidArgumentException('Ingrese una tarifa corporativa válida (número positivo).');
        }
        // --
        $credit = trim((string) ($bind['credit_limit'] ?? $bind['credito'] ?? '0'));
        // --
        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/D', $credit) || (float) $credit < 0) {
            throw new InvalidArgumentException('Ingrese un límite de crédito válido (número positivo o cero).');
        }
        // --
        $conditions = trim((string) ($bind['commercial_conditions'] ?? ''));
        // --
        return array(
            'company_name' => $company_name,
            'ruc' => $ruc,
            'business_name' => $business_name,
            'contact_name' => $contact_name,
            'contact_email' => $contact_email,
            'contact_phone' => $contact_phone,
            'corporate_tariff' => number_format((float) $tariff, 2, '.', ''),
            'credit_limit' => number_format((float) $credit, 2, '.', ''),
            'commercial_conditions' => $conditions === '' ? null : mb_substr($conditions, 0, 4000),
            'guests' => $this->normalize_guests($bind['guests'] ?? array())
        );
    }

    // -- Reemplazar los huéspedes asociados a la empresa
    private function sync_guests($id_company, $ids) {
        // --
        $this->pdo->perform('DELETE FROM company_guest WHERE id_company = :id_company', array('id_company' => $id_company));
        // --
        if (empty($ids)) {
            return;
        }
        // --
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        // --
        $rows = $this->pdo->fetchAll('SELECT id FROM person WHERE status = 1 AND id IN (' . $placeholders . ')', $ids);
        // --
        foreach ($rows as $row) {
            // --
            $this->pdo->perform('INSERT INTO company_guest (id_company, id_person) VALUES (:id_company, :id_person)', array(
                'id_company' => $id_company,
                'id_person' => (int) $row['id']
            ));
        }
    }

    // --
	public function get_companies() {
        // --
        try {
            // --
            $sql = 'SELECT
                    cc.id AS id_company,
                    cc.company_name,
                    cc.ruc,
                    cc.business_name,
                    cc.contact_name,
                    cc.contact_email,
                    cc.contact_phone,
                    cc.commercial_conditions,
                    cc.corporate_tariff,
                    cc.credit_limit,
                    cc.status,
                    COUNT(cg.id_person) AS guest_count
                FROM company_corporate cc
                LEFT JOIN company_guest cg ON cg.id_company = cc.id
                WHERE cc.status = 1
                GROUP BY cc.id
                ORDER BY cc.id DESC';
            // --
            $result = $this->pdo->fetchAll($sql);
            // --
            if ($result) {
                // --
                $response = array('status' => 'OK', 'result' => $result);
                // --
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
                // --
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
            // --
        }
        // --
        return $response;
    }

    // --
	public function get_company_by_id($bind) {
        // --
        try {
            // --
            $sql = 'SELECT
                    cc.id AS id_company,
                    cc.company_name,
                    cc.ruc,
                    cc.business_name,
                    cc.contact_name,
                    cc.contact_email,
                    cc.contact_phone,
                    cc.commercial_conditions,
                    cc.corporate_tariff,
                    cc.credit_limit,
                    cc.status
                FROM company_corporate cc
                WHERE cc.id = :id_company AND cc.status = 1';
            // --
            $result = $this->pdo->fetchOne($sql, $bind);
            // --
            if ($result) {
                // --
                $result['guests'] = $this->pdo->fetchAll('SELECT
                        g.id_person,
                        p.name,
                        p.business_name,
                        p.document_number
                    FROM company_guest g
                    INNER JOIN person p ON p.id = g.id_person
                    WHERE g.id_company = :id_company
                    ORDER BY p.name', array('id_company' => $result['id_company']));
                // --
                $response = array('status' => 'OK', 'result' => $result);
                // --
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
                // --
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
            // --
        }
        // --
        return $response;
    }

    // --
    public function create_company($bind) {
        // --
        try {
            // --
            $data = $this->validate($bind);
            // --
            $sql = 'INSERT INTO company_corporate
            (
                company_name,
                ruc,
                business_name,
                contact_name,
                contact_email,
                contact_phone,
                commercial_conditions,
                corporate_tariff,
                credit_limit,
                status
            ) 
            VALUES 
            (
                :company_name,
                :ruc,
                :business_name,
                :contact_name,
                :contact_email,
                :contact_phone,
                :commercial_conditions,
                :corporate_tariff,
                :credit_limit,
                1
            )';
            // --
            $result = $this->pdo->perform($sql, $data);
            // --
            if ($result) {
                // --
                $id_company = $this->pdo->lastInsertId();
                // --
                $this->sync_guests($id_company, $data['guests']);
                // --
                $response = array('status' => 'OK', 'result' => array('id_company' => $id_company));
                // --
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
                // --
            }
        } catch (InvalidArgumentException $e) {
            // --
            $response = array('status' => 'ERROR', 'result' => $e->getMessage());
            // --
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
            // --
        }
        // --
        return $response;
    }

    // --
    public function update_company($bind) {
        // --
        try {
            // --
            if (!isset($bind['id_company']) || !ctype_digit((string) $bind['id_company']) || (int) $bind['id_company'] < 1) {
                throw new InvalidArgumentException('Seleccione una empresa válida.');
            }
            // --
            $data = $this->validate($bind);
            $data['id_company'] = (int) $bind['id_company'];
            // --
            $row = $this->pdo->fetchOne('SELECT id FROM company_corporate WHERE id = :id_company AND status = 1', array('id_company' => $data['id_company']));
            // --
            if (!$row) {
                throw new InvalidArgumentException('La empresa seleccionada no existe.');
            }
            // --
            $sql = 'UPDATE company_corporate 
                SET
                    company_name = :company_name,
                    ruc = :ruc,
                    business_name = :business_name,
                    contact_name = :contact_name,
                    contact_email = :contact_email,
                    contact_phone = :contact_phone,
                    commercial_conditions = :commercial_conditions,
                    corporate_tariff = :corporate_tariff,
                    credit_limit = :credit_limit
                WHERE id = :id_company';
            // --
            $result = $this->pdo->perform($sql, $data);
            // --
            if ($result) {
                // --
                $this->sync_guests($data['id_company'], $data['guests']);
                // --
                $response = array('status' => 'OK', 'result' => array());
                // --
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
                // --
            }
        } catch (InvalidArgumentException $e) {
            // --
            $response = array('status' => 'ERROR', 'result' => $e->getMessage());
            // --
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
            // --
        }
        // --
        return $response;
    }

    // --
    public function delete_company($bind) {
        // --
        try {
            // --
            $sql = 'UPDATE company_corporate SET status = 0 WHERE id = :id_company';
            // --
            $result = $this->pdo->perform($sql, $bind);
            // --
            if ($result) {
                // --
                $response = array('status' => 'OK', 'result' => array());
                // --
            } else {
                // --
                $response = array('status' => 'ERROR', 'result' => array());
                // --
            }
        } catch (PDOException $e) {
            // --
            $response = array('status' => 'EXCEPTION', 'result' => $e);
            // --
        }
        // --
        return $response;
    }
}
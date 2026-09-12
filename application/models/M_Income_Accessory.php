<?php  
// --
class M_Income_Accessory extends Model {
    // --
    public function __construct() {
        parent::__construct();
    }

    // Obtener todos los ingresos
    public function get_income_accessory() {
        try {
            $draw = intval($_GET['draw'] ?? 1);
            $start = intval($_GET['start'] ?? 0);
            $length = intval($_GET['length'] ?? 10);
            $search_value = trim($_GET['search']['value'] ?? '');

            $sql_total = 'SELECT COUNT(*) FROM income_accessory';
            $records_total = $this->pdo->fetchOne($sql_total) ?: 0;

            $sql = 'SELECT 
                        ia.id AS id_income_accessory, 
                        ia.date AS proof_date, 
                        COALESCE(c.business_name, c.name, \'Desconocido\') AS business_name, 
                        CONCAT(COALESCE(u.first_name, \'\'), \' \', COALESCE(u.last_name, \'\')) AS first_name, 
                        COALESCE(vt.description, \'N/A\') AS vt_description, 
                        ia.proof_series, 
                        ia.voucher_series, 
                        ia.full_purchase 
                    FROM income_accessory ia 
                    LEFT JOIN person c ON ia.id_client = c.id 
                    LEFT JOIN user u ON ia.id_user = u.id 
                    LEFT JOIN voucher_type vt ON ia.id_voucher_type = vt.id';
            
            $params = [];
            $where_clause = '';
            if (!empty($search_value)) {
                $where_clause = ' WHERE (COALESCE(c.business_name, c.name) LIKE :search 
                                OR CONCAT(COALESCE(u.first_name, \'\'), \' \', COALESCE(u.last_name, \'\')) LIKE :search 
                                OR COALESCE(vt.description, \'N/A\') LIKE :search 
                                OR ia.proof_series LIKE :search 
                                OR ia.voucher_series LIKE :search)';
                $params[':search'] = '%' . $search_value . '%';
            }

            $sql_filtered = 'SELECT COUNT(ia.id) FROM income_accessory ia 
                            LEFT JOIN person c ON ia.id_client = c.id 
                            LEFT JOIN user u ON ia.id_user = u.id 
                            LEFT JOIN voucher_type vt ON ia.id_voucher_type = vt.id' . $where_clause;
            $records_filtered = $this->pdo->fetchOne($sql_filtered, $params) ?: 0;

            $sql .= $where_clause . ' ORDER BY ia.id DESC LIMIT ' . $start . ', ' . $length;
            $result = $this->pdo->fetchAll($sql, $params);

            $datatable_data = [
                'draw' => $draw,
                'recordsTotal' => $records_total,
                'recordsFiltered' => $records_filtered,
                'data' => $result
            ];

            return ['status' => 'OK', 'result' => $datatable_data];
        } catch (PDOException $e) {
            return ['status' => 'EXCEPTION', 'result' => $e];
        } catch (Exception $e) {
            return ['status' => 'EXCEPTION', 'result' => $e];
        }
    }

//--------------------- CREATE INCOME ACCESSORY -------------------------
    public function create_income_accessory($bind) {
        try {
            // Usamos los campos de la tabla 'income_accessory'
            $sql = "INSERT INTO income_accessory (
                id_client, id_user, id_voucher_type, id_payment_type, 
                proof_series, voucher_series, date, igv, full_purchase, status
            ) VALUES (
                :id_client, :id_user, :id_voucher_type, :id_payment_type,
                :proof_series, :voucher_series, :date, :igv, :full_purchase, :status
            )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($bind);
            // Si tiene éxito, devolvemos el ID
            return ['status' => 'OK', 'id' => $this->pdo->lastInsertId()]; 

        } catch (PDOException $e) {
            return ['status' => 'EXCEPTION', 'message' => $e->getMessage()]; 
        }
    }

    //--------------------- INSERT INCOME ACCESSORY DETAILS-----------------
    public function insertIncomeAccessoryDetails($accessory) {
        try {
            // Usamos los campos de la tabla 'income_accessory_details'
            $sql = "INSERT INTO income_accessory_details (
                        id_income_accessory, id_accessory, serie, 
                        stock, purchase_price, sale_price, status
                    ) VALUES (
                        :id_income_accessory, :id_accessory, :serie, 
                        :stock, :purchase_price, :sale_price, 1
                    )";

            $stmt = $this->pdo->prepare($sql);

            // Bind de los parámetros
            $stmt->bindParam(':id_income_accessory', $accessory['id_income_accessory'], PDO::PARAM_INT);
            $stmt->bindParam(':id_accessory', $accessory['id_accessory'], PDO::PARAM_INT);
            $stmt->bindParam(':serie', $accessory['serie'], PDO::PARAM_STR);
            $stmt->bindParam(':stock', $accessory['stock'], PDO::PARAM_INT);
            $stmt->bindParam(':purchase_price', $accessory['purchase_price'], PDO::PARAM_STR);
            $stmt->bindParam(':sale_price', $accessory['sale_price'], PDO::PARAM_STR);

            $stmt->execute();
            
            // Actualizar el stock en la tabla 'accessory'
            $sql_stock = 'UPDATE accessory SET accessory_stock = accessory_stock + :stock WHERE id_accessory = :id_accessory';
            $stmt_stock = $this->pdo->prepare($sql_stock); // <-- CORREGIDO
            $stmt_stock->bindParam(':stock', $accessory['stock'], PDO::PARAM_INT);
            $stmt_stock->bindParam(':id_accessory', $accessory['id_accessory'], PDO::PARAM_INT);
            $stmt_stock->execute();

            return true;
        } catch (PDOException $e) {
            return "ERROR SQL: " . $e->getMessage(); // Devuelve el error
        }
    }
    
    //--------------------- UPDATE TOTAL -----------------
    public function update_income_accessory_total($id_income_accessory, $total_purchase) {
        try {
            $sql = "UPDATE income_accessory SET full_purchase = :total_purchase WHERE id = :id_income_accessory";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':total_purchase', $total_purchase, PDO::PARAM_STR);
            $stmt->bindParam(':id_income_accessory', $id_income_accessory, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

// ------------------- DELETE -------------------
    public function delete_income_accessory($bind) {
        try {
            $this->pdo->beginTransaction(); // Iniciar transacción

            // 1. Obtener los detalles
            $sql_get_details = 'SELECT id_accessory, stock FROM income_accessory_details WHERE id_income_accessory = :id_income_accessory';
            
            $details = $this->pdo->fetchAll($sql_get_details, $bind); 

            if ($details) {
                // 2. Revertir el stock en la tabla 'accessory'
                $sql_update_stock = 'UPDATE accessory SET accessory_stock = accessory_stock - :stock WHERE id_accessory = :id_accessory';
                // PREPARAMOS la consulta una sola vez
                $stmt_update_stock = $this->pdo->prepare($sql_update_stock);
                foreach ($details as $item) {
                    // EJECUTAMOS el statement preparado
                    $stmt_update_stock->execute([
                        ':stock' => $item['stock'],
                        ':id_accessory' => $item['id_accessory']
                    ]);
                }
            }

            // 3. Eliminar los detalles
            $sql_delete_details = 'DELETE FROM income_accessory_details WHERE id_income_accessory = :id_income_accessory';
            // PREPARAMOS la consulta
            $stmt_delete_details = $this->pdo->prepare($sql_delete_details);
            // EJECUTAMOS el statement
            $stmt_delete_details->execute($bind);

            // 4. Eliminar el ingreso maestro
            $sql_delete_master = 'DELETE FROM income_accessory WHERE id = :id_income_accessory';
            // PREPARAMOS la consulta
            $stmt_delete_master = $this->pdo->prepare($sql_delete_master);
            // EJECUTAMOS el statement
            $stmt_delete_master->execute($bind);
            
            $this->pdo->commit(); // Confirmar transacción
            return ['status' => 'OK', 'result' => 'Registro eliminado y stock revertido.'];

        } catch (PDOException $e) {
            $this->pdo->rollBack();  // Revertir en caso de error
            return ['status' => 'EXCEPTION', 'result' => $e];
        }
    }

    // ------------------- GET DETAILS (NUEVO) -------------------
    public function get_income_accessory_details_by_id($id_income_accessory) {
        try {
            // 1. Obtener los datos del ingreso MAESTRO
            $sql_master = 'SELECT 
                ia.id AS id_income_accessory,
                COALESCE(c.name, c.business_name, "N/A") AS client_name,
                ia.date AS proof_date,
                ia.proof_series,
                ia.voucher_series,
                vt.description AS voucher_type_description,
                pt.description AS payment_type_description
            FROM income_accessory ia
            LEFT JOIN person c ON ia.id_client = c.id  
            LEFT JOIN voucher_type vt ON ia.id_voucher_type = vt.id  
            LEFT JOIN payment_type pt ON ia.id_payment_type = pt.id
            WHERE ia.id = :id_income_accessory';
            
            $master_data = $this->pdo->fetchOne($sql_master, ['id_income_accessory' => $id_income_accessory]);

            if (!$master_data) {
                return ['status' => 'NOT_FOUND', 'message' => 'No se encontró el ingreso maestro.'];
            }

            // 2. Obtener los accesorios DETALLE
            $sql_details = 'SELECT 
                iad.stock,
                iad.purchase_price,
                iad.sale_price,
                iad.serie,
                a.accessory_description,
                (iad.purchase_price * iad.stock) AS subtotal
            FROM income_accessory_details iad
            INNER JOIN accessory a ON iad.id_accessory = a.id_accessory
            WHERE iad.id_income_accessory = :id_income_accessory';

            $details_data = $this->pdo->fetchAll($sql_details, ['id_income_accessory' => $id_income_accessory]);

            // 3. Combinar ambos resultados
            $master_data['accessories'] = $details_data ?: []; // Asigna los detalles (o un array vacío)

            return ['status' => 'OK', 'result' => $master_data];
            
        } catch (PDOException $e) {
            return ['status' => 'EXCEPTION', 'message' => 'Error en la base de datos: ' . $e->getMessage()];
        }
    }
}
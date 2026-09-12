<?php  
// --
class M_Income_Products extends Model {
    // --
    public function __construct() {
        parent::__construct();
    }
    //---------------------------LISTADO DE INCOME_PRODUCTS Y INCOME_PRODUCTS_DETAILS----------------------------
    public function get_income_products() {
    try {

        $this->update_expired_products();

        $sql = "SELECT 
            f.id AS id_income_products,
            c.name,
            f.voucher_series AS series,
            f.number_serial,
            f.expiration_date,
            vt.description AS voucher_type_description,
            pt.description AS payment_type_description,
            pm.description AS payment_shape_description,
            COALESCE(SUM(d.full_purchase), 0) AS total_purchase,
            f.status
        FROM income_products f
        LEFT JOIN person c ON f.id_person = c.id
        LEFT JOIN voucher_type vt ON f.id_voucher_type = vt.id
        LEFT JOIN payment_type pt ON f.id_payment_type = pt.id
        LEFT JOIN payment_shape pm ON f.id_payment_shape = pm.id
        LEFT JOIN income_products_details d ON f.id = d.id_income_products
        GROUP BY 
            f.id, c.name, f.voucher_series, f.number_serial, f.expiration_date,
            vt.description, pt.description, pm.description, f.status
        ORDER BY f.id DESC";

        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            return ['status' => 'OK', 'result' => $result];
        } else {
            return ['status' => 'ERROR', 'result' => []];
        }

    } catch (PDOException $e) {
        return ['status' => 'EXCEPTION', 'result' => $e->getMessage()];
    }
}

    //-------------------------PRODUCTOS VENCIDOS------------------------------
    public function update_expired_products() {
        try {
            $sql = "UPDATE income_products 
                    SET status = 3  
                    WHERE expiration_date < NOW() 
                    AND status = 1";  
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
    
        } catch (PDOException $e) {
            // Si falla la actualización, se captura el error, pero no interrumpe la consulta
            error_log("Error actualizando productos vencidos: " . $e->getMessage());
        }
    }
    //--------------------------- BUSQUEDA POR ID DE INCOME_PRODUCTS Y INCOME_PRODUCTS_DETAILS----------------------------
    public function get_income_products_by_id($bind) {
        try {
            $sql = 'SELECT 
                        f.id AS id_income_products, 
                        c.name, 
                        f.sale_date, 
                        f.series, 
                        f.number_serial, 
                        f.expiration_date, 
                        vt.description AS voucher_type_description,
                        pt.description AS payment_type_description, 
                        pm.description AS payment_shape_description, 
                        COALESCE(SUM(d.full_purchase), 0) AS total_purchase, 
                        f.status 
                    FROM income_products f 
                    INNER JOIN person c ON f.id_person = c.id 
                    INNER JOIN voucher_type vt ON f.id_voucher_type = vt.id 
                    INNER JOIN payment_type pt ON f.id_payment_type = pt.id 
                    INNER JOIN payment_shape pm ON f.id_payment_shape = pm.id 
                    LEFT JOIN income_products_details d ON f.id = d.id_income_product 
                    WHERE f.id = :id';

            $result = $this->pdo->fetchOne($sql, $bind);

            if ($result) {
                return ['status' => 'OK', 'result' => $result];
            } else {
                return ['status' => 'ERROR', 'result' => []];
            }
        } catch (PDOException $e) {
            return ['status' => 'EXCEPTION', 'result' => $e->getMessage()];
        }
    }

    //--------------------- CREATE INCOME PRODUCTS -------------------------
    public function create_income_products($bind) {
        try {
            file_put_contents("log.txt", "\n=== MODEL: create_income_products ===\n", FILE_APPEND);
            file_put_contents("log.txt", "BIND RECIBIDO: " . json_encode($bind) . "\n", FILE_APPEND);

            // Estructura correcta de la tabla income_products
            $sql = "INSERT INTO income_products (
                id_person, id_user, id_voucher_type, voucher_series, number_serial, 
                expiration_date, id_payment_type, id_payment_shape, tax, purchase_total, status
            ) VALUES (
                :id_person, :id_user, :id_voucher_type, :voucher_series, :number_serial,
                :expiration_date, :id_payment_type, :id_payment_shape, :tax, :purchase_total, :status
            )";

            file_put_contents("log.txt", "SQL: $sql\n", FILE_APPEND);

            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($bind);

            file_put_contents("log.txt", "EXECUTE RESULT: " . ($result ? "TRUE" : "FALSE") . "\n", FILE_APPEND);

            if ($result) {
                $lastId = $this->pdo->lastInsertId();
                file_put_contents("log.txt", "✅ ÚLTIMO ID INSERTADO: $lastId\n", FILE_APPEND);
                return $lastId;
            } else {
                $errorInfo = $stmt->errorInfo();
                file_put_contents("log.txt", "❌ ERROR SQL: " . json_encode($errorInfo) . "\n", FILE_APPEND);
                return false;
            }
        } catch (PDOException $e) {
            file_put_contents("log.txt", "❌ EXCEPTION: " . $e->getMessage() . "\n", FILE_APPEND);
            file_put_contents("log.txt", "CODE: " . $e->getCode() . "\n", FILE_APPEND);
            return false;
        }
    }

    //--------------------- INSERT INCOME PRODUCTS DETAILS -----------------
    public function insertIncomeProductDetails($producto) {
        try {

            $sql = "INSERT INTO income_products_details (
                        id_income_products, id_product, quantity, full_purchase, 
                        product_expiration_date, selling_price
                    ) VALUES (
                        :id_income_products, :id_product, :quantity, :full_purchase,
                        :product_expiration_date, :selling_price
                    )";

            $stmt = $this->pdo->prepare($sql);

            // Usar full_purchase directamente (o subtotal como fallback)
            $full_purchase_val = $producto['full_purchase'] ?? $producto['subtotal'] ?? 0;
            // Campos opcionales con NULL por defecto si no vienen del controlador
            $product_expiration_date = $producto['product_expiration_date'] ?? null;
            $selling_price = $producto['selling_price'] ?? $full_purchase_val;

            // Bind de los parámetros con los tipos de datos correctos
            $stmt->bindParam(':id_income_products', $producto['id_income_products'], PDO::PARAM_INT);
            $stmt->bindParam(':id_product', $producto['id_product'], PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $producto['quantity'], PDO::PARAM_INT);
            $stmt->bindParam(':full_purchase', $full_purchase_val, PDO::PARAM_STR);
            $stmt->bindParam(':product_expiration_date', $product_expiration_date);
            $stmt->bindParam(':selling_price', $selling_price, PDO::PARAM_STR);

            $stmt->execute();

            return true;
            } catch (PDOException $e) {
            file_put_contents("log.txt", "ERROR SQL: " . $e->getMessage() . "\n", FILE_APPEND);
            return "ERROR SQL: " . $e->getMessage(); // Devuelve el error
        }
    }
    
    //-----------------------------  DETAILS ------------------------------
    public function get_income_product_details($id_income_product) {
        try {
            $sql = 'SELECT 
                f.id AS id_income_product,
                c.name AS client_name,
                f.sale_date,
                f.series,
                f.number_serial,
                f.expiration_date,
                vt.description AS voucher_type_description,
                pt.description AS payment_type_description,
                pm.description AS payment_shape_description,
                d.subtotal AS total_purchase,
                d.id AS income_products_details_id, 
                p.id_product, 
                p.product_name,
                d.quantity,
                d.full_purchase,
                d.subtotal,
                f.status
            FROM income_products f
            INNER JOIN person c ON f.id_client = c.id  
            INNER JOIN voucher_type vt ON f.id_voucher_type = vt.id  
            INNER JOIN payment_type pt ON f.id_payment_type = pt.id
            INNER JOIN payment_shape pm ON f.id_payment_shape = pm.id
            INNER JOIN income_products_details d ON f.id = d.id_income_products
            INNER JOIN product p ON d.id_product = p.id_product
            WHERE f.id = :id_income_product';

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':id_income_product', $id_income_product, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (!$rows) {
                return ['status' => 'NOT_FOUND', 'message' => 'No se encontraron detalles para este ingreso.'];
            }

            // Estructura los datos correctamente
            $incomeProduct = [
                'id_income_products' => $rows[0]['id_income_product'],
                'client_name' => $rows[0]['client_name'],
                'sale_date' => $rows[0]['sale_date'],
                'series' => $rows[0]['series'],
                'number_serial' => $rows[0]['number_serial'],
                'expiration_date' => $rows[0]['expiration_date'],
                'voucher_type' => $rows[0]['voucher_type_description'],
                'payment_type' => $rows[0]['payment_type_description'],
                'payment_shape' => $rows[0]['payment_shape_description'],
                'subtotal' => $rows[0]['total_purchase'],
                'status' => $rows[0]['status'],
                'products' => []
            ];

            foreach ($rows as $row) {
                $incomeProduct['products'][] = [
                    'id_product' => $row['id_product'],
                    'product_name' => $row['product_name'],
                    'quantity' => $row['quantity'],
                    'full_purchase' => $row['full_purchase'],
                    'subtotal' => $row['subtotal'] // Incluye el subtotal aquí
                ];
            }

            return ['status' => 'OK', 'result' => $incomeProduct];
        } catch (PDOException $e) {
            return ['status' => 'EXCEPTION', 'message' => 'Error en la base de datos: ' . $e->getMessage()];
        }
    }

    //----------------- Eliminar un ingreso y sus detalles ----------------- 
    public function delete_income_products($bind) {
        try {
            $this->pdo->beginTransaction(); // Iniciar transacción
        
            // Eliminar los detalles relacionados usando el nombre correcto de la columna
            $sqlDetails = 'DELETE FROM income_products_details WHERE id_income_products = :id_income_product';  // Asegúrate de que el nombre de la columna es 'id_income_products'
            $stmtDetails = $this->pdo->prepare($sqlDetails);
            $stmtDetails->execute($bind);
            $resultDetails = $stmtDetails->rowCount();  // Verificar filas afectadas
        
            // Eliminar el ingreso principal usando el nombre correcto de la columna
            $sqlIncome = 'DELETE FROM income_products WHERE id = :id_income_product';  // El campo 'id' en la tabla 'income_products' sigue siendo correcto
            $stmtIncome = $this->pdo->prepare($sqlIncome);
            $stmtIncome->execute($bind);
            $resultIncome = $stmtIncome->rowCount();  // Verificar filas afectadas
        
            // Verificar si se eliminaron filas
            if ($resultDetails > 0 || $resultIncome > 0) {
                $this->pdo->commit(); // Confirmar transacción
                $response = array('status' => 'OK', 'result' => array());
            } else {
                $this->pdo->rollBack(); // Revertir si no se eliminó nada
                $response = array('status' => 'ERROR', 'result' => 'No se eliminó ningún registro.');
            }
        } catch (PDOException $e) {
            $this->pdo->rollBack();  // Revertir en caso de excepción
            $response = array('status' => 'EXCEPTION', 'result' => $e->getMessage());
        }
        return $response;
    }
    
  
    //--------------------------- UPDATE -----------------------------------

    public function update_income_product($data) {
        try {
            // Iniciar transacción
            $this->pdo->beginTransaction();
    
            // Actualizar la compra principal
            $sql = "UPDATE income_products SET 
                        id_client = :id_client,
                        series = :series,
                        sale_date = CURRENT_TIMESTAMP,
                        number_serial = :number_serial,
                        expiration_date = :expiration_date,
                        id_voucher_type = :id_voucher_type,
                        id_payment_type = :id_payment_type,
                        id_payment_shape = :id_payment_shape
                    WHERE id = :id_income_product";
    
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':id_income_product', $data['id_income_product'], PDO::PARAM_INT);
            $stmt->bindValue(':id_client', $data['id_client'], PDO::PARAM_INT);
            $stmt->bindValue(':series', $data['series'], PDO::PARAM_STR);
            $stmt->bindValue(':number_serial', $data['number_serial'], PDO::PARAM_STR);
            $stmt->bindValue(':expiration_date', $data['expiration_date'], PDO::PARAM_STR);
            $stmt->bindValue(':id_voucher_type', $data['id_voucher_type'], PDO::PARAM_INT);
            $stmt->bindValue(':id_payment_type', $data['id_payment_type'], PDO::PARAM_INT);
            $stmt->bindValue(':id_payment_shape', $data['id_payment_shape'], PDO::PARAM_INT);
            $stmt->execute();
    
            // Eliminar los detalles de los productos anteriores (si es necesario)
            $deleteSQL = "DELETE FROM income_products_details WHERE id_income_products = :id_income_product";
            $deleteStmt = $this->pdo->prepare($deleteSQL);
            $deleteStmt->bindValue(':id_income_product', $data['id_income_product'], PDO::PARAM_INT);
            $deleteStmt->execute();
    
            // Insertar los nuevos detalles de los productos
            if (isset($data['products']) && count($data['products']) > 0) {
                foreach ($data['products'] as $producto) {
                    $productoData = [
                        'id_income_products' => $data['id_income_product'], // ID de la compra
                        'id_product' => $producto['id_product'], // ID del producto
                        'quantity' => $producto['quantity'], // Cantidad de productos
                        'subtotal' => $producto['subtotal'], // Subtotal calculado
                        'full_purchase' => $producto['full_purchase'] // Precio total por producto
                    ];
    
                    $this->insertIncomeProductDetails($productoData); // Llamar al modelo para insertar los detalles
                }
            }
    
            // Confirmar la transacción
            $this->pdo->commit();
    
            return [
                'status' => 'OK',
                'message' => 'Compra y productos actualizados correctamente',
                'id' => $data['id_income_product'] // Aquí se devuelve el ID
            ];
    
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            return ['status' => 'ERROR', 'message' => $e->getMessage()];
        }
    }
    
}
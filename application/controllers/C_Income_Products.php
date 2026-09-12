<?php 
// --
class C_Income_Products extends Controller {

    // --
    public function __construct() {
		parent::__construct();
    }
    
    // --
    public function index() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        $this->functions->check_permissions($this->segment->get('modules'), 'Income_Products');
        // --
        $this->view->set_js('index');       // -- Load JS
        $this->view->set_menu(array('modules' => $this->segment->get('modules'), 'view' => 'Income_Products')); // -- Active Menu
        $this->view->set_view('index');     // -- Load View
    }
    //---------------------------LISTADO DE INCOME_PRODUCTS Y INCOME_PRODUCTS_DETAILS----------------------------
    
    public function get_income_products() {

    $this->functions->validate_session($this->segment->get('isActive'));

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $obj = $this->load_model('Income_Products');

        // Actualizamos productos vencidos antes de obtener los datos
        $obj->update_expired_products();
        $response = $obj->get_income_products();

        switch ($response['status']) {
            case 'OK':
                $prod_pendientes = array_filter($response['result'], function($item) {
                    return $item['status'] == 1;
                });

                $warning_message = null;
                if (!empty($prod_pendientes)) {
                    $count_pedding = count($prod_pendientes);
                    $warning_message = array(
                        'show' => true,
                        'count' => $count_pedding,
                        'message' => "¡Atencion! Tiene {$count_pedding} registro(s) pendientes(s) por verificar.",
                        'action' => 'showPending'
                    );
                }

                $json = [
                    'status' => 'OK',
                    'type' => 'success',
                    'msg' => 'Listado de registros encontrados.',
                    'data' => $response['result'],
                    'warning' => $warning_message
                ];
                break;

            case 'ERROR':
                $json = [
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => 'No se encontraron registros en el sistema.',
                    'data' => []
                ];
                break;

            default:
                $json = [
                    'status' => 'ERROR',
                    'type' => 'error',
                    'msg' => 'Respuesta no válida del modelo.',
                    'data' => []
                ];
                break;
        }
    } else {
        $json = [
            'status' => 'ERROR',
            'type' => 'error',
            'msg' => 'Método no permitido.',
            'data' => []
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($json);
    exit;
}
    
    //--------------------------- BUSQUEDA POR ID DE INCOME_PRODUCTS Y INCOME_PRODUCTS_DETAILS----------------------------
    public function get_income_products_by_id() {
        $this->functions->validate_session($this->segment->get('isActive'));

        $request = $_SERVER['REQUEST_METHOD'];

        if ($request === 'GET') {
            // Obtener el ID desde la URL correctamente
            $input = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($input !== false && $input !== null) {
                $obj = $this->load_model('Income_Products');
                $bind = ['id' => $input]; // ✅ Pasa el ID como array asociativo

                $response = $obj->get_income_products_by_id($bind);

                switch ($response['status']) {
                    case 'OK':
                        $json = [
                            'status' => 'OK',
                            'type' => 'success',
                            'msg' => 'Ingreso de productos encontrado.',
                            'data' => $response['result']
                        ];
                        break;

                    case 'ERROR':
                        $json = [
                            'status' => 'ERROR',
                            'type' => 'warning',
                            'msg' => 'No se encontró el ingreso de productos.',
                            'data' => []
                        ];
                        break;

                    case 'EXCEPTION':
                        $json = [
                            'status' => 'ERROR',
                            'type' => 'error',
                            'msg' => $response['result'],
                            'data' => []
                        ];
                        break;
                }
            } else {
                $json = [
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => 'No se enviaron los campos necesarios o el ID no es válido.',
                    'data' => []
                ];
            }
        } else {
            $json = [
                'status' => 'ERROR',
                'type' => 'error',
                'msg' => 'Método no permitido.',
                'data' => []
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($json);
    }
    
    //-----------------------------  DETAILS ------------------------------
    public function income_products_details() {
        $this->functions->validate_session($this->segment->get('isActive'));

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!isset($_GET['id_income_products']) || empty($_GET['id_income_products'])) {
                $json = [
                    'status' => 'ERROR',
                    'type' => 'error',
                    'msg' => 'ID del ingreso de productos no proporcionado.'
                ];
            } else {
                $id_income_product = intval($_GET['id_income_products']);

                // Verificar que el ID sea válido
                if ($id_income_product <= 0) {
                    $json = [
                        'status' => 'ERROR',
                        'type' => 'error',
                        'msg' => 'ID del ingreso de productos no válido.'
                    ];
                } else {
                    $obj = $this->load_model('Income_Products');
                    $response = $obj->get_income_product_details($id_income_product);

                    if (isset($response['status']) && $response['status'] === 'OK') {
                        $json = [
                            'status' => 'OK',
                            'type' => 'success',
                            'msg' => 'Detalles del ingreso de productos encontrados.',
                            'result' => $response['result']
                        ];
                    } else {
                        $json = [
                            'status' => 'ERROR',
                            'type' => 'warning',
                            'msg' => 'No se encontraron detalles para este ingreso de productos.',
                            'result' => []
                        ];
                    }
                }
            }
        } else {
            $json = [
                'status' => 'ERROR',
                'type' => 'error',
                'msg' => 'Método no permitido.'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($json);
        exit;
    }

    //--------------------- CREATE INCOME PRODUCTS -------------------------
    public function create_income_products() {
        header('Content-Type: application/json');

        $this->functions->validate_session($this->segment->get('isActive'));

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input)) {
                $input = filter_input_array(INPUT_POST);
            }

            file_put_contents("log.txt", "\n=== CREATE INCOME PRODUCTS ===\n", FILE_APPEND);
            file_put_contents("log.txt", "INPUT RECIBIDO: " . json_encode($input, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

            if (!empty($input['id_client']) && !empty($input['id_voucher_type']) && !empty($input['id_payment_type'])
                && !empty($input['id_payment_shape']) && !empty($input['series']) && !empty($input['number_serial'])
                && !empty($input['expiration_date'])) {

                // Limpiar y validar los datos
                $id_person = $this->functions->clean_string($input['id_client']);           // Cliente
                $id_voucher_type = $this->functions->clean_string($input['id_voucher_type']);
                $id_payment_type = $this->functions->clean_string($input['id_payment_type']);
                $id_payment_shape = $this->functions->clean_string($input['id_payment_shape']);
                $voucher_series = $this->functions->clean_string($input['series']);        // Nota: se llama 'series' en el formulario pero es 'voucher_series' en BD
                $number_serial = $this->functions->clean_string($input['number_serial']);
                $expiration_date = $this->functions->clean_string($input['expiration_date']);
                
                // Obtener el ID del usuario desde sesión
                $id_user = $this->segment->get('userID') ?? 1;

                file_put_contents("log.txt", "DATOS LIMPIOS: id_person=$id_person, id_user=$id_user, id_voucher_type=$id_voucher_type\n", FILE_APPEND);
                file_put_contents("log.txt", "voucher_series=$voucher_series, number_serial=$number_serial, expiration_date=$expiration_date\n", FILE_APPEND);

                // Validar formato de fecha
                if (!strtotime($expiration_date)) {
                    file_put_contents("log.txt", "ERROR: Fecha inválida: $expiration_date\n", FILE_APPEND);
                    echo json_encode(['status' => 'ERROR', 'msg' => 'Fecha de expiración no válida.']);
                    return;
                }

                // Preparar los datos para la inserción
                $bind = array(
                    'id_person' => $id_person,
                    'id_user' => $id_user,
                    'id_voucher_type' => $id_voucher_type,
                    'voucher_series' => $voucher_series,
                    'number_serial' => $number_serial,
                    'expiration_date' => $expiration_date,
                    'id_payment_type' => $id_payment_type,
                    'id_payment_shape' => $id_payment_shape,
                    'tax' => 0,                           // Impuesto inicial
                    'purchase_total' => floatval($input['purchase_total']),              // Se calculará cuando se agreguen detalles
                    'status' => 1
                );

                file_put_contents("log.txt", "BIND PREPARADO: " . json_encode($bind, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

                // Insertar y obtener el ID generado
                $obj = $this->load_model('Income_Products');
                $insertedId = $obj->create_income_products($bind);

                file_put_contents("log.txt", "RESULTADO DE INSERT: insertedId=$insertedId\n", FILE_APPEND);

                if ($insertedId && $insertedId !== false) {
                    file_put_contents("log.txt", "✅ EXITOSO: Ingreso creado con ID $insertedId\n", FILE_APPEND);
                    echo json_encode(['status' => 'OK', 'id' => $insertedId]);
                } else {
                    file_put_contents("log.txt", "❌ ERROR: No se pudo insertar (insertedId es false o null)\n", FILE_APPEND);
                    echo json_encode(['status' => 'ERROR', 'msg' => 'No se pudo crear el ingreso.']);
                }
            } else {
                file_put_contents("log.txt", "❌ ERROR: Campos obligatorios faltantes\n", FILE_APPEND);
                file_put_contents("log.txt", "id_client: " . (empty($input['id_client']) ? "FALTA" : $input['id_client']) . "\n", FILE_APPEND);
                file_put_contents("log.txt", "id_voucher_type: " . (empty($input['id_voucher_type']) ? "FALTA" : $input['id_voucher_type']) . "\n", FILE_APPEND);
                file_put_contents("log.txt", "id_payment_type: " . (empty($input['id_payment_type']) ? "FALTA" : $input['id_payment_type']) . "\n", FILE_APPEND);
                file_put_contents("log.txt", "id_payment_shape: " . (empty($input['id_payment_shape']) ? "FALTA" : $input['id_payment_shape']) . "\n", FILE_APPEND);
                echo json_encode(['status' => 'ERROR', 'msg' => 'Campos obligatorios faltantes.']);
            }
        }
    }

    //--------------------- INSERT INCOME PRODUCTS DETAILS -------------------------
    public function create_income_products_details() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);

            // Loguear entrada
            file_put_contents("log.txt", "CREATE DETAILS - Input: " . json_encode($input) . "\n", FILE_APPEND);

            // Asegurarse de que se han enviado productos
            if (empty($input['productos'])) {
                echo json_encode(['status' => 'ERROR', 'msg' => 'No se enviaron productos.']);
                return;
            }

            $obj = $this->load_model('Income_Products');
            $insertCount = 0;
            $errors = [];

            foreach ($input['productos'] as $producto) {
                // Validar que producto tenga los campos requeridos
                if (empty($producto['id_income_products']) || empty($producto['id_product']) || empty($producto['quantity']) || empty($producto['full_purchase'])) {
                    $errors[] = "Producto incompleto: " . json_encode($producto);
                    continue;
                }

                // Limpiar y preparar los datos
                $productoLimpio = [
                    'id_income_products' => intval($producto['id_income_products']),
                    'id_product' => intval($producto['id_product']),
                    'quantity' => intval($producto['quantity']),
                    'full_purchase' => floatval($producto['full_purchase']),
                    'subtotal' => floatval($producto['subtotal'] ?? ($producto['quantity'] * $producto['full_purchase']))
                ];

                $result = $obj->insertIncomeProductDetails($productoLimpio);
                
                if ($result === true) {
                    $insertCount++;
                } else {
                    $errors[] = $result;
                }
            }

            file_put_contents("log.txt", "INSERT COUNT: $insertCount, ERRORS: " . json_encode($errors) . "\n", FILE_APPEND);

            if ($insertCount > 0) {
                echo json_encode(['status' => 'OK', 'message' => 'Productos insertados correctamente.', 'insertados' => $insertCount]);
            } else {
                echo json_encode(['status' => 'ERROR', 'msg' => 'No se pudieron insertar los productos.', 'errors' => $errors]);
            }
        }
    }

    //----------------- Eliminar un ingreso y sus detalles ----------------- 
    public function delete_income_products() {


            $this->functions->validate_session($this->segment->get('isActive')); // Validar sesión
            $request = $_SERVER['REQUEST_METHOD'];
        
            if ($request === 'POST') {
                $input = json_decode(file_get_contents('php://input'), true);
                if (empty($input)) {
                    $input = filter_input_array(INPUT_POST);
                }
        
                if (!empty($input['id_income_product'])) {
                    $bind = array(
                        'id_income_product' => intval($input['id_income_product'])
                    );
        
                    $obj = $this->load_model('Income_Products'); // Cargar modelo
                    $response = $obj->delete_income_products($bind); // Ejecutar eliminación
        
                    switch ($response['status']) {
                        case 'OK':
                            $json = array(
                                'status' => 'OK',
                                'type' => 'success',
                                'msg' => 'Registro eliminado exitosamente.',
                                'data' => array()
                            );
                            break;
                        case 'ERROR':
                            $json = array(
                                'status' => 'ERROR',
                                'type' => 'warning',
                                'msg' => 'No se pudo eliminar el registro, verificar.',
                                'data' => array(),
                            );
                            break;
                        case 'EXCEPTION':
                            $json = array(
                                'status' => 'ERROR',
                                'type' => 'error',
                                'msg' => $response['result']->getMessage(),
                                'data' => array()
                            );
                            break;
                    }
                } else {
                    $json = array(
                        'status' => 'ERROR',
                        'type' => 'warning',
                        'msg' => 'No se enviaron los campos necesarios, verificar.',
                        'data' => array()
                    );
                }
            } else {
                $json = array(
                    'status' => 'ERROR',
                    'type' => 'error',
                    'msg' => 'Método no permitido.',
                    'data' => array()
                );
            }
        
            header('Content-Type: application/json');
            echo json_encode($json);
    }
    //--------------------------- UPDATE -----------------------------------

    public function update_income_product() {
        $this->functions->validate_session($this->segment->get('isActive'));
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input)) {
                $input = filter_input_array(INPUT_POST);
            }
    
           
            if (isset($input['products']) && count($input['products']) > 0) {
                $products = $input['products'];  
                unset($input['products']);      
    
             
                $obj = $this->load_model('Income_Products');
                $response = $obj->update_income_product($input);
    
                if ($response['status'] === 'OK') {
                    foreach ($products as $producto) {
                        $productoData = [
                            'id_income_products' => $input['id_income_product'], 
                            'id_product' => $producto['id_product'], 
                            'quantity' => $producto['quantity'], 
                            'subtotal' => $producto['subtotal'], 
                            'full_purchase' => $producto['full_purchase']
                        ];
                        $obj->insertIncomeProductDetails($productoData);
                    }
                }

                $json = [
                    'status' => $response['status'],
                    'type' => $response['status'] === 'OK' ? 'success' : 'error',
                    'msg' => $response['message'] ?? 'Ocurrió un error al actualizar la compra.'
                ];
            } else {
               
                $obj = $this->load_model('Income_Products');
                $response = $obj->update_income_product($input);
                
                $json = [
                    'status' => $response['status'],
                    'type' => $response['status'] === 'OK' ? 'success' : 'error',
                    'msg' => $response['message'] ?? 'Ocurrió un error al actualizar la compra.'
                ];
            }
        } else {
            $json = [
                'status' => 'ERROR',
                'type' => 'error',
                'msg' => 'Método no permitido.'
            ];
        }
    
        header('Content-Type: application/json');
        echo json_encode($json);
    }
    
}   



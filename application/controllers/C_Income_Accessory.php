<?php 
// --
class C_Income_Accessory extends Controller {

    // --
    public function __construct() {
        parent::__construct();
    }
    
    // --
    public function index() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        $this->functions->check_permissions($this->segment->get('modules'), 'Income_Accessory');
        // --
        $this->view->set_js('index');       // -- Load JS
        $this->view->set_menu(array('modules' => $this->segment->get('modules'), 'view' => 'Income_Accessory')); // -- Active Menu
        $this->view->set_view('index');     // -- Load View
    }
    public function get_income_accessory() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        // --
        $request = $_SERVER['REQUEST_METHOD'];
        // --
        if ($request === 'GET') {
            // --
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input)) {
                $input = filter_input_array(INPUT_GET);
            }
            // --
            $obj = $this->load_model('Income_Accessory');
            // --
            $response = $obj->get_income_accessory();
            
            // --
            switch ($response['status']) {
                // --
                case 'OK':
                $json = array(
                    'draw' => $response['result']['draw'],  // Top-level para DataTables
                    'recordsTotal' => $response['result']['recordsTotal'],
                    'recordsFiltered' => $response['result']['recordsFiltered'],
                    'data' => $response['result']['data'],  // El array de filas directo aquí
                    'status' => 'OK',
                    'type' => 'success',
                    'msg' => 'Listado de registros encontrados.'
                );
                break;
            case 'ERROR':
                $json = array(
                    'draw' => intval($_GET['draw'] ?? 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => array(),
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => 'No se encontraron registros en el sistema.'
                );
                break;
            case 'EXCEPTION':
                $json = array(
                    'draw' => intval($_GET['draw'] ?? 1),
                    'recordsTotal' => 0,
                    'recordsFiltered' => 0,
                    'data' => array(),
                    'status' => 'ERROR',
                    'type' => 'error',
                    'msg' => $response['result']->getMessage()
                );
                break;
            }
            
        } else {
            // --
            $json = array(
                'status' => 'ERROR',
                'type' => 'error',
                'msg' => 'Método no permitido.',
                'data' => array()
            ); 
        }

        // --
        header('Content-Type: application/json');
        echo json_encode($json);
        exit;
    }

//--------------------- CREATE INCOME ACCESSORY -------------------------
    public function create_income_accessory() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input)) {
                $input = filter_input_array(INPUT_POST);
            }

            if (!empty($input['id_client']) && !empty($input['id_voucher_type']) && !empty($input['id_payment_type'])
                && !empty($input['proof_series']) && !empty($input['voucher_series'])
                && !empty($input['proof_date'])) {

                $bind = array(
                    'id_client' => $this->functions->clean_string($input['id_client']),
                    'id_voucher_type' => $this->functions->clean_string($input['id_voucher_type']),
                    'id_payment_type' => $this->functions->clean_string($input['id_payment_type']),
                    'proof_series' => $this->functions->clean_string($input['proof_series']),
                    'voucher_series' => $this->functions->clean_string($input['voucher_series']),
                    'date' => $this->functions->clean_string($input['proof_date']),
                    'id_user' => 1, 
                    'igv' => 0.00,
                    'full_purchase' => 0.00,
                    'status' => 1
                );

                $obj = $this->load_model('Income_Accessory');
                
                $response = $obj->create_income_accessory($bind);

                
                if ($response['status'] === 'OK') {
                    // Éxito
                    echo json_encode(['status' => 'OK', 'id' => $response['id']]);
                } else {
                    // Error de base de datos
                    echo json_encode(['status' => 'ERROR', 'msg' => $response['message']]);
                }
            } else {
                echo json_encode(['status' => 'ERROR', 'msg' => 'Campos obligatorios faltantes.']);
            }
        }
    }

    //--------------------- INSERT INCOME ACCESSORY DETAILS -------------------------
    public function create_income_accessory_details() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);

            // Asegurarse de que se han enviado accesorios
            if (empty($input['accessories'])) {
                echo json_encode(['status' => 'ERROR', 'msg' => 'No se enviaron accesorios.']);
                return;
            }

            $obj = $this->load_model('Income_Accessory'); // <-- CORREGIDO
            $insertCount = 0;
            $total_purchase = 0;

            foreach ($input['accessories'] as $accessory) {
                // Insertar el detalle
                $result = $obj->insertIncomeAccessoryDetails($accessory);
                if ($result === true) {
                    $insertCount++;
                    // Acumular el subtotal para actualizar el maestro
                    $total_purchase += (floatval($accessory['purchase_price']) * intval($accessory['stock']));
                }
            }

            if ($insertCount > 0) {
                // Actualizar el monto total en la tabla maestra 'income_accessory'
                $id_income_accessory = $input['accessories'][0]['id_income_accessory']; // Tomamos el ID del primer item
                $obj->update_income_accessory_total($id_income_accessory, $total_purchase);
                
                echo json_encode(['status' => 'OK', 'message' => 'Accesorios insertados correctamente.', 'insertados' => $insertCount]);
            } else {
                echo json_encode(['status' => 'ERROR', 'msg' => 'No se pudieron insertar los accesorios.']);
            }
        }
    }

// ------------------- DELETE -------------------
    public function delete_income_accessory() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            if (empty($input)) {
                $input = filter_input_array(INPUT_POST); 
            }

            if (!empty($input['id_income_accessory'])) {
                $bind = array(
                    'id_income_accessory' => intval($input['id_income_accessory'])
                );

                $obj = $this->load_model('Income_Accessory'); 
                $response = $obj->delete_income_accessory($bind); // Llama al modelo

                switch ($response['status']) {
                    case 'OK':
                        $json = array('status' => 'OK', 'type' => 'success', 'msg' => 'Registro eliminado exitosamente.');
                        break;
                    case 'ERROR':
                        $json = array('status' => 'ERROR', 'type' => 'warning', 'msg' => $response['result']);
                        break;
                    case 'EXCEPTION':
                        $json = array('status' => 'ERROR', 'type' => 'error', 'msg' => $response['result']->getMessage());
                        break;
                }
            } else {
                $json = array('status' => 'ERROR', 'type' => 'warning', 'msg' => 'No se recibió el ID.');
            }
        } else {
            $json = array('status' => 'ERROR', 'type' => 'error', 'msg' => 'Método no permitido.');
        }

        echo json_encode($json);
        exit;
    }

    // ------------------- GET DETAILS (NUEVO) -------------------
    public function get_income_accessory_details() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!isset($_GET['id_income_accessory']) || empty($_GET['id_income_accessory'])) {
                $json = array('status' => 'ERROR', 'msg' => 'ID de ingreso no proporcionado.');
            } else {
                $id_income_accessory = intval($_GET['id_income_accessory']);

                if ($id_income_accessory <= 0) {
                    $json = array('status' => 'ERROR', 'msg' => 'ID de ingreso no válido.');
                } else {
                    $obj = $this->load_model('Income_Accessory');
                    // Llamamos a la nueva función del modelo
                    $response = $obj->get_income_accessory_details_by_id($id_income_accessory); 

                    if (isset($response['status']) && $response['status'] === 'OK') {
                        $json = array(
                            'status' => 'OK', 
                            'type' => 'success', 
                            'msg' => 'Detalles encontrados.',
                            'result' => $response['result'] // Enviamos los datos al JS
                        );
                    } else {
                        $json = array('status' => 'ERROR', 'type' => 'warning', 'msg' => 'No se encontraron detalles para este ingreso.');
                    }
                }
            }
        } else {
            $json = array('status' => 'ERROR', 'type' => 'error', 'msg' => 'Método no permitido.');
        }

        echo json_encode($json);
        exit;
    }
}

<?php
// --
class C_Companies extends Controller {

    // --
    public function __construct() {
		parent::__construct();
    }

    // --
    public function index() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        $this->functions->check_permissions($this->segment->get('modules'), 'Companies');
        // --
        $this->view->set_js('index');       // -- Load JS
        $this->view->set_menu(array('modules' => $this->segment->get('modules'), 'view' => 'Companies')); // -- Active Menu
        $this->view->set_view('index');     // -- Load View
    }

    // --
    private function normalize_input($request) {
        // --
        $input = json_decode(file_get_contents('php://input'), true);
        // --
        if (empty($input)) {
            $input = $request === 'GET' ? filter_input_array(INPUT_GET) : filter_input_array(INPUT_POST);
        }
        // --
        foreach ($input as $key => $value) {
            // -- Campo de texto libre (condiciones comerciales): no se limpia
            if ($key === 'commercial_conditions') {
                $input[$key] = $value;
            } elseif (is_array($value)) {
                // -- Listados (huéspedes): limpiar cada elemento
                foreach ($value as $subkey => $subvalue) {
                    $input[$key][$subkey] = is_string($subvalue) ? $this->functions->clean_string($subvalue) : $subvalue;
                }
            } elseif (is_string($value)) {
                $input[$key] = $this->functions->clean_string($value);
            }
        }
        // --
        return $input;
    }

    // --
    public function get_companies() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        // --
        $request = $_SERVER['REQUEST_METHOD'];
        // --
        if ($request === 'GET') {
            // --
            $obj = $this->load_model('Companies');
            // --
            $response = $obj->get_companies();
            // --
            switch ($response['status']) {
                // --
                case 'OK':
                    // --
                    $json = array(
                        'status' => 'OK',
                        'type' => 'success',
                        'msg' => 'Listado de registros encontrados.',
                        'data' => $response['result']
                    );
                    // --
                    break;

                case 'ERROR':
                    // --
                    $json = array(
                        'status' => 'ERROR',
                        'type' => 'warning',
                        'msg' => 'No se encontraron registros en el sistema.',
                        'data' => array(),
                    );
                    // --
                    break;

                case 'EXCEPTION':
                    // --
                    $json = array(
                        'status' => 'ERROR',
                        'type' => 'error',
                        'msg' => $response['result']->getMessage(),
                        'data' => array()
                    );
                    // --
                    break;
            }
            // --
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
    }

    // --
    public function get_company_by_id() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        // --
        $request = $_SERVER['REQUEST_METHOD'];
        // --
        if ($request === 'GET') {
            // --
            $input = $this->normalize_input($request);
            // --
            if (!empty($input['id_company'])) {
                // --
                $obj = $this->load_model('Companies');
                // --
                $bind = array('id_company' => intval($input['id_company']));
                // --
                $response = $obj->get_company_by_id($bind);
                // --
                switch ($response['status']) {
                    // --
                    case 'OK':
                        // --
                        $json = array(
                            'status' => 'OK',
                            'type' => 'success',
                            'msg' => 'Listado de registros encontrados.',
                            'data' => $response['result']
                        );
                        // --
                        break;

                    case 'ERROR':
                        // --
                        $json = array(
                            'status' => 'ERROR',
                            'type' => 'warning',
                            'msg' => 'No se encontró la empresa en el sistema.',
                            'data' => array(),
                        );
                        // --
                        break;

                    case 'EXCEPTION':
                        // --
                        $json = array(
                            'status' => 'ERROR',
                            'type' => 'error',
                            'msg' => $response['result']->getMessage(),
                            'data' => array()
                        );
                        // --
                        break;
                }
            } else {
                // --
                $json = array(
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => 'No se enviaron los campos necesarios, verificar.',
                    'data' => array()
                );
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
    }

    // --
    public function create_company() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        // --
        $response = $this->save(false);
        // --
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // --
    public function update_company() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        // --
        $response = $this->save(true);
        // --
        header('Content-Type: application/json');
        echo json_encode($response);
    }

    // --
    private function save($update) {
        // --
        $request = $_SERVER['REQUEST_METHOD'];
        // --
        if ($request !== 'POST') {
            // --
            return array(
                'status' => 'ERROR',
                'type' => 'error',
                'msg' => 'Método no permitido.',
                'data' => array()
            );
        }
        // --
        $input = $this->normalize_input($request);
        // --
        if ($update && empty($input['id_company'])) {
            // --
            return array(
                'status' => 'ERROR',
                'type' => 'warning',
                'msg' => 'No se enviaron los campos necesarios, verificar.',
                'data' => array()
            );
        }
        // --
        $bind = array(
            'company_name' => strtoupper(trim($input['company_name'] ?? '')),
            'ruc' => trim($input['ruc'] ?? ''),
            'business_name' => strtoupper(trim($input['business_name'] ?? '')),
            'contact_name' => trim($input['contact_name'] ?? ''),
            'contact_email' => trim($input['contact_email'] ?? ''),
            'contact_phone' => trim($input['contact_phone'] ?? ''),
            'commercial_conditions' => $input['commercial_conditions'] ?? '',
            'corporate_tariff' => trim($input['corporate_tariff'] ?? ''),
            'credit_limit' => trim($input['credit_limit'] ?? ''),
            'guests' => $input['guests'] ?? array()
        );
        // --
        if ($update) {
            $bind['id_company'] = intval($input['id_company']);
        }
        // --
        $obj = $this->load_model('Companies');
        // --
        $update ? $response = $obj->update_company($bind) : $response = $obj->create_company($bind);
        // --
        switch ($response['status']) {
            // --
            case 'OK':
                // --
                return array(
                    'status' => 'OK',
                    'type' => 'success',
                    'msg' => $update ? 'Registro actualizado en el sistema con éxito.' : 'Registro almacenado en el sistema con éxito.',
                    'data' => $response['result'] ?? array()
                );
                // --

            case 'ERROR':
                // --
                return array(
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => is_string($response['result']) ? $response['result'] : 'No fue posible guardar el registro ingresado, verificar.',
                    'data' => array(),
                );
                // --

            case 'EXCEPTION':
                // --
                return array(
                    'status' => 'ERROR',
                    'type' => 'error',
                    'msg' => $response['result']->getMessage(),
                    'data' => array()
                );
                // --
        }
    }

    // --
    public function delete_company() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        // --
        $request = $_SERVER['REQUEST_METHOD'];
        // --
        if ($request === 'POST') {
            // --
            $input = $this->normalize_input($request);
            // --
            if (!empty($input['id_company'])) {
                // --
                $bind = array('id_company' => intval($input['id_company']));
                // --
                $obj = $this->load_model('Companies');
                // --
                $response = $obj->delete_company($bind);
                // --
                switch ($response['status']) {
                    // --
                    case 'OK':
                        // --
                        $json = array(
                            'status' => 'OK',
                            'type' => 'success',
                            'msg' => 'Registro eliminado del sistema con éxito.',
                            'data' => array()
                        );
                        // --
                        break;

                    case 'ERROR':
                        // --
                        $json = array(
                            'status' => 'ERROR',
                            'type' => 'warning',
                            'msg' => 'No fue posible eliminar el registro, verificar.',
                            'data' => array(),
                        );
                        // --
                        break;

                    case 'EXCEPTION':
                        // --
                        $json = array(
                            'status' => 'ERROR',
                            'type' => 'error',
                            'msg' => $response['result']->getMessage(),
                            'data' => array()
                        );
                        // --
                        break;
                }
            } else {
                // --
                $json = array(
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => 'No se enviaron los campos necesarios, verificar.',
                    'data' => array()
                );
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
    }

    // --
    public function get_company_data() {
        // --
        $this->functions->validate_session($this->segment->get('isActive'));
        // --
        $request = $_SERVER['REQUEST_METHOD'];
        // --
        if ($request === 'GET') {
            // --
            $input = filter_input_array(INPUT_GET);
            // --
            if (!empty($input['nroDoc'])) {
                // --
                $nroDoc = $this->functions->clean_string($input['nroDoc']);
                // --
                $token = 'apis-token-11708.QYpYnDltztORYLPzFYZCi6PylhhdjRl2';
                // --
                if (strlen($nroDoc) == 11) {
                    // --
                    $url = 'https://api.apis.net.pe/v2/sunat/ruc?numero=' . $nroDoc;
                    // --
                } else {
                    // --
                    header('Content-Type: application/json');
                    echo json_encode(array(
                        'status' => 'ERROR',
                        'type' => 'warning',
                        'msg' => 'Ingrese un RUC de 11 dígitos.',
                        'data' => array()
                    ));
                    return;
                }
                // --
                $curl = curl_init();
                // --
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => true,
                    CURLOPT_CONNECTTIMEOUT => 5,
                    CURLOPT_TIMEOUT => 15,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Referer: https://apis.net.pe',
                        'Authorization: Bearer ' . $token
                    ),
                ));
                // --
                $response = curl_exec($curl);
                // --
                curl_close($curl);
                // --
                $company = json_decode($response);
                // --
                if ($company && !isset($company->message)) {
                    // --
                    $json = array(
                        'status' => 'OK',
                        'type' => 'success',
                        'msg' => 'Datos obtenidos con éxito.',
                        'data' => $company
                    );
                    // --
                } else {
                    // --
                    $json = array(
                        'status' => 'ERROR',
                        'type' => 'error',
                        'msg' => $company->message ?? 'Error desconocido al consultar.',
                        'data' => array()
                    );
                }
            } else {
                // --
                $json = array(
                    'status' => 'ERROR',
                    'type' => 'error',
                    'msg' => 'No se envió el número de documento.',
                    'data' => array()
                );
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
    }
}
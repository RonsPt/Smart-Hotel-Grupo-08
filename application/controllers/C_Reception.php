<?php
class C_Reception extends Controller
{
    public function index()
    {
        $this->functions->validate_session($this->segment->get('isActive'));
        $this->functions->check_permissions($this->segment->get('modules'), 'Reception');
        $this->view->set_js('index');
        $this->view->set_menu(['modules' => $this->segment->get('modules'), 'view' => 'Reception']);
        $this->view->set_view('index');
    }

    private function respond($method, $operation)
    {
        $this->functions->validate_session($this->segment->get('isActive'));
        header('Content-Type: application/json; charset=utf-8');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== $method) {
                http_response_code(405);
                throw new InvalidArgumentException('Método no permitido.');
            }
            $input = $method === 'GET' ? $_GET : (json_decode(file_get_contents('php://input'), true) ?: $_POST);
            $result = $operation(is_array($input) ? $input : []);
            if (($result['status'] ?? '') === 'EXCEPTION') {
                throw $result['result'];
            }
            $status = $result['status'] ?? 'OK';
            echo json_encode(['status' => $status, 'type' => $status === 'OK' ? 'success' : 'warning',
                'msg' => $result['msg'] ?? ($status === 'OK' ? 'Operación completada.' : 'La habitación ya está reservada para esas fechas.'),
                'data' => $result['data'] ?? $result['result'] ?? [], 'existing' => $result['existing'] ?? false], JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            $known = $e instanceof InvalidArgumentException;
            if (!$known) { error_log('Reception: ' . $e->getMessage()); }
            echo json_encode(['status' => 'ERROR', 'type' => $known ? 'warning' : 'error',
                'msg' => $known ? $e->getMessage() : 'No se pudo completar la operación. Verifique la conexión y la migración de huéspedes.', 'data' => []], JSON_UNESCAPED_UNICODE);
        }
    }

    private function callModel($method, $action, $fields = null)
    {
        $this->respond($method, function ($input) use ($action, $fields) {
            $model = $this->load_model('Reception');
            if ($fields === null) { return $model->$action(); }
            $bind = [];
            foreach ($fields as $key) {
                if (!isset($input[$key]) || !is_scalar($input[$key]) || trim((string) $input[$key]) === '') {
                    throw new InvalidArgumentException('Complete el campo ' . $key . '.');
                }
                $bind[$key] = trim((string) $input[$key]);
            }
            return $model->$action($bind);
        });
    }

    public function get_rooms() { $this->callModel('GET', 'get_rooms'); }
    public function get_room_by_status() { $this->callModel('GET', 'get_room_by_status', ['room_status']); }
    public function get_room_by_id() { $this->callModel('GET', 'get_room_by_id', ['id_room']); }
    public function get_rooms_price() { $this->callModel('GET', 'get_rooms_price', ['type_name']); }
    public function get_guest() { $this->callModel('GET', 'get_guest', ['document_type', 'document_number']); }
    public function get_reservation_room() { $this->callModel('GET', 'get_reservation_room', ['id_room']); }
    public function date_reservation() { $this->callModel('GET', 'date_reservation', ['id_room', 'checkin_date', 'checkout_date']); }
    public function update_state() { $this->callModel('POST', 'update_state', ['id_room', 'room_status']); }
    public function update_state_reservation() { $this->update_state(); }
    public function clean_rooms() { $this->callModel('POST', 'clean_rooms', ['id_room']); }

    public function create_guest_reservation()
    {
        $this->respond('POST', function ($input) { return $this->load_model('Reception')->create_guest_reservation($input); });
    }

    public function create_reservation()
    {
        $this->respond('POST', function ($input) { return $this->load_model('Reception')->create_reservation($input); });
    }

    public function create_reservation_free() { $this->create_reservation(); }

    public function get_document_types()
    {
        $this->respond('GET', function () { return $this->load_model('Main')->get_document_types(); });
    }
}

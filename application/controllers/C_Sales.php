<?php
// --
class C_Sales extends Controller
{

  // --
  public function __construct()
  {
    parent::__construct();
  }

  // --
  public function index()
  {
    // --
    $this->functions->validate_session($this->segment->get('isActive'));
    $this->functions->check_permissions($this->segment->get('modules'), 'Sales');
    // --
    $this->view->set_js('index');       // -- Load JS
    $this->view->set_menu(array('modules' => $this->segment->get('modules'), 'view' => 'Sales')); // -- Active Menu
    $this->view->set_view('index');     // -- Load View
  }
  public function get_reservation_by_id()
  {
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
      if (!empty($input['id_reservation'])) {
        // --
        $obj = $this->load_model('Sales');
        // --
        $bind = array('id_reservation' => intval($input['id_reservation']));
        // --
        $response = $obj->get_reservation_by_id($bind);
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

  public function sales_product_history()
  {
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
      if (!empty($input['id_reservation'])) {
        // --
        $obj = $this->load_model('Sales');
        // --
        $bind = array('id_reservation' => intval($input['id_reservation']));
        // --
        $response = $obj->sales_product_history($bind);
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

  public function update_payment_total()
  {
      $this->functions->validate_session($this->segment->get('isActive'));
  
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $input = json_decode(file_get_contents('php://input'), true);
  
          if (empty($input)) {
              header('Content-Type: application/json');
              echo json_encode([
                  'status' => 'ERROR',
                  'msg' => 'No se enviaron datos. Verifica el cuerpo de la solicitud.',
                  'data' => []
              ]);
              return;
          }
  
          // Validar que id_reservation está definido y no vacío
          if (empty($input['id_reservation'])) {
              header('Content-Type: application/json');
              echo json_encode([
                  'status' => 'ERROR',
                  'msg' => 'El campo id_reservation es obligatorio.',
                  'data' => []
              ]);
              return;
          }
  
          // Validar que payment_sales está definido (puede ser 0, pero no nulo)
          if (!isset($input['payment_sales'])) {
              header('Content-Type: application/json');
              echo json_encode([
                  'status' => 'ERROR',
                  'msg' => 'El campo payment_sales es obligatorio.',
                  'data' => []
              ]);
              return;
          }
  
          $id_reservation = intval($input['id_reservation']);
          $payment_sales = floatval($input['payment_sales']);
  
          // Llama al modelo para actualizar la base de datos
          $obj = $this->load_model('Sales');
          $bind = [
              'id_reservation' => $id_reservation,
              'payment_sales' => $payment_sales
          ];
          $response = $obj->update_payment_total($bind);
  
          if ($response['status'] === 'OK') {
              header('Content-Type: application/json');
              echo json_encode([
                  'status' => 'OK',
                  'msg' => 'Total de pago actualizado correctamente.',
                  'data' => $response['result']
              ]);
          } else {
              header('Content-Type: application/json');
              echo json_encode([
                  'status' => 'ERROR',
                  'msg' => 'Error al actualizar el total de pago.',
                  'data' => $response['result']
              ]);
          }
      } else {
          header('Content-Type: application/json');
          echo json_encode([
              'status' => 'ERROR',
              'msg' => 'Método no permitido.',
              'data' => []
          ]);
      }
  }

  public function delete_reservation() {
      $this->functions->validate_session($this->segment->get('isActive'));

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          $id_reservation = filter_input(INPUT_POST, 'id_reservation', FILTER_VALIDATE_INT);

          if (!$id_reservation) {
              echo json_encode([
                  'status' => 'ERROR',
                  'msg' => 'ID de reservación inválido.',
                  'data' => []
              ]);
              return;
          }

          $obj = $this->load_model('Sales');
          $response = $obj->delete_reservation($id_reservation);

          if ($response['status'] === 'OK') {
              echo json_encode([
                  'status' => 'OK',
                  'msg' => 'Reservación eliminada correctamente.',
                  'data' => []
              ]);
          } else {
              echo json_encode([
                  'status' => 'ERROR',
                  'msg' => 'Error al eliminar la reservación.',
                  'data' => $response['result']
              ]);
          }
      } else {
          echo json_encode([
              'status' => 'ERROR',
              'msg' => 'Método no permitido.',
              'data' => []
          ]);
      }
  }

}



<?php
// --
class C_Meal extends Controller
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
    $this->functions->check_permissions($this->segment->get('modules'), 'Meal');
    // --
    $this->view->set_js('index');       // -- Load JS
    $this->view->set_menu(array('modules' => $this->segment->get('modules'), 'view' => 'Meal')); // -- Active Menu
    $this->view->set_view('index');     // -- Load View
  }

  // -- Obtener todas las comidas
  public function get_meal() {
    $this->functions->validate_session($this->segment->get('isActive'));
    $request = $_SERVER['REQUEST_METHOD'];

    if ($request === 'GET') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input)) {
            $input = filter_input_array(INPUT_GET);
        }

        $obj = $this->load_model('Meal');
        $response = $obj->get_meal();

        switch ($response['status']) {
            case 'OK':
                $json = array(
                    'status' => 'OK',
                    'type' => 'success',
                    'msg' => 'Listado de comidas encontradas.',
                    'data' => $response['result']
                );
                break;
            case 'ERROR':
                $json = array(
                    'status' => 'ERROR',
                    'type' => 'warning',
                    'msg' => 'No se encontraron comidas.',
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
            'type' => 'error',
            'msg' => 'Método no permitido.',
            'data' => array()
        );
    }

    header('Content-Type: application/json');
    echo json_encode($json);
  }

  // -- Obtener comida por ID
  public function get_meal_by_id() {
      $this->functions->validate_session($this->segment->get('isActive'));

      $request = $_SERVER['REQUEST_METHOD'];

      if ($request === 'GET') {
          $input = json_decode(file_get_contents('php://input'), true);
          if (empty($input)) {
              $input = filter_input_array(INPUT_GET);
          }

          if (!empty($input['id_meal'])) {
              $obj = $this->load_model('Meal');
              $bind = array('id_meal' => intval($input['id_meal']));
              $response = $obj->get_meal_by_id($bind);

              switch ($response['status']) {
                  case 'OK':
                      $json = array(
                          'status' => 'OK',
                          'type' => 'success',
                          'msg' => 'Comida encontrada.',
                          'data' => $response['result']
                      );
                      break;

                  case 'ERROR':
                      $json = array(
                          'status' => 'ERROR',
                          'type' => 'warning',
                          'msg' => 'No se encontró la comida.',
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
                  'msg' => 'No se enviaron los campos necesarios.',
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

  // -- Obtener categorías
  public function get_categories() {
      $this->functions->validate_session($this->segment->get('isActive'));

      $obj = $this->load_model('Categories');
      $response = $obj->get_categories();

      switch ($response['status']) {
          case 'OK':
              $json = array(
                  'status' => 'OK',
                  'type' => 'success',
                  'msg' => 'Listado de categorías encontrado.',
                  'data' => $response['result']
              );
              break;
          case 'ERROR':
              $json = array(
                  'status' => 'ERROR',
                  'type' => 'warning',
                  'msg' => 'No se encontraron categorías.',
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

      header('Content-Type: application/json');
      echo json_encode($json);
  }

  // -- Crear comida
  public function create_meal() {
    $this->functions->validate_session($this->segment->get('isActive'));

    $request = $_SERVER['REQUEST_METHOD'];

    if ($request === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input)) {
            $input = filter_input_array(INPUT_POST);
        }

        if (!empty($input['meal_sku']) && !empty($input['meal_name']) && !empty($input['id_category'])) {
            $meal_sku = $this->functions->clean_string($input['meal_sku']);
            $meal_name = $this->functions->clean_string($input['meal_name']);
            $meal_description = $this->functions->clean_string($input['meal_description']);
            $id_category = intval($input['id_category']);
            $meal_price = isset($input['meal_price']) && !empty($input['meal_price']) ? floatval($input['meal_price']) : 0.00;

            $bind = array(
                'meal_sku' => $meal_sku,
                'meal_name' => $meal_name,
                'meal_description' => $meal_description,
                'id_category' => $id_category,
                'meal_price' => $meal_price
            );

            $obj = $this->load_model('Meal');
            $response = $obj->create_meal($bind);

            switch ($response['status']) {
                case 'OK':
                    $json = array('status' => 'OK', 'type' => 'success', 'msg' => 'Comida creada exitosamente.', 'data' => array());
                    break;
                case 'ERROR':
                    $json = array('status' => 'ERROR', 'type' => 'warning', 'msg' => 'No se pudo crear la comida.', 'data' => array());
                    break;
                case 'EXCEPTION':
                    $json = array('status' => 'ERROR', 'type' => 'error', 'msg' => $response['result']->getMessage(), 'data' => array());
                    break;
            }
        } else {
            $json = array('status' => 'ERROR', 'type' => 'warning', 'msg' => 'Campos obligatorios faltantes.', 'data' => array());
        }
    } else {
        $json = array('status' => 'ERROR', 'type' => 'error', 'msg' => 'Método no permitido.', 'data' => array());
    }

    header('Content-Type: application/json');
    echo json_encode($json);
  }

  // -- Actualizar comida
  public function update_meal() {
      $this->functions->validate_session($this->segment->get('isActive'));

      $request = $_SERVER['REQUEST_METHOD'];

      if ($request === 'POST') {
          $input = json_decode(file_get_contents('php://input'), true);
          if (empty($input)) {
              $input = filter_input_array(INPUT_POST);
          }

          if (!empty($input['id_meal']) && !empty($input['meal_sku']) && !empty($input['meal_name']) && !empty($input['id_category'])) {
              $id_meal = intval($input['id_meal']);
              $meal_sku = $this->functions->clean_string($input['meal_sku']);
              $meal_name = $this->functions->clean_string($input['meal_name']);
              $meal_description = $this->functions->clean_string($input['meal_description']);
              $id_category = intval($input['id_category']);
              $meal_price = isset($input['meal_price']) && !empty($input['meal_price']) ? floatval($input['meal_price']) : 0.00;

              $bind = array(
                  'id_meal' => $id_meal,
                  'meal_sku' => $meal_sku,
                  'meal_name' => $meal_name,
                  'meal_description' => $meal_description,
                  'id_category' => $id_category,
                  'meal_price' => $meal_price,
              );

              $obj = $this->load_model('Meal');
              $response = $obj->update_meal($bind);

              switch ($response['status']) {
                  case 'OK':
                      $json = array('status' => 'OK', 'type' => 'success', 'msg' => 'Comida actualizada exitosamente.', 'data' => array());
                      break;
                  case 'ERROR':
                      $json = array('status' => 'ERROR', 'type' => 'warning', 'msg' => 'No se pudo actualizar la comida.', 'data' => array());
                      break;
                  case 'EXCEPTION':
                      $json = array('status' => 'ERROR', 'type' => 'error', 'msg' => $response['result']->getMessage(), 'data' => array());
                      break;
              }
          } else {
              $json = array('status' => 'ERROR', 'type' => 'warning', 'msg' => 'Campos obligatorios faltantes.', 'data' => array());
          }
      } else {
          $json = array('status' => 'ERROR', 'type' => 'error', 'msg' => 'Método no permitido.', 'data' => array());
      }

      header('Content-Type: application/json');
      echo json_encode($json);
  }

  // -- Eliminar comida
  public function delete_meal() {
    $this->functions->validate_session($this->segment->get('isActive'));
    $request = $_SERVER['REQUEST_METHOD'];

    if ($request === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input)) {
            $input = filter_input_array(INPUT_POST);
        }

        if (!empty($input['id_meal'])) {
            $bind = array(
                'id_meal' => intval($input['id_meal'])
            );

            $obj = $this->load_model('Meal');
            $response = $obj->delete_meal($bind);

            switch ($response['status']) {
                case 'OK':
                    $json = array(
                        'status' => 'OK',
                        'type' => 'success',
                        'msg' => 'Comida eliminada exitosamente.',
                        'data' => array()
                    );
                    break;
                case 'ERROR':
                    $json = array(
                        'status' => 'ERROR',
                        'type' => 'warning',
                        'msg' => 'No se pudo eliminar la comida, verificar.',
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
}
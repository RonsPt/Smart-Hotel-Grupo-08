<?php
// --
class M_Reception extends Model
{
  // --
  public function __construct()
  {
    parent::__construct();
  }


  //---------------------------------------------------
  //-----------------------------------------------------------------------


  //----------------------------------------------------
  //-------------------------------------------------------------------------



  public function get_rooms()
  {
    try {
      $sql = 'SELECT room.id_room,room.room_number, room.room_status, room_type.bed_type, room_type.type_name, room_type.person_limit, room_type.price_temporary, room_type.price_half, room_type.price_day
      FROM room 
      JOIN room_type ON room.id_type = room_type.id_type ORDER BY CAST(room.room_number AS UNSIGNED)';
      $result = $this->pdo->fetchAll($sql);

      if ($result) {
        $response = array('status' => 'OK', 'result' => $result);
      } else {
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    return $response;
  }

  public function get_room_by_status($bind)
  {
    // --
    try {
      // --
      $sql = 'SELECT room.id_room,room.room_number, room.room_status, room_type.bed_type, room_type.type_name, room_type.person_limit, room_type.price_temporary, room_type.price_half, room_type.price_day
      FROM room 
      JOIN room_type ON room.id_type = room_type.id_type WHERE room.room_status = :room_status ORDER BY CAST(room.room_number AS UNSIGNED)'; 
      // --
      $result = $this->pdo->fetchAll($sql, $bind);
      // --
      if ($result) {
        // --
        $response = array('status' => 'OK', 'result' => $result);
      } else {
        // --
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      // --
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    // --
    return $response;
  }

  public function get_room_by_id($bind)
  {
    // --
    try {
      // --
      $sql = 'SELECT room.id_room,room.room_number, room.room_status, room_type.bed_type, room_type.type_name, room_type.person_limit, room_type.price_temporary, room_type.price_half, room_type.price_day
      FROM room 
      JOIN room_type ON room.id_type = room_type.id_type WHERE room.id_room = :id_room';
      // --
      $result = $this->pdo->fetchOne($sql, $bind);
      // --
      if ($result) {
        // --
        $response = array('status' => 'OK', 'result' => $result);
      } else {
        // --
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      // --
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    // --
    return $response;
  }

  public function service_room()
  {
    try {
      $sqlFood = 'SELECT food_description FROM food WHERE food_stock > 0';
      $sqlAccessories = 'SELECT accesory_description FROM accesory WHERE accesory_stock > 0';
      $resultFood = $this->pdo->fetchAll($sqlFood);
      $resultAccessories = $this->pdo->fetchAll($sqlAccessories);
      if ($resultFood && $resultAccessories) {
        $response = array('status' => 'OK', 'result' => array('food' => $resultFood, 'accessories' => $resultAccessories));
      } else {
        $response = array('status' => 'ERROR', 'result' => array());
      }
      // return $result;
    } catch (PDOException $e) {
      return $e;
    }

    return $response;
  }

  public function create_guest_reservation($data) {
    try {
        $sql = "INSERT INTO person (id_document_type, name, document_number, address, business_name, status) 
                VALUES (:id_type, :name, :doc_num, :addr, :bus_name, 1)";
        
        $stmt = $this->pdo->prepare($sql);
        
        // --- AQUÍ ESTÁ EL TRUCO: Juntamos nombres y apellidos con un espacio ---
        $nombreCompleto = trim(($data['first_names'] ?? '') . ' ' . ($data['last_names'] ?? ''));

        $result = $stmt->execute([
            ':id_type'  => $data['document_type'],
            ':name'     => $nombreCompleto, // Ahora sí va el nombre completo
            ':doc_num'  => $data['document_number'],
            ':addr'     => $data['address'] ?? '',
            ':bus_name' => $data['company_name'] ?? ''
        ]);

        return ['status' => 'OK'];
    } catch (PDOException $e) {
        return ['status' => 'EXCEPTION', 'msg' => $e->getMessage()];
    }
}

public function get_guest($bind)
{
    try {
        // Seleccionamos desde 'person' mapeando los nombres para el JS
        $sql = 'SELECT 
                    id AS id_guest,
                    name AS first_names,
                    "" AS last_names,
                    document_number,
                    :document_type AS document_type,
                    business_name AS company_name
                FROM person
                WHERE document_number = :document_number';
        $result = $this->pdo->fetchAll($sql, $bind);

        if ($result) {
            $response = array('status' => 'OK', 'result' => $result);
        } else {
            $response = array('status' => 'ERROR', 'result' => array());
        }
    } catch (PDOException $e) {
        $response = array('status' => 'EXCEPTION', 'result' => $e->getMessage());
    }
    return $response;
}

public function create_payment($bind)
{
    try {
        $sql = 'INSERT INTO payment (payment_room) VALUES (:payment_room)';
        $result = $this->pdo->perform($sql, $bind);
        if ($result) {
            $response = array('status' => 'OK', 'result' => array());
        } else {
            $response = array('status' => 'ERROR', 'result' => array());
        }
    } catch (PDOException $e) {
        $response = array('status' => 'EXCEPTION', 'result' => $e->getMessage());
    }
    return $response;
}

private function resolve_guest_id(&$bind)
{
    $requestInput = filter_input_array(INPUT_POST) ?? [];
    if (empty($requestInput)) {
        $requestInput = json_decode(file_get_contents('php://input'), true) ?? [];
    }

    $document_number = trim($bind['document_number'] ?? ($requestInput['document_number'] ?? ''));

    if ($document_number === '') {
        if (!empty($bind['id_guest']) && ctype_digit((string) $bind['id_guest'])) {
            return (int) $bind['id_guest'];
        }
        throw new Exception('No se recibió document_number ni un id_guest válido.');
    }

    // 1. Buscamos si la persona ya existe en la tabla 'person'
    $guest = $this->pdo->fetchOne(
        'SELECT id FROM person WHERE document_number = :document_number LIMIT 1',
        ['document_number' => $document_number]
    );

    if (!empty($guest['id'])) {
        return (int) $guest['id'];
    }

    // 2. Si no existe, preparamos los datos para insertarla
    $document_type = !empty($bind['document_type']) ? $bind['document_type'] : ($requestInput['document_type'] ?? 'DNI');
    $first_names = trim($bind['first_names'] ?? ($requestInput['first_names'] ?? 'CLIENTE'));
    $address = trim($bind['address'] ?? ($requestInput['address'] ?? ''));
    $company_name = trim($bind['company_name'] ?? ($requestInput['company_name'] ?? ''));

    $guestBind = [
        'document_type'   => $document_type,
        'document_number' => $document_number,
        'first_names'     => $first_names,
        'address'         => $address,
        'company_name'    => $company_name
    ];

    // 3. Insertamos ÚNICAMENTE en 'person'
    $sqlGuest = 'INSERT INTO person (document_type, document_number, name, address, business_name)
                 VALUES (:document_type, :document_number, :first_names, :address, :company_name)';

    $resultGuest = $this->pdo->perform($sqlGuest, $guestBind);

    if (!$resultGuest) {
        throw new Exception('No fue posible crear la persona en la tabla person.');
    }

    return (int) $this->pdo->lastInsertId();
}

  public function create_reservation($bind)
  {
    try {
      //Lamadada al metodo resolve_guest_id()
      $bind['id_guest'] = $this->resolve_guest_id($bind);
      // --
      $sql = 'INSERT INTO reservation (checkin_date, checkin_time, checkout_date, checkout_time, id_room, id_guest,status)  VALUES (:checkin_date, :checkin_time, :checkout_date, :checkout_time, :id_room, :id_guest,:status);

      INSERT INTO payment (id_reservation, payment_room, pre_payment)  
      VALUES (LAST_INSERT_ID(),:payment_room, :pre_payment)';
      // --
      $result = $this->pdo->perform($sql, $bind);
      // --
      if ($result) {
        // --
        $response = array('status' => 'OK', 'result' => array());
      } else {
        // --
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      // --
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    // --
    return $response;
  }

  public function create_reservation_free($bind)
  {
    try {
      //Lamadada al metodo resolve_guest_id()
      $bind['id_guest'] = $this->resolve_guest_id($bind);


      // --
      $sql = 'INSERT INTO reservation (checkin_date, checkin_time, id_room, id_guest,status)  VALUES (:checkin_date, :checkin_time, :id_room, :id_guest,:status);

      INSERT INTO payment (id_reservation) VALUES (LAST_INSERT_ID())';
      // --
      $result = $this->pdo->perform($sql, $bind);
      // --
      if ($result) {
        // --
        $response = array('status' => 'OK', 'result' => array());
      } else {
        // --
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      // --
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    // --
    return $response;
  }


  public function update_state_reservation($bind)
  {
    // --
    try {
      // --
      $sql = 'UPDATE room SET room_status=:room_status WHERE id_room=:id_room';
      // --
      $result = $this->pdo->perform($sql, $bind);

      // --
      if ($result) {
        // --
        $response = array('status' => 'OK', 'result' => array());
      } else {
        // --
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      // --
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }

    return $response;
  }




  public function get_rooms_price($bind)
  {
    try {
      $sql = 'SELECT id_type,type_name,person_limit,price_temporary, price_half, price_day FROM room_type WHERE type_name = :type_name';
      $result = $this->pdo->fetchAll($sql, $bind);

      if ($result) {
        $response = array('status' => 'OK', 'result' => $result);
      } else {
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    return $response;
  }

  /*
  public function update_state_timer($bind)
  {
    try {
      $sql = '
      DELETE FROM payment WHERE id_reservation=:id_reservation;
      DELETE FROM reservation WHERE id_reservation=:id_reservation;
      UPDATE room SET room_status=:room_status WHERE id_room=:id_room;';
      $result = $this->pdo->fetchAll($sql, $bind);

      if ($result) {
        $response = array('status' => 'OK', 'result' => $result);
      } else {
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    return $response;
  }
*/

//----------------------------------------------
//----------------------------------------------------------------------------
public function update_state($bind)
{
    try {
        $this->pdo->beginTransaction();

        error_log('ID de habitación: ' . $bind['id_room']);
        error_log('Estado de habitación: ' . $bind['room_status']);
        $this->pdo->prepare('UPDATE room SET room_status=:room_status WHERE id_room=:id_room')->execute([':room_status' => $bind['room_status'], ':id_room' => $bind['id_room']]);

        $this->pdo->commit();

        $response = array('status' => 'ok', 'result' => []);
    } catch (PDOException $e) {
        $this->pdo->rollBack();
        $response = array('status' => 'EXCEPTION', 'result' => $e->getMessage());
    }
    return $response;
}

//--------------------------------------------------
//----------------------------------------------------------------------------

  public function get_reservation_room($bind)
  {
    try {
      $sql = '
      SELECT
      r.id_reservation,
      r.id_room,
      r.id_guest,
      r.status,
      g.first_names,
      g.last_names,
      g.company_name
    FROM
      reservation r
    JOIN
      person g ON g.id_guest = r.id_guest
    WHERE
      r.id_room = :id_room
    AND
      r.status = "Pendiente"
  ';
      $result = $this->pdo->fetchAll($sql, $bind);

      if ($result) {
        $response = array('status' => 'OK', 'result' => $result);
      } else {
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    return $response;
  }


  public function clean_rooms($bind)
  {
    // --
    try {
      // --
      $sql = 'UPDATE room SET room_status="Disponible" WHERE id_room=:id_room';
      // --
      $result = $this->pdo->fetchAll($sql, $bind);

      // --
      if ($result) {
        // --
        $response = array('status' => 'OK', 'result' => array());
      } else {
        // --
        $response = array('status' => 'ERROR', 'result' => array());
      }
    } catch (PDOException $e) {
      // --
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }

    return $response;
  }

  public function date_reservation($bind)
  {
    // --
    try {
      // --
      $sql = 'SELECT 
                  r.id_reservation,
                  r.checkin_date,
                  r.checkin_time,
                  r.checkout_date,
                  r.checkout_time,
                  r.status,
                  r.id_room,
                  ro.room_number,
                  ro.room_status
                FROM reservation r
                JOIN room ro ON ro.id_room = r.id_room
                WHERE r.status IN ("Reservado", "Ocupado","Pendiente") AND r.id_room = :id_room
                AND (CONCAT(r.checkin_date, " ", r.checkin_time) BETWEEN CONCAT(:checkin_date) AND CONCAT(:checkout_date)
                OR CONCAT(r.checkout_date, " ", r.checkout_time) BETWEEN CONCAT(:checkin_date) AND CONCAT(:checkout_date));
                ';
      // --
      $result = $this->pdo->fetchAll($sql, $bind);
      // --
      if (count($result) == 0) {
        // --
        $response = array('status' => 'OK', 'result' => $result);
      } else {
        // --
        $response = array('status' => 'ERROR', 'result' => $result);
      }
    } catch (PDOException $e) {
      // --
      $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    // --
    return $response;
  }
}

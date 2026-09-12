<?php
// --
class M_Meal extends Model
{
    // --
    public function __construct()
    {
        parent::__construct();
    }

    // -- Obtener todas las comidas
    public function get_meal()
    {
        try {
            // --
            $sql = 'SELECT   
                        meal.id_meal,
                        meal.meal_sku,
                        meal.meal_name,
                        meal.meal_description,
                        categories.description,
                        meal.meal_price
                  FROM meal
                  JOIN categories ON meal.id_category=categories.id';
            // --
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

    // -- Obtener comida por ID
    public function get_meal_by_id($bind) {
        try {
            $sql = 'SELECT 
                        meal.id_meal,
                        meal.meal_sku,
                        meal.meal_name,
                        meal.meal_description,
                        meal.id_category,
                        meal.meal_price
                    FROM meal
                    WHERE meal.id_meal = :id_meal';
    
            $result = $this->pdo->fetchOne($sql, $bind);
            
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
    

    // -- Crear Comida
    public function create_meal($bind)
    {
    try {
        $sql = 'INSERT INTO meal 
                (meal_sku, meal_name, meal_description, id_category, meal_price) 
                VALUES 
                (:meal_sku, :meal_name, :meal_description, :id_category, :meal_price)';
        
        $result = $this->pdo->perform($sql, $bind);
        
        if ($result) {
            $response = array('status' => 'OK', 'result' => array());
        } else {
            $response = array('status' => 'ERROR', 'result' => array());
        }
    } catch (PDOException $e) {
        $response = array('status' => 'EXCEPTION', 'result' => $e);
    }
    return $response;
    }

    public function update_meal($bind) {
        try {
            $sql = 'UPDATE meal
                    SET 
                        meal_sku = :meal_sku,
                        meal_name = :meal_name,
                        meal_description = :meal_description,
                        id_category = :id_category,
                        meal_price = :meal_price
                    WHERE id_meal = :id_meal';
    
            // Ejecutar la consulta de actualización
            $result = $this->pdo->perform($sql, $bind);
    
            if ($result) {
                $response = array('status' => 'OK', 'result' => array());
            } else {
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
    
        return $response;
    }
    

    // -- Eliminar un comida
    public function delete_meal($bind)
    {
        try {
            $sql = 'DELETE FROM meal WHERE id_meal = :id_meal';
            // --
            $result = $this->pdo->perform($sql, $bind);
            if ($result) {
                $response = array('status' => 'OK', 'result' => array());
            } else {
                $response = array('status' => 'ERROR', 'result' => array());
            }
        } catch (PDOException $e) {
            $response = array('status' => 'EXCEPTION', 'result' => $e);
        }
        return $response;
    }
}
<?php
// -- Zona Horaria
date_default_timezone_set('America/Lima');
// --
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    $protocol = "https://";
} else {
    $protocol = "http://";
}
// --
$base_url =  $protocol . $_SERVER['HTTP_HOST'] .  '/sistema_hotelero/'; //<--Direccion HTTP
// --
define('BASE_URL', $base_url);
define('DEFAULT_CONTROLLER', 'Login');
define('DEFAULT_LAYOUT', 'layout');
// --
define('DB_HOST', 'solucionesintegralesjb.com');
define('DB_NAME', 'soluciones_hotelero_demo');
define('DB_USER', 'soluciones_hotelero');
define('DB_PASS', 'S0Luc10nes*$%&*-');
define('DB_PORT', 3306);
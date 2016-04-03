<?php
/* For use in development mode... */
error_reporting(E_ALL);

define('BASE_PATH', dirname(realpath(__FILE__)) . '/');
define('APP_PATH', BASE_PATH . 'app/');
define('CONTROLLER_PATH', APP_PATH . 'controllers/');
define('VIEW_PATH', APP_PATH . 'views/');

include_once BASE_PATH . '/vendor/autoload.php';

/** GloBal Configuration array **/
$GLOBALS['config'] = array(
    'mysql' => array(
        'driver' => 'MYSQL',
        'tables' => array(),
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'db' => 'test'
    )
);
/** Call BootStrap for routing **/
$bootstrap = new \Ecne\Core\BootStrap();
$bootstrap->parse($_SERVER['REQUEST_URI'], 'GET');

<?php
# For use in development mode
error_reporting(E_ALL);

# global constant definitions
define('BASE_PATH', dirname(realpath(__FILE__)) . '/');
define('APP_PATH', BASE_PATH . 'app/');
define('CONTROLLER_PATH', APP_PATH . 'controllers/');
define('VIEW_PATH', APP_PATH . 'views/');

include_once BASE_PATH . '/vendor/autoload.php';

/**
 *  @note configuration array
 *  @todo remove from index.php, and move to conf/
 */
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

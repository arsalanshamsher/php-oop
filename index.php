<?php
use App\Services\Session;

define('APP_ROOT',__DIR__);
require_once APP_ROOT . '/vendor/autoload.php';
// ✅ Helper file include karein
require_once APP_ROOT . '/app/Helpers.php';
load_env(__DIR__ . '/.env');
Session::start();
Session::flashOldInput();

// aotuloader gor namespaced classes
spl_autoload_register(function($class) {
    $classFile = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $classPath = APP_ROOT . '/app/' . $classFile;

    if (file_exists($classPath)) {
        require_once $classPath;
    }
});



// ✅ Flash session automatically clear karega next request pe
register_shutdown_function(function () {
    file_put_contents('shutdown_debug.txt', "Shutdown function executed at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
    Session::clearFlash();
});


use App\Services\Route;
use App\Services\ErrorHandler;

// Set up global exception handler
set_exception_handler([ErrorHandler::class, 'handle']);

$route = new Route();
require_once(APP_ROOT.'/routes/web.php');
require_once(APP_ROOT.'/routes/api.php');
$route->handle();


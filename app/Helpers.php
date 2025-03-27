<?php

// redirect funtion

use App\Services\Route;
use App\Services\Session;

/**
 * Debugging function similar to Laravel's dd().
 * This function prints the given variables in a readable format and stops execution.
 *
 * @param mixed ...$vars - Multiple variables to dump.
 */
function dd(...$vars)
{
    echo "<pre>"; // Preformatted text for better readability
    foreach ($vars as $var) {
        print_r($var); // Print each variable
    }
    echo "</pre>";
    die; // Stop script execution after dumping
}
function redirect()
{
    return new class {
        public function route($name, $params = [])
        {
            $url = route($name, $params);
            header('Location: ' . $url);
            exit;
        }
    };
}

// end of redirect function

// view directory function
function view($dir, $data = [], $defaultData = [])
{
    $data = array_merge($data, $defaultData);
    extract($data);
    $dir = str_replace('\\', DIRECTORY_SEPARATOR, $dir);
    $dir = str_replace('.', DIRECTORY_SEPARATOR, $dir);
    $file = APP_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $dir . '.php';
    if (file_exists($file)) {
        return require $file;
    }
    throw new Exception('View not found. ' . $file);
}
// end of view directory function

function asset($dir)
{
    // Replace backslashes with forward slashes for web compatibility
    $dir = str_replace('\\', '/', $dir);

    // Build the file path
    $file = APP_ROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir);

    // Check if the file exists
    if (file_exists($file)) {
        // Return the correct relative path as a URL
        return '/public/' . $dir;
    }

    // Throw an exception if the file is not found
    throw new Exception('Asset not found: ' . $file);
}


// include function
function include_file($file)
{
    include(APP_ROOT . '/views/' . $file);
}

// route function
function route($name, $params = [])
{
    static $route;
    // Initialize the Route instance once
    if (!$route) {
        $route = new Route();
        // Register routes here (or load them from a configuration file)

    }

    // Get the URL of the route
    return $route->getRoute($name, $params);
}

function old($field, $default = '')
{
    $old = Session::get('old', []);
    return isset($old[$field]) ? $old[$field] : $default;
}

function has_error($field)
{
    $errors = Session::get('errors', []);
    return isset($errors[$field]);
}

function error_message($field)
{
    $errors = Session::get('errors', []);
    return has_error($field) ? $errors[$field][0] : '';
}



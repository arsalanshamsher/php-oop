<?php

use App\Services\Route;
use App\Services\Session;

/**
 * Debugging function similar to Laravel's dd().
 */
function dd(...$vars)
{
    echo "<pre>";
    foreach ($vars as $var) {
        print_r($var);
    }
    echo "</pre>";
    die;
}

/**
 * Redirect helper function.
 */
function redirect()
{
    return new class {
        public function route($name, $params = [])
        {
            $url = route($name, $params);
            header('Location: ' . $url);
            exit;
        }

        public function back()
        {
            $previousUrl = $_SERVER['HTTP_REFERER'] ?? '/';
            header("Location: $previousUrl");
            exit;
        }
    };
}

/**
 * View function to include Blade-style views.
 */
function view($dir, $data = [], $defaultData = [])
{
    $data = array_merge($data, $defaultData);
    extract($data);
    $dir = str_replace(['\\', '.'], DIRECTORY_SEPARATOR, $dir);
    $file = APP_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $dir . '.php';
    
    if (file_exists($file)) {
        return require $file;
    }
    
    throw new Exception('View not found: ' . $file);
}

/**
 * Generate asset URLs.
 */
function asset($dir)
{
    $dir = str_replace('\\', '/', $dir);
    $file = APP_ROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $dir);

    if (file_exists($file)) {
        return '/public/' . ltrim($dir, '/');
    }

    throw new Exception('Asset not found: ' . $file);
}

/**
 * Include file from views directory.
 */
function include_file($file)
{
    include(APP_ROOT . '/views/' . $file);
}

/**
 * Get the named route URL.
 */
function route($name, $params = [])
{
    return Route::getRoute($name, $params) ?? '/';
}

/**
 * Get old form input value.
 */
function old($field, $default = '')
{
    return Session::get('old', [])[$field] ?? $default;
}

/**
 * Check if an error exists for a field.
 */
function has_error($field)
{
    return isset(Session::get('errors', [])[$field]);
}

/**
 * Get the first error message for a field.
 */
function error_message($field)
{
    return has_error($field) ? Session::get('errors', [])[$field][0] : '';
}

/**
 * Get the public path.
 */
function public_path($path = '')
{
    return rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/public' . ($path ? '/' . ltrim($path, '/') : '');
}

/**
 * Load environment variables from .env file
 */
function load_env($file)
{
    if (!file_exists($file)) {
        throw new Exception(".env file not found at: " . $file);
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value, "\"'"); // Remove extra quotes
            putenv("$key=$value"); // ✅ Store in system environment
        }
    }
}


/**
 * Get environment variable value
 */
function env($key, $default = null)
{
    // dd($default);
    return $_ENV[$key] ?? getenv($key) ?? $default;
}
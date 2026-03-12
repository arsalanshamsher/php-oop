<?php

use App\Core\Routing\Route;
use App\Core\Session\Session;
use App\Core\View\View;
use App\Core\Exceptions\RouteNotFoundException;

/**
 * Debugging function similar to Laravel's dd().
 */
if (!function_exists('dd')) {
    function dd(...$vars)
    {
        echo "<pre>";
        foreach ($vars as $var) {
            print_r($var);
        }
        echo "</pre>";
        die;
    }
}

/**
 * Redirect helper function.
 */
if (!function_exists('redirect')) {
    function redirect($url = null)
    {
        if ($url) {
            header('Location: ' . $url);
            exit;
        }

        return new class {
            protected $url;

            public function route($name, $params = [])
            {
                $this->url = route($name, $params);
                return $this;
            }

            public function back()
            {
                $this->url = $_SERVER['HTTP_REFERER'] ?? '/';
                return $this;
            }

            public function with($key, $value)
            {
                Session::flash($key, $value);
                return $this;
            }

            public function to($url)
            {
                $this->url = $url;
                return $this;
            }

            public function __destruct()
            {
                if ($this->url) {
                    header('Location: ' . $this->url);
                    exit;
                }
            }
        };
    }
}

/**
 * View function to include Blade-style views.
 */
if (!function_exists('view')) {
    function view($dir, $data = [], $defaultData = [])
    {
        $view = new View();
        $dir = str_replace(['\\', '.'], DIRECTORY_SEPARATOR, $dir);
        $file = APP_ROOT . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $dir . '.php';

        if (!file_exists($file)) {
            throw new Exception('View not found: ' . $file);
        }

        echo $view->render($file, array_merge($data, $defaultData));
    }
}

/**
 * Generate asset URLs.
 */
if (!function_exists('asset')) {
    function asset($path)
    {
        // Replace backslashes with forward slashes
        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        // Build the full file path
        $file = APP_ROOT . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $path);

        // Get base URL from environment or detect automatically
        $baseUrl = env('APP_URL', '/');
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $baseDir = dirname($scriptName);

        if ($baseUrl === '/') {
            $baseUrl = $baseDir === '/' || $baseDir === '\\' ? '/' : $baseDir . '/';
        }

        $url = rtrim($baseUrl, '/') . '/public/' . $path;

        if (file_exists($file) && env('APP_ENV') === 'development') {
            $url .= '?v=' . filemtime($file);
        }

        return $url;
    }
}

/**
 * Include file from views directory.
 */
if (!function_exists('include_file')) {
    function include_file($file)
    {
        include(APP_ROOT . '/views/' . $file);
    }
}

/**
 * Get the session service instance.
 */
if (!function_exists('session')) {
    function session()
    {
        return new Session();
    }
}

/**
 * Get the named route URL.
 */
if (!function_exists('route')) {
    function route($name, $params = [], $default = null)
    {
        try {
            return Route::route($name, $params);
        } catch (RouteNotFoundException $e) {
            if ($default !== null) {
                return $default;
            }
            throw $e;
        }
    }
}

/**
 * Get old form input value.
 */
if (!function_exists('old')) {
    function old($field, $default = '')
    {
        return Session::get('old', [])[$field] ?? $default;
    }
}

/**
 * Check if an error exists for a field.
 */
if (!function_exists('has_error')) {
    function has_error($field)
    {
        return isset(Session::get('errors', [])[$field]);
    }
}

/**
 * Get the first error message for a field.
 */
if (!function_exists('error_message')) {
    function error_message($field)
    {
        return has_error($field) ? Session::get('errors', [])[$field][0] : '';
    }
}

/**
 * Get the public path.
 */
if (!function_exists('public_path')) {
    function public_path($path = '')
    {
        return rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/public' . ($path ? '/' . ltrim($path, '/') : '');
    }
}

/**
 * Load environment variables from .env file
 */
if (!function_exists('load_env')) {
    function load_env($file)
    {
        if (!file_exists($file)) {
            return;
        }
        
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) continue;
            
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = trim($value, "\"'");
                
                putenv("$key=$value");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }
    }
}

/**
 * Get environment variable value
 */
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        return $value;
    }
}

/**
 * Generate a hidden input for method spoofing.
 */
if (!function_exists('method_field')) {
    function method_field($method)
    {
        return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
    }
}

/**
 * Generate a CSRF token field (placeholder for now).
 */
if (!function_exists('csrf_field')) {
    function csrf_field()
    {
        return '<input type="hidden" name="_token" value="dummy_token">';
    }
}

/**
 * Return an API response object.
 */
if (!function_exists('response')) {
    function response()
    {
        return new class {
            public function json($data, $statusCode = 200)
            {
                return \App\Core\Http\ApiResponse::json($data, $statusCode);
            }
        };
    }
}

/**
 * Log helper function.
 */
if (!function_exists('logger')) {
    function logger()
    {
        return new \App\Core\Log\Logger();
    }
}

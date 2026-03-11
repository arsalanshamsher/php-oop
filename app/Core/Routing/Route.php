<?php

namespace App\Core\Routing;

use App\Core\Http\Request;

class Route
{
   private static $routes = [];
   private static $nameRoutes = [];
   private static $controllerNamespace = 'App\\Controllers\\';
   // Stack to hold nested group options (e.g., middleware) so routes defined
   // inside a group inherit those options.
   private static $groupStack = [];

   // ✅ Route Register
   public static function register($name, $url)
   {
      self::$nameRoutes[$name] = $url;
   }

   // ✅ Named Route URL Fetch
   public static function getRoute($name, $params = [])
   {
      if (!isset(self::$nameRoutes[$name])) {
         return null; // ✅ null return karega taaki `route()` function handle kare
      }

      $route = self::$nameRoutes[$name];
      foreach ($params as $key => $value) {
         $route = str_replace('{' . $key . '}', $value, $route);
      }
      return $route;
   }



   // ✅ Route Add Logic
   public static function add($uri, $controller, $action = null, $method = 'GET', $middleware = [])
   {
      // Merge middleware from any active groups (groupStack can contain middleware or other options)
      $groupMiddleware = [];
      foreach (self::$groupStack as $group) {
         if (isset($group['middleware'])) {
            if (is_array($group['middleware'])) {
               $groupMiddleware = array_merge($groupMiddleware, $group['middleware']);
            } elseif ($group['middleware']) {
               $groupMiddleware[] = $group['middleware'];
            }
         }
      }

      // Normalize middleware param
      $middlewareList = [];
      if (is_array($middleware)) {
         $middlewareList = $middleware;
      } elseif ($middleware) {
         $middlewareList[] = $middleware;
      }

      // Final middleware is group middleware first, then route-specific; remove duplicates
      $finalMiddleware = array_values(array_unique(array_merge($groupMiddleware, $middlewareList)));

      if ($controller instanceof \Closure) {
         // ✅ Agar Closure hai to action null hoga
         self::$routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => null,
            'middleware' => $finalMiddleware,
         ];
      } elseif (is_array($controller)) {
         // ✅ Agar array diya gaya hai ['Controller', 'method'] format me
         self::$routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller[0],
            'action'     => $controller[1],
            'middleware' => $finalMiddleware,
         ];
      } else {
         // ✅ Normal case
         self::$routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => $action,
            'middleware' => $finalMiddleware,
         ];
      }
   }

   // ✅ Group support: call with options and a closure. Routes declared inside the closure
   // will inherit group's options (e.g., middleware). Supports nested groups.
   public static function group($options, $closure)
   {
      if (!is_array($options)) $options = [];
      array_push(self::$groupStack, $options);
      // Execute the closure so child routes get registered with group context
      $closure();
      array_pop(self::$groupStack);
      return new self();
   }

   // ✅ GET Method
   public static function get($uri, $controller, $action = null, $middleware = [])
   {
      self::add($uri, $controller, $action, 'GET', $middleware);
      return new self();
   }

   // ✅ POST Method
   public static function post($uri, $controller, $action = null, $middleware = [])
   {
      self::add($uri, $controller, $action, 'POST', $middleware);
      return new self();
   }

   // ✅ PUT Method
   public static function put($uri, $controller, $action = null, $middleware = [])
   {
      self::add($uri, $controller, $action, 'PUT', $middleware);
      return new self();
   }

   // ✅ DELETE Method
   public static function delete($uri, $controller, $action = null, $middleware = [])
   {
      self::add($uri, $controller, $action, 'DELETE', $middleware);
      return new self();
   }

   // ✅ PATCH Method
   public static function patch($uri, $controller, $action = null, $middleware = [])
   {
      self::add($uri, $controller, $action, 'PATCH', $middleware);
      return new self();
   }

   // ✅ Route Handler
   // ✅ Route Handler
   public static function handle()
   {
      $requestUri = strtok($_SERVER['REQUEST_URI'], '?'); // Query params hatao
      $requestMethod = $_SERVER['REQUEST_METHOD'];

      // Debug trace
      if (isset($_GET['debug_route'])) {
         echo "<!-- URI=$requestUri Method=$requestMethod Routes=" . count(self::$routes) . " -->\n";
      }



      // ✅ Method Spoofing Support (Laravel-style)
      if ($requestMethod === 'POST' && isset($_POST['_method'])) {
         $method = strtoupper($_POST['_method']);
         if (in_array($method, ['PUT', 'PATCH', 'DELETE'])) {
            $requestMethod = $method;
         }
      }

      foreach (self::$routes as $route) {
         // ✅ Convert `{id}` type placeholders into regex
         $pattern = preg_replace('/{([^}]+)}/', '([^/]+)', $route['uri']);
         $pattern_escaped = str_replace('/', '\\/', $pattern);
         if ($route['method'] === $requestMethod && preg_match("/^$pattern_escaped$/", $requestUri, $matches)) {

            array_shift($matches); // Full match ko hatao

            $request = new Request(); // ✅ Request object banao
            $params = [...$matches]; // ✅ Sirf route parameters rakhna hai

            // Build the final route handler (either a closure or controller method)
            if ($route['controller'] instanceof \Closure) {
                $routeHandler = function($request) use ($route, $params) {
                    // For closures, put request as first argument and then route params
                    array_unshift($params, $request);
                    return call_user_func_array($route['controller'], $params);
                };
            } else {
                // Controller@action handler
                $controllerClass = $route['controller'];
                if (strpos($controllerClass, '\\') === false && !class_exists($controllerClass)) {
                    $controllerClass = self::$controllerNamespace . $controllerClass;
                }

                $action = $route['action'];

                if (!class_exists($controllerClass)) {
                    http_response_code(500);
                    echo "Controller class <b>{$controllerClass}</b> not found.";
                    return;
                }

                if (!method_exists($controllerClass, $action)) {
                    http_response_code(500);
                    echo "Method <b>{$action}</b> not found in <b>{$controllerClass}</b>";
                    return;
                }

                $routeHandler = function($request) use ($controllerClass, $action, $params) {
                    $controller = new $controllerClass();
                    $reflection = new \ReflectionMethod($controller, $action);
                    $parameters = $reflection->getParameters();

                    $callParams = $params;
                    if (isset($parameters[0]) && $parameters[0]->getType() && $parameters[0]->getType()->getName() === Request::class) {
                        array_unshift($callParams, $request);
                    }

                    return call_user_func_array([$controller, $action], $callParams);
                };
            }

            // Resolve middleware list for this route
            $middlewareList = $route['middleware'] ?? [];
            if (!is_array($middlewareList)) $middlewareList = [$middlewareList];

            // Helper to resolve middleware string to a class name
            $resolveMiddlewareClass = function($name) {
                if (is_string($name) && class_exists($name)) return $name;
                // Try common patterns: App\Middlewhere\{CamelName}Middleware
                $camel = str_replace(' ', '', ucwords(str_replace(['-', '_', '.'], ' ', $name)));
                $candidates = [
                    'App\\Middleware\\' . $camel . 'Middleware',
                    'App\\Middleware\\' . $camel,
                    'App\\Middleware\\' . ucfirst($name) . 'Middleware',
                ];
                foreach ($candidates as $cand) {
                    if (class_exists($cand)) return $cand;
                }
                return null;
            };

            // Build middleware pipeline: wrap handler with middleware in reverse order
            $next = $routeHandler;
            foreach (array_reverse($middlewareList) as $mw) {
                if (is_callable($mw)) {
                    $next = function($request) use ($mw, $next) {
                        return $mw($request, $next);
                    };
                } elseif (is_string($mw)) {
                    $mwClass = $resolveMiddlewareClass($mw);
                    if ($mwClass && class_exists($mwClass)) {
                        $instance = new $mwClass();
                        $next = function($request) use ($instance, $next) {
                            return $instance->handle($request, $next);
                        };
                    } else {
                        // Unknown middleware string: ignore or throw; let's throw to surface the issue
                        http_response_code(500);
                        echo "Middleware <b>{$mw}</b> not found.";
                        return;
                    }
                } elseif (is_object($mw)) {
                    // If instance provided
                    $instance = $mw;
                    $next = function($request) use ($instance, $next) {
                        return $instance->handle($request, $next);
                    };
                }
            }

            // Execute the pipeline
            $response = $next($request);

            // If middleware/handler returned something, echo it if it's a string
            if (is_string($response)) {
                echo $response;
            }

            return;
         }
      }


      http_response_code(404);
      echo "404 Page Not Found.<br>";
      echo "<!-- Debug Trace:\n";
      echo "URI: " . $requestUri . "\n";
      echo "Method: " . $requestMethod . "\n";
      echo "Registered Routes: " . count(self::$routes) . "\n";
      echo "-->";
   }



   // ✅ Name Route
   public static function name($name)
   {
      $lastRoute = end(self::$routes);
      if ($lastRoute) {
         self::$nameRoutes[$name] = $lastRoute['uri'];
      }
      return new self();
   }

   // ✅ Generate Named Route URL
   public static function route($name, $params = [])
   {
      $route = self::getRoute($name, $params);

      if ($route === null) {
         throw new \App\Core\Exceptions\RouteNotFoundException($name); // ✅ Laravel-style exception
      }

      return $route;
   }



   private static function renderErrorPage($message)
   {
      http_response_code(500); // Internal Server Error
      echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Route Error</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f8d7da; text-align: center; padding: 50px; }
            .error-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); display: inline-block; }
            h1 { color: #721c24; }
            p { color: #721c24; font-size: 18px; }
        </style>
    </head>
    <body>
        <div class='error-box'>
            <h1>🚨 Error</h1>
            <p>{$message}</p>
        </div>
    </body>
    </html>";
      exit;
   }
}

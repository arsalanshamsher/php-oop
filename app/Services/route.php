<?php

namespace App\Services;

class Route
{
   private static $routes = [];
   private static $nameRoutes = [];
   private static $controllerNamespace = 'App\\Controllers\\';

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
      if ($controller instanceof \Closure) {
         // ✅ Agar Closure hai to action null hoga
         self::$routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => null,
            'middleware' => $middleware,
         ];
      } elseif (is_array($controller)) {
         // ✅ Agar array diya gaya hai ['Controller', 'method'] format me
         self::$routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller[0],
            'action'     => $controller[1],
            'middleware' => $middleware,
         ];
      } else {
         // ✅ Normal case
         self::$routes[] = [
            'method'     => $method,
            'uri'        => $uri,
            'controller' => $controller,
            'action'     => $action,
            'middleware' => $middleware,
         ];
      }
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

   // ✅ Route Handler
   // ✅ Route Handler
   public static function handle()
   {
      $requestUri = strtok($_SERVER['REQUEST_URI'], '?'); // Query params hatao
      $requestMethod = $_SERVER['REQUEST_METHOD'];

      foreach (self::$routes as $route) {
         // ✅ Convert `{id}` type placeholders into regex
         $pattern = preg_replace('/{([^}]+)}/', '([^/]+)', $route['uri']);
         $pattern = str_replace('/', '\\/', $pattern);

         if ($route['method'] === $requestMethod && preg_match("/^$pattern$/", $requestUri, $matches)) {
            array_shift($matches); // Full match ko hatao

            $request = new \App\Services\Request(); // ✅ Request object banao
            $params = [...$matches]; // ✅ Sirf route parameters rakhna hai

            // ✅ Agar route Closure hai, to usko directly call karo
            if ($route['controller'] instanceof \Closure) {
               array_unshift($params, $request); // ✅ Request ko first argument banao
               call_user_func_array($route['controller'], $params);
               return;
            }

            $controllerClass = self::$controllerNamespace . $route['controller'];
            $action = $route['action'];

            if (class_exists($controllerClass)) {
               $controller = new $controllerClass();

               if (!method_exists($controller, $action)) {
                  http_response_code(500);
                  echo "Method <b>{$action}</b> not found in <b>{$controllerClass}</b>";
                  return;
               }

               $reflection = new \ReflectionMethod($controller, $action);
               $parameters = $reflection->getParameters();

               // ✅ Agar first parameter `Request` hai to pehle pass karo
               if (isset($parameters[0]) && $parameters[0]->getType() && $parameters[0]->getType()->getName() === \App\Services\Request::class) {
                  array_unshift($params, $request);
               }

               call_user_func_array([$controller, $action], $params);
               return;
            }
         }
      }

      http_response_code(404);
      echo '404 Page Not Found.';
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
         throw new \App\Exceptions\RouteNotFoundException($name); // ✅ Laravel-style exception
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

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
      if (isset(self::$nameRoutes[$name])) {
         $route = self::$nameRoutes[$name];
         foreach ($params as $key => $value) {
            $route = str_replace('{' . $key . '}', $value, $route);
         }
         return $route;
      }
      return null;
   }

   // ✅ Route Add Logic
   public static function add($uri, $controller, $action = null, $method = 'GET', $middleware = [])
   {
      // Agar `$controller` ek array hai to usko `controller` aur `action` me split karenge
      if (is_array($controller)) {
         [$controller, $action] = $controller;
      }

      self::$routes[] = [
         'method'     => $method,
         'uri'        => $uri,
         'controller' => $controller,
         'action'     => $action,
         'middleware' => $middleware,
      ];
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
   public static function handle()
{
    $requestUri = strtok($_SERVER['REQUEST_URI'], '?'); // Query params hatao
    $requestMethod = $_SERVER['REQUEST_METHOD'];

    foreach (self::$routes as $route) {
        // Route pattern ko regex me convert karo
        $pattern = preg_replace('/{([^}]+)}/', '([^/]+)', $route['uri']);
        $pattern = str_replace('/', '\\/', $pattern);

        if ($route['method'] === $requestMethod && preg_match("/^$pattern$/", $requestUri, $matches)) {
            array_shift($matches); // Full match ko hatao

            $request = new \App\Services\Request(); // ✅ Request object banao
            $params = [...$matches]; // ✅ Sirf route parameters rakhna hai

            // Agar controller ka function `Request` expect karta hai, to usko pehle pass karo
            $controllerClass = self::$controllerNamespace . $route['controller'];
            $action = $route['action'];

            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                $reflection = new \ReflectionMethod($controller, $action);
                $parameters = $reflection->getParameters();

                // Check karo agar first parameter `Request` hai
                if (isset($parameters[0]) && $parameters[0]->getType() && $parameters[0]->getType()->getName() === \App\Services\Request::class) {
                    array_unshift($params, $request); // ✅ Request ko first parameter bana do
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
      return self::getRoute($name, $params);
   }
}

<?php

namespace App\Services;

class Route
{
   private static $routes = [];
   private static $nameRoutes = [];
   private static $controllerNamespace = 'App\\Controllers\\';

   /**
    * Register a route with a name and a corresponding URL pattern.
    */
   public static function register($name, $url)
   {
      self::$nameRoutes[$name] = $url;
   }

   /**
    * Get the URL of a registered route with parameters replaced.
    */
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

   // Add a route
   public static function add($uri, $controller, $action, $method = 'GET', $middleware = [])
   {
      self::$routes[] = [
         'method' => $method,
         'uri' => $uri,
         'controller' => $controller,
         'action' => $action,
         'middleware' => $middleware,
      ];
   }

   // GET method
   public static function get($uri, $controller = null, $action = null, $middleware = [])
   {
      self::add($uri, $controller, $action, 'GET', $middleware);
      return new self();
   }

   // POST method
   public static function post($uri, $controller, $action, $middleware = [])
   {
      self::add($uri, $controller, $action, 'POST', $middleware);
      return new self();
   }

   // Handle incoming request
   public static function handle()
   {
      $requestUri = strtok($_SERVER['REQUEST_URI'], '?'); // Remove query params
      $requestMethod = $_SERVER['REQUEST_METHOD'];
      
      foreach (self::$routes as $route) {
         // Convert route uri to regex for dynamic parameters
         $pattern = preg_replace('/{([^}]+)}/', '([^/]+)', $route['uri']);
         $pattern = str_replace('/', '\\/', $pattern);
         
         if ($route['method'] === $requestMethod && preg_match("/^$pattern$/", $requestUri, $matches)) {
            array_shift($matches); // Remove full match
            
            // Middleware Handling
            foreach ($route['middleware'] as $middleware) {
               $middlewareClass = new $middleware;
               if (method_exists($middlewareClass, 'handle') && $middlewareClass->handle() === false) {
                  return; // Stop execution if middleware fails
               }
            }

            if (is_callable($route['controller'])) {
               call_user_func_array($route['controller'], $matches);
               return;
            }

            $controllerClass = self::$controllerNamespace . $route['controller'];
            $action = $route['action'];
            
            if (class_exists($controllerClass)) {
               $controller = new $controllerClass();
               if (method_exists($controller, $action)) {
                  call_user_func_array([$controller, $action], $matches);
                  return;
               }
            }
         }
      }

      http_response_code(404);
      echo '404 Page Not Found.';
   }

   // Name a route
   public static function name($name)
   {
      $lastRoute = end(self::$routes);
      if ($lastRoute) {
         self::$nameRoutes[$name] = $lastRoute['uri'];
      }
      return new self();
   }

   // Generate URL for a named route
   public static function route($name)
   {
      return self::$nameRoutes[$name] ?? null;
   }
}

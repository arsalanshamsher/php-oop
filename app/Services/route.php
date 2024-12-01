<?php

namespace App\Services;


class Route
{
   private static $routes = [];
   private static $nameRoutes = [];
   private static $controllerNamespace = 'App\Controllers\\';


   /**
    * Register a route with a name and a corresponding URL pattern.
    * @param string $name
    * @param string $url
    */
   public static function register($name, $url)
   {
      self::$nameRoutes[$url] = $name;
   }


   /**
    * Get the URL of a registered route with parameters replaced.
    * @param string $name
    * @param array $params
    * @return string|null
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


   // route add method
   public static function add($uri, $controller, $action, $method = 'GET', $middlewhere = [])
   {
      self::$routes[] = [
         'method' => $method,
         'uri' => $uri,
         'controller' => $controller,
         'action' => $action,
         'middlewhere' => $middlewhere,
      ];
   }

   // get method
   public static function get($uri, $controller = null, $action = null, $middlewhere = [])
   {
      self::add($uri, $controller, $action, 'GET', $middlewhere);
      return new self();
   }
   // post method
   public static function post($uri, $controller, $action, $middlewhere = [])
   {
      self::add($uri, $controller, $action, 'POST', $middlewhere);
      return new self();
   }
   // method handle
   public static function handle()
   {
      $requestUri = $_SERVER['REQUEST_URI'];
      $requestMethod = $_SERVER['REQUEST_METHOD'];
      foreach (self::$routes as $route) {
         if ($route['uri'] === $requestUri && $route['method'] == $requestMethod) {
            // handle middlewhere
            foreach ($route['middlewhere'] as $middlewhere) {
               $middlewhereClass = new $middlewhere;
               $middlewhereClass->handle();
            }
            if (is_callable($route['controller'])) {
               call_user_func($route['controller']);
               return true;
            }
            $controller = self::$controllerNamespace . $route['controller'];
            $action = $route['action'];

            $controller = new $controller();
            $controller->$action();
            return true;
         }
      }
      echo '404 Page Not Found.';
   }

   // Name the route (this enables chaining with ->name('route_name'))
   // Name the route (this enables chaining with ->name('route_name'))
   public static function name($name){
       // Target the last added route
       $lastRoute = end(self::$routes);
       if($lastRoute){
         self::$nameRoutes[$name] = $lastRoute['uri'];
       }
       return new self();
   }
   // Generate URL for a named route
   public static function route($name)
   {
      if (isset(self::$nameRoutes[$name])) {
         return self::$nameRoutes[$name];
      }
      return null;
   }
}

<?php

namespace App\Services;


class Route
{
   private static $routes = [];
   private static $controllerNamespace = 'App\Controllers\\';

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
   public static function get($uri, $controller=null, $action=null, $middlewhere = [])
   {
      self::add($uri, $controller, $action, 'GET', $middlewhere);
   }
   // post method
   public static function post($uri, $controller, $action, $middlewhere = [])
   {
      self::add($uri, $controller, $action, 'POST', $middlewhere);
   }
   // method handle
   public static function handle()
   {
      $requestUri = $_SERVER['REQUEST_URI'];
      $requestMethod = $_SERVER['REQUEST_METHOD'];
      foreach (self::$routes as $route) {
         if ($route['uri'] === $requestUri && $route['method'] == $requestMethod) {
            // handle middlewhere
            foreach($route['middlewhere'] as $middlewhere){
               $middlewhereClass = new $middlewhere;
               $middlewhereClass->handle();
            }
            if(is_callable($route['controller'])){
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
}

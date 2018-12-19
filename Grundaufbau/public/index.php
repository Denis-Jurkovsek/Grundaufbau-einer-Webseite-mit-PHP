<?php
session_start();

require __DIR__ . "/../init.php";

$pathInfo = $_SERVER['PATH_INFO'];

$routes = [
  '/index' => [
    'controller' => 'CONTROLLER',
    'method' => 'METHOD'
  ]];

if (isset($routes[$pathInfo])) {
  $route = $routes[$pathInfo];
  $controller = $container->make($route['controller']);
  $method = $route['method'];
  $controller->$method();
}

?>

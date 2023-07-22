<?php

require 'System/Config/config.php';
require 'System/Routing/Autoloader.php';
Autoloader::register();

use System\Routing\Router;
use System\Routing\Startup;
use System\Http\Request;
use System\Http\Response;

$request = new Request();
$response = new Response();


$request_url = $request->getUrl();
$request_method = $request->getMethod();

$router = new Router();

// Инициализируем роутер и добавляем маршруты с помощью класса Startup
Startup::createRouters($router);

$route = $router->dispatch($request_url, $request_method);

$response = new Response();

if ($route) {
    // Разбираем строку с контроллером и методом
    list($controller_name, $method_name) = explode('@', $route['controller']);

    // Формируем полное имя класса контроллера
    $controller_class = $controller_name;

    // Создаем экземпляр контроллера
    $controller = new $controller_class();

    // Вызываем метод контроллера и передаем ему совпадения из URL
    call_user_func_array([$controller, $method_name], $route['matches']);
} else {
    $response->setStatusCode(404);
    $response->setContent(['error' => 'Not Found', 'status_code' => 404]);
}
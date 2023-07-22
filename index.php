<?php

require 'System/Config/config.php';
require 'System/Routing/Autoloader.php';
Autoloader::register();

use System\Routing\Router;
use System\Routing\Startup;
use Http\Request;
use Http\Response;

$request = new Request();
$response = new Response();

$response->setHeader('Access-Control-Allow-Origin: *');
$response->setHeader("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
$response->setHeader('Content-Type: application/json; charset=UTF-8');

$request_method = $request->getMethod();
$request_url = $request->getUrl();

$router = new Router();

// Инициализируем роутер и добавляем маршруты с помощью класса Startup
Startup::createRouters($router);

$route = $router->dispatch($request_url, $request_method);

if ($route) {
    // Разбираем строку с контроллером и методом
    list($controller_name, $method_name) = explode('@', $route['controller']);

    // Формируем полное имя класса контроллера
    $controller_class = 'Controllers\\' . $controller_name;

    // Создаем экземпляр контроллера
    $controller = new $controller_class();

    // Вызываем метод контроллера и передаем ему совпадения из URL
    call_user_func_array([$controller, $method_name], $route['matches']);
} else {
    $response->setStatusCode(404);
    $response->setContent(['error' => 'Not Found', 'status_code' => 404]);
}

$response->render();

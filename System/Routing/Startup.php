<?php

namespace System\Routing;

use Controllers\TestController;

class Startup
{
    public static function createRouters(Router $router)
    {
        // Здесь добавляем все маршруты, необходимые для приложения
        $router->addRoute('GET', '/products', 'ProductController@index');
        $router->addRoute('GET', '/users', 'UserController@index');
        $router->addRoute('GET', '/carts', 'CartController@index');
        $router->addRoute('GET', '/categories', 'CategoryController@index');
        $router->addRoute('GET', '/test', TestController::class . '@index');

        // Возвращаем массив маршрутов для вывода
        return $router->getRoutes();
    }
}

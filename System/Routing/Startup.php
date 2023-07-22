<?php

namespace System\Routing;


class Startup
{
    public static function createRouters(Router $router)
    {
        // Здесь добавляем все маршруты, необходимые для приложения
        $router->addRoute('GET', '/products', 'Application\Controllers\ProductController@index');
        $router->addRoute('GET', '/users', 'Application\Controllers\UserController@index');
        $router->addRoute('GET', '/carts', 'Application\Controllers\CartController@index');
        $router->addRoute('GET', '/categories', 'Application\Controllers\CategoryController@index');
        $router->addRoute('GET', '/test', 'Application\Controllers\TestController@index');

        // Возвращаем массив маршрутов для вывода
        return $router->getRoutes();
    }
}

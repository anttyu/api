<?php

namespace System\Routing;


class Startup
{
    public static function createRouters(Router $router)
    {
        // Здесь добавляем все маршруты, необходимые для приложения
        $router->addRoute('GET', '/products', 'Application\Controllers\ProductController@read');
        $router->addRoute('GET', '/products/{id}', 'Application\Controllers\ProductController@read_one');
        $router->addRoute('GET', '/products/search/{query}', 'Application\Controllers\ProductController@search');
        $router->addRoute('POST', '/products', 'Application\Controllers\ProductController@create');
        $router->addRoute('PATCH', '/products/{id}', 'Application\Controllers\ProductController@update');
        $router->addRoute('DELETE', '/products/{id}', 'Application\Controllers\ProductController@delete');

        $router->addRoute('GET', '/users', 'Application\Controllers\UserController@read');
        $router->addRoute('GET', '/users/{id}', 'Application\Controllers\UserController@read_one');
        $router->addRoute('POST', '/users', 'Application\Controllers\UserController@create');
        $router->addRoute('PATCH', '/users/{id}', 'Application\Controllers\UserController@update');
        $router->addRoute('DELETE', '/users/{id}', 'Application\Controllers\UserController@delete');

        $router->addRoute('GET', '/carts', 'Application\Controllers\CartController@read');
        $router->addRoute('GET', '/carts/user/{id}', 'Application\Controllers\CartController@read_user_cart');
        $router->addRoute('POST', '/carts', 'Application\Controllers\CartController@create');
        $router->addRoute('PATCH', '/carts/{id}', 'Application\Controllers\CartController@update');
        $router->addRoute('DELETE', '/carts/{id}', 'Application\Controllers\CartController@delete');

        $router->addRoute('GET', '/categories', 'Application\Controllers\CategoryController@read');
        $router->addRoute('GET', '/categories/{id}', 'Application\Controllers\CategoryController@read_one');
        $router->addRoute('POST', '/categories', 'Application\Controllers\CategoryController@create');
        $router->addRoute('PATCH', '/categories/{id}', 'Application\Controllers\CategoryController@update');
        $router->addRoute('DELETE', '/categories/{id}', 'Application\Controllers\CategoryController@delete');

        // Возвращаем массив маршрутов для вывода
        return $router->getRoutes();
    }
}
<?php

namespace System\Routing;


class Startup
{
    public static function createRouters(Router $router)
    {
        // Здесь добавляем все маршруты, необходимые для приложения
        $router->addRoute('GET', '/products/read', 'Application\Controllers\ProductController@read');
        $router->addRoute('GET', '/products/read_one', 'Application\Controllers\ProductController@read_one');
        $router->addRoute('GET', '/products/read_paging', 'Application\Controllers\ProductController@read_paging');
        $router->addRoute('GET', '/products/search', 'Application\Controllers\ProductController@search');
        $router->addRoute('POST', '/products/create', 'Application\Controllers\ProductController@create');
        $router->addRoute('PUT', '/products/update', 'Application\Controllers\ProductController@update');
        $router->addRoute('DELETE', '/products/delete', 'Application\Controllers\ProductController@delete');

        $router->addRoute('GET', '/users/read', 'Application\Controllers\UserController@read');
        $router->addRoute('POST', '/users/create', 'Application\Controllers\UserController@create');
        $router->addRoute('PUT', '/users/update', 'Application\Controllers\UserController@update');
        $router->addRoute('DELETE', '/users/delete', 'Application\Controllers\UserController@delete');

        $router->addRoute('GET', '/carts/read', 'Application\Controllers\CartController@read');
        $router->addRoute('GET', '/carts/read_user_cart', 'Application\Controllers\CartController@read_user_cart');
        $router->addRoute('POST', '/carts/create', 'Application\Controllers\CartController@create');
        $router->addRoute('PUT', '/carts/update', 'Application\Controllers\CartController@update');
        $router->addRoute('DELETE', '/carts/delete', 'Application\Controllers\CartController@delete');

        $router->addRoute('GET', '/categories/read', 'Application\Controllers\CategoryController@read');
        $router->addRoute('POST', '/categories/create', 'Application\Controllers\CategoryController@create');
        $router->addRoute('PUT', '/categories/update', 'Application\Controllers\CategoryController@update');
        $router->addRoute('DELETE', '/categories', 'Application\Controllers\CategoryController@delete');
        
        // Возвращаем массив маршрутов для вывода
        return $router->getRoutes();
    }
}

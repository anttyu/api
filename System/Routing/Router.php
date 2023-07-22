<?php

namespace System\Routing;

class Router {
    protected $routes = [];
    protected $params = [];

    public function addRoute($method, $pattern, $controller)
    {
        $pattern = '/' . trim($pattern, '/'); // Удаляем слэш в начале и добавляем одиночный слэш
        $this->routes[] = array(
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller
        );
    }

    // В методе dispatch класса Router
    public function dispatch($url, $method)
    {
        foreach ($this->routes as $route)
        {
            $pattern = $route['pattern'];
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';
            echo $pattern . " Pattern" . '</br>'; // Распечатаем паттерн маршрута
            echo $url . " url" . '</br>'; // Распечатаем URL запроса
            echo $route['method'] . " route" . '</br>'; // Распечатаем метод маршрута
            echo $method . " method" . '</br>'; // Распечатаем метод запроса
            if (preg_match($pattern, $url, $matches) && $route['method'] === $method)
            {
                var_dump($route); // Распечатаем маршрут, если совпадение найдено
                return $route;
            }
        }
        return null;
    }


    public function getRoutes() {
        return $this->routes;
    }
}

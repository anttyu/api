<?php

namespace System\Routing;

class Router {
    private $routes = [];

    public function addRoute($method, $pattern, $controller)
    {
        $this->routes[] =
            [
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller
            ];
    }

    public function dispatch($url, $method)
    {
        foreach ($this->routes as $route)
        {
            if ($route['method'] === $method && $route['pattern'] === $url)
            {
                return $route;
            }
        }

        return null;
    }
    public function getRoutes()
    {
        return $this->routes;
    }
}
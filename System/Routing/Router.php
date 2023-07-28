<?php

namespace System\Routing;

/**
 * Class Router
 * @package System\Routing
 */
class Router {
    /**
     * @var array
     */
    protected $routes = [];
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param $method
     * @param $pattern
     * @param $controller
     */
    public function addRoute($method, $pattern, $controller)
    {
        $pattern = '/' . trim($pattern, '/'); // Удаляем слэш в начале и добавляем одиночный слэш
        $this->routes[] = array(
            'method' => $method,
            'pattern' => $pattern,
            'controller' => $controller
        );
    }

    /**
     * @param $url
     * @param $method
     * @return mixed|null
     */
    public function dispatch($url, $method)
    {
        foreach ($this->routes as $route)
        {
            $pattern = $route['pattern'];

            $pattern = preg_replace('/{id}/', '\d+', $pattern);
            $pattern = preg_replace('/{query}/', '\w+', $pattern);

            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';
            if (preg_match($pattern, $url, $matches) && $route['method'] === $method)
            {
                $route['matches'] = $matches;
                return $route;
            }
        }
        /*
        echo "URL: " . $url . "<br>";
        echo "Method: " . $method . "<br>";
        echo "Available routes: ";
        foreach ($this->routes as $route) {
            echo $route['pattern'] . " ";
        }*/
        return null;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}

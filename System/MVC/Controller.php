<?php

namespace System\MVC;

abstract class Controller
{
    protected function model($model, ...$args)
    {
        $file = MODELS . ucfirst($model) . '.php';

        if (file_exists($file)) {
            require_once $file;

            $modelClass = 'Application\Models\\' . str_replace('/', '', ucwords($model, '/'));

            if (class_exists($modelClass))
                return new $modelClass(...$args);
            else
                throw new \Exception(sprintf('{ %s } this model class not found', $modelClass));
        } else {
            throw new \Exception(sprintf('{ %s } this model file not found', $file));
        }
    }

}
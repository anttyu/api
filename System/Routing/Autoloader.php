<?php


class Autoloader
{
    public static function register()
    {
        spl_autoload_register(function ($class)
        {
            $file = str_replace('\\', '/', $class) . '.php';
            if (file_exists($file))
            {
                require_once $file;
            } else
            {
                echo "Class not found: $class<br>";
            }
        });
    }
}

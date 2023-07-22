<?php

namespace Application\Controllers;

class HomeController
{
    public function index()
    {
        $message = "Welcome to our API!";
        return $message;
    }
}

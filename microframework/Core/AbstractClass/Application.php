<?php

namespace MicroFramework\Core\AbstractClass;

use MicroFramework\Core\Router\Router;

abstract class Application
{
    private array $controllers;

    public function __construct(private readonly Router $router, string ...$controller)
    {
        $this->controllers = $controller;
    }

    public function __get($key) {return $this->controllers[$key];}

    public function __isset($key) {return isset($this->controllers[$key]);}
}
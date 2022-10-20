<?php

namespace MicroFramework\Core\Applications;

use MicroFramework\Core\AbstractClass\Application;
use MicroFramework\Core\Router\Router;

class WebApplication extends Application
{
    public function __construct(Router $router, string ...$controller)
    {
        parent::__construct($router, ...$controller);
    }
}
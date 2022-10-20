<?php

namespace MicroFramework\Core;

use Dotenv\Dotenv;
use Exception;
use MicroFramework\Core\Api\Documentation\Controller\ApiDocumentationController;
use MicroFramework\Core\Router\Exceptions\RouteNameAlreadyDeclared;
use MicroFramework\Core\Router\Router;
use ReflectionException;

class ApplicationManager
{
    private array $apps = array();

    public function __construct(private readonly string $envDir)
    {
        ini_set("display_errors", 1);

        $dotenv = Dotenv::createImmutable($this->envDir);
        $dotenv->load();
    }

    /**
     * Start all applications.
     *
     * @throws ReflectionException
     * @throws RouteNameAlreadyDeclared
     * @throws Exception
     * @return void
     */
    public function startAll(): void
    {
        $router = new Router((empty($_GET["route"])) ? "/" : $_GET["route"]);
        foreach ($this->apps as $app)
        {
            $router->getRouteFromController(...$app);
        }

        $router->listen();
    }

    /**
     * Start an application by name.
     *
     * @param string $name Application name
     * @return void
     */
    public function start(string $name): void
    {}

    /**
     * Register a new application.
     *
     * @param string $name Application name
     * @param string ...$controllers Controller(s)
     * @return bool Return true if the application has been created successfully, otherwise false.
     */
    public function newApp(string $name, string ...$controllers): bool
    {
        if (in_array($name, $this->apps))
            return false;

        $this->apps[$name] = $controllers;
        return true;
    }

    public function setupApiDoc(string $name)
    {

    }
}
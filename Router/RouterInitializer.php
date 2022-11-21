<?php

namespace Framework\Router;

use Framework\API\Attributes\API;
use Framework\Router\Exceptions\MethodNotSupported;
use Framework\Router\Exceptions\RouteNameAlreadyDeclared;
use Framework\Router\Exceptions\RouteNotFound;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;

/**
 *  Singleton class to manage Router
 */
class RouterInitializer
{
    private static ?RouterInitializer $instance = null;
    public Router $router;
    public ?array $controllers = null;

    /**
     * @param bool $autoStart
     * @throws ReflectionException
     */
    private function __construct(bool $autoStart = false)
    {
        $this->router = new Router();
        if ($autoStart)
            $this->initialize()->__setControllerMap();
    }

    /**
     * Create a map.json
     *
     * @return void
     */
    private function __setControllerMap(): void
    {
        file_put_contents(dirname(__DIR__) . "/Controller/map.json", json_encode($this->controllers));
    }

    private function __mergeControllers(array &$controllers1, array $controllers2): void
    {
        $controllers1["Controllers"] = array_merge($controllers1["Controllers"], $controllers2["Controllers"]);
        $controllers1["Api"] = array_merge($controllers1["Api"], $controllers2["Api"]);
    }

    /**
     * @param bool $autoStart
     * @return RouterInitializer
     * @throws ReflectionException
     */
    public static function getInstance(bool $autoStart = false): RouterInitializer
    {
        return (self::$instance ??= new RouterInitializer($autoStart));
    }

    /**
     * @param string $dir
     * @param string|null $prefix
     * @return array
     * @throws ReflectionException
     */
    #[ArrayShape(["Controllers" => "array", "Api" => "array"])]
    public function getControllers(string $dir, ?string $prefix = null): array
    {
        $controllers = ["Controllers" => [], "Api" => []];
        $namespace = explode("\\", __NAMESPACE__)[0] . "\\Controller\\" . ($prefix ? "$prefix\\" : "") ;

        foreach (array_diff(scandir($dir), [".", ".."]) as $path)
        {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $path))
                $this->__mergeControllers($controllers, $this->getControllers($dir . DIRECTORY_SEPARATOR . $path, $path));

            if (is_file($dir . DIRECTORY_SEPARATOR . $path) && array_slice(explode('.', $path), -1)[0] == "php")
                $this->saveController($controllers, $namespace . explode('.', $path)[0]);
        }

        return $controllers;
    }

    /**
     * @throws ReflectionException
     */
    public function saveController(array &$controllers, string $controllerNamespace): void
    {
        $controllers["Controllers"][] = $controllerNamespace;

        if (!empty((new \ReflectionClass($controllerNamespace))->getAttributes(API::class)))
            $controllers["Api"][] = $controllerNamespace;
    }

    /**
     * @return RouterInitializer
     * @throws ReflectionException
     */
    public function initialize(): RouterInitializer
    {
        $this->controllers = $this->getControllers(dirname(__DIR__) . "/Controller");
        return $this;
    }

    /**
     * @return void
     * @throws MethodNotSupported
     * @throws ReflectionException
     * @throws RouteNameAlreadyDeclared
     * @throws RouteNotFound
     */
    public function listen(): void
    {
        $this->router
            ->getRouteFromStringControllers(... ( $this->controllers ?? json_decode( file_get_contents(dirname(__DIR__) . "/Controller/map.json"), true ) )["Controllers"] )
            ->listen();
    }
}
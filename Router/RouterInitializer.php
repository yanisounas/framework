<?php

namespace Framework\Router;

use Framework\Router\Exceptions\MethodNotSupported;
use Framework\Router\Exceptions\RouteNameAlreadyDeclared;
use Framework\Router\Exceptions\RouteNotFound;
use ReflectionException;

/**
 *  Singleton class to manage Router
 */
class RouterInitializer
{
    private static ?RouterInitializer $instance = null;
    public Router $router;
    private ?array $controllers = null;

    /**
     * @param bool $autoStart
     */
    private function __construct(bool $autoStart = false)
    {
        if ($autoStart)
        {
            $this->initialize();
            $this->router = new Router();
            $this->__setControllerMap();
        }
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

    /**
     * @param bool $autoStart
     * @return RouterInitializer
     */
    public static function getInstance(bool $autoStart = false): RouterInitializer
    {
        return (self::$instance ??= new RouterInitializer($autoStart));
    }

    /**
     * @param string $dir
     * @param string|null $prefix
     * @return array
     */
    public function getControllers(string $dir, ?string $prefix = null): array
    {
        $controllers = [];
        $namespace = explode("\\", __NAMESPACE__)[0] . "\\Controller\\" . ($prefix ? "$prefix\\" : "") ;

        //$namespace = ($prefix) ? explode("\\", __NAMESPACE__)[0] . "\\Controller\\$prefix\\" : explode("\\", __NAMESPACE__)[0] ."\\Controller\\";

        foreach (array_diff(scandir($dir), [".", ".."]) as $path)
        {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $path))
                $controllers = array_merge($controllers, $this->getControllers($dir . DIRECTORY_SEPARATOR . $path, $path));

            if (is_file($dir . DIRECTORY_SEPARATOR . $path) && array_slice(explode('.', $path), -1)[0] == "php")
                $controllers[] = $namespace . explode('.', $path)[0];
        }

        return $controllers;
    }

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->controllers = $this->getControllers(dirname(__DIR__) . "/Controller");
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
        ($this->router ??= new Router())
            ->getRouteFromStringControllers(... ($this->controllers ??= json_decode( file_get_contents(dirname(__DIR__) . "/Controller/map.json") ) ) )
            ->listen();
    }
}
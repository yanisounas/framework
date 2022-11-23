<?php

namespace Framework\API;


use Framework\API\Attributes\API;
use Framework\API\Attributes\Endpoint;
use Framework\API\FastDoc\FastDocController;
use Framework\Router\Attributes\Route;
use Framework\Router\Exceptions\RouteNameAlreadyDeclared;
use Framework\Router\RouterInitializer;
use ReflectionClass;
use ReflectionException;

class ApiManager
{
    private static ?ApiManager $instance = null;
    private array $apiControllers;
    public array $api;

    /**
     * @param string ...$ApiController
     */
    private function __construct(string ...$ApiController)
    {
        $this->apiControllers = (!empty($ApiController) ? $ApiController : json_decode(file_get_contents(dirname(__DIR__) . "/Controller/map.json"), true)["Api"]);
    }

    /**
     * @param string ...$ApiController
     * @return ApiManager
     */
    public static function getInstance(string ...$ApiController): ApiManager
    {
        return (self::$instance ??= new ApiManager(...$ApiController));
    }

    /**
     * @param ReflectionClass $apiReflect
     * @return void
     * @throws ReflectionException
     */
    public function addApi(ReflectionClass $apiReflect): void
    {
        $attributeInstance = $apiReflect->getAttributes(API::class)[0]->newInstance();

        foreach ($apiReflect->getMethods() as $method)
        {
            $endpoint = empty($method->getAttributes(Endpoint::class)) ? null : $method->getAttributes(Endpoint::class)[0];
            $route = empty($method->getAttributes(Route::class)) ? null : $method->getAttributes(Route::class)[0];

            if (!$endpoint || !$route)
                continue;

            $endpoint = $endpoint->newInstance();
            $route = $route->newInstance();
            $endpoint->setMethod( ( $endpoint->getMethod() ?? $route->getMethod()) );


            $this->api[$attributeInstance->getAppName()][$route->getPath()] = $endpoint;
        }
    }

    /**
     * @param array|null $apis
     * @param ReflectionClass ...$apiReflect
     * @return void
     * @throws ReflectionException
     */
    public function addMultipleApis(?array $apis = null, ReflectionClass ...$apiReflect): void
    {
        $apis ??= $apiReflect;

        foreach ($apis as $api)
            $this->addApi($api);
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws RouteNameAlreadyDeclared
     */
    public function initialize(): void
    {
        if (empty($this->apiControllers))
            return;

        foreach ($this->apiControllers as &$controller)
            $controller = new \ReflectionClass($controller);

        $this->addMultipleApis($this->apiControllers);

        RouterInitializer::getInstance()->router->getRouteFromStringControllers(FastDocController::class);
    }
}
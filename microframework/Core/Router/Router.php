<?php

namespace MicroFramework\Core\Router;

use MicroFramework\Core\Response\View;
use MicroFramework\Core\Router\Attributes\Endpoint;
use MicroFramework\Core\Router\Attributes\Route;
use MicroFramework\Core\Router\Exceptions\MethodNotSupported;
use MicroFramework\Core\Router\Exceptions\RouteNameAlreadyDeclared;
use MicroFramework\Core\Router\Exceptions\RouteNotFound;
use ReflectionException;

class Router
{
    public array $routes;
    public array $namedRoutes;
    public array $endpoints;

    public function __construct(private readonly string $url) {}

    /**
     * Create a new Route/Named Route
     *
     * @param Route $route
     * @param string $path Path/Regex for the route
     * @param string $method HTTP METHOD(S). Use multiple with pipes (GET|POST|UPDATE|DELETE) (multiple methods not implemented)
     * @param mixed $target The target when the route is called
     * @param string|null $name Optional name for the route (see Router->getPathFrom())
     * @return void
     * @throws RouteNameAlreadyDeclared
     */
    public function newRoute(Route $route, string $path, string $method, mixed $target, ?string $name = null): void
    {
        $this->routes[strtoupper($method)][trim($path, '/')] = [$route, $target];

        if ($name)
        {
            if (isset($this->namedRoutes[$name]))
                throw new RouteNameAlreadyDeclared("You can't overwrite the route \"{$name}\"");
            else
                $this->namedRoutes[$name] = $path;
        }
    }

    /**
     * Create a new Endpoint/Named Endpoint as Route and save it in endpoints.
     * This method is used to easily find info about a given endpoint for ApiDocumentation.
     *
     * @param Endpoint $endpoint
     * @param string $path Path/Regex for the endpoint
     * @param string $method HTTP METHOD(S). Use multiple with pipes (GET|POST|UPDATE|DELETE) (multiple methods not implemented)
     * @param mixed $target The target when the endpoint is called
     * @param string|null $name Optional name for the route (see Router->getPathFrom())
     * @return void
     * @throws RouteNameAlreadyDeclared
     */
    private function newEndpoint(Endpoint $endpoint, string $path, string $method, mixed $target, ?string $name = null): void
    {
        $this->newRoute($endpoint, $path, $method, $target, $name);

        $this->endpoints[strtoupper($method)] = [$endpoint, $path, $endpoint->getDescription()];
    }

    /**
     * Check all controllers for routes and add them
     *
     * @param string ...$controllers
     * @return void
     * @throws RouteNameAlreadyDeclared
     * @throws ReflectionException
     */
    public function getRouteFromController(string ...$controllers): void
    {
        foreach ($controllers as $controller)
        {
            $reflect = new \ReflectionClass($controller);
            foreach ($reflect->getMethods() as $method)
            {
                // TODO: Find a better way
                foreach ($method->getAttributes(Route::class) as $attribute)
                {
                    $route = $attribute->newInstance();
                    $this->newRoute($route, $route->getPath(), $route->getMethod(), [$controller, $method->getName()], $route->getRouteName());
                }

                foreach ($method->getAttributes(Endpoint::class) as $attribute)
                {
                    $endpoint = $attribute->newInstance();
                    $this->newEndpoint($endpoint, $endpoint->getPath(), $endpoint->getMethod(), [$controller, $method->getName()], $endpoint->getRouteName());
                }
            }
        }
    }

    /**
     * Check if a route match and execute the target
     *
     * @return mixed
     * @throws MethodNotSupported
     * @throws RouteNotFound
     */
    public function listen(): mixed
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];


        if (!isset($this->routes[$requestMethod]))
            throw new MethodNotSupported("Method '$requestMethod' not supported");

        foreach ($this->routes[$requestMethod] as $route)
        {
            if ($route[0]->match($this->url))
                return call_user_func_array([new $route[1][0](), $route[1][1]], $route[0]->getMatches());
        }
        if (!isset($_ENV["ERROR_404"]))
            throw new RouteNotFound("Route $this->url not found");
        return (new View($_ENV["ERROR_404"], 404))->render();
    }

    /**
     * Get Path from a named route
     *
     * @param string $name Name of the route
     * @return string
     * @throws RouteNotFound
     */
    public function getPathFrom(string $name): string
    {
        if (!isset($this->namedRoutes[$name]))
            throw new RouteNotFound("Route with name \"{$name}\" not found");

        return ($this->namedRoutes[$name] == "/") ? $this->namedRoutes[$name] : $this->namedRoutes[$name] . '/';

    }

}
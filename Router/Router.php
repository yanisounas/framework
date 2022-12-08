<?php

namespace Framework\Router;

use Framework\Request\Request;
use Framework\Response\View;
use Framework\Router\Attributes\Route;
use Framework\Router\Exceptions\MethodNotSupported;
use Framework\Router\Exceptions\RouteNameAlreadyDeclared;
use Framework\Router\Exceptions\RouteNotFound;
use ReflectionException;

class Router
{
    public array $routes;
    public array $namedRoutes;

    public function __construct(public ?string $url = null) {}


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
        if (str_contains($method, '|'))
            foreach (explode('|', $method) as $m)
                $this->routes[strtoupper($m)][trim($path, '/')] = [$route, $target];
        else
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
     * Check all controllers for routes and add them
     *
     * @param string ...$controllers
     * @return Router
     * @throws RouteNameAlreadyDeclared
     * @throws ReflectionException
     */
    public function getRouteFromStringControllers(string ...$controllers): Router
    {
        foreach ($controllers as $controller)
        {
            $reflect = new \ReflectionClass($controller);
            foreach ($reflect->getMethods() as $method)
            {
                foreach ($method->getAttributes(Route::class) as $attribute)
                {
                    $route = $attribute->newInstance();
                    $this->newRoute($route, $route->getPath(), $route->getMethod(), [$controller, $method->getName()], $route->getRouteName());
                }
            }
        }
        return $this;
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
        $requestMethod = Request::METHOD();
        $this->url ??= $_SERVER["REQUEST_URI"];
        $this->url = (!str_contains($this->url, "?")) ? $this->url : explode("?", $this->url)[0];

        if (!isset($this->routes[$requestMethod]))
            throw new MethodNotSupported("Method '$requestMethod' not supported");

        foreach ($this->routes[$requestMethod] as $route)
            if ($route[0]->match($this->url))
                return call_user_func_array([new $route[1][0](), $route[1][1]], $route[0]->getMatches());

        if (!isset($_ENV["ERROR_404"]))
            throw new RouteNotFound("Route $this->url not found");
        return (new View(dirname(__DIR__) . DIRECTORY_SEPARATOR . $_ENV["ERROR_404"], 404));
    }

    /**
     * Get Path from a named route
     *
     * @param string $name Name of the route
     * @return string
     * @throws RouteNotFound
     * @throws ReflectionException
     */
    public static function getPathFrom(string $name): string
    {
        $router = RouterInitializer::getInstance()->router;

        if (!isset($router->namedRoutes[$name]))
            throw new RouteNotFound("Route with name \"{$name}\" not found");

        return ($router->namedRoutes[$name] == "/") ? $router->namedRoutes[$name] : "/" . $router->namedRoutes[$name];
    }
}
<?php

namespace MicroFramework\Core\Api\Endpoint;

use Exception;
use MicroFramework\Core\Api\Endpoint\Attributes\Endpoint;
use MicroFramework\Core\Router\Exceptions\MethodNotSupported;
use ReflectionException;

class EndpointManager
{
    private array $endpoints;
    private array $namedEndpoints;

    public function __construct(private readonly string $url) {}


    /**
     * @param Endpoint $endpoint
     * @param string $path
     * @param string $method
     * @param mixed $target
     * @param string|null $name
     * @param string|null $description
     * @return void
     * @throws Exception
     */
    public function newEndpoint(Endpoint $endpoint, string $path, string $method, mixed $target, ?string $name = null, ?string $description = null): void
    {
        $this->endpoints[strtoupper($method)][trim($path, "/")] = ["endpoint" => $endpoint, "target" => $target];

        if ($name)
        {
            if (isset($this->namedEndpoints[$name]))
                throw new Exception("You can't overwrite the endpoint \"{$name}\"");
            else
                $this->namedEndpoints[$name] = $path;
        }
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function getEndpoints(string ...$controllers): void
    {
        foreach ($controllers as $controller)
        {
            $reflect = new \ReflectionClass($controller);
            foreach ($reflect->getMethods() as $method)
            {
                foreach ($method->getAttributes(Endpoint::class) as $attribute)
                {
                    $endpoint = $attribute->newInstance();
                    $this->newEndpoint($endpoint, $endpoint->getPath(), $endpoint->getMethod(), [$controller, $method->getName()], $endpoint->getName(), $endpoint->getDescription());
                }
            }
        }
    }


    /**
     * @throws Exception
     */
    public function listen()
    {
        $requestMethod = $_SERVER["REQUEST_METHOD"];

        if (!isset($this->endpoints[$requestMethod]))
            throw new MethodNotSupported("Method not supported");

        foreach ($this->endpoints[$requestMethod] as $endpoint)
        {
            if ($endpoint["endpoint"]->match($this->url))
            {
                return call_user_func_array([new $endpoint["target"][0](), $endpoint["target"][1]], ($endpoint["endpoint"]->getName() != "api-documentation") ? $endpoint["endpoint"]->getMatches() : [$this->endpoints]);
            }
        }
        if (!isset($_ENV["ERROR_404"]))
            throw new Exception("Route $this->url not found");
        header("Location: ".$_ENV["ERROR_404"]);
    }
}
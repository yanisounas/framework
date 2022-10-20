<?php

namespace MicroFramework\Core\Api;

use MicroFramework\Core\Api\Documentation\Controller\ApiDocumentationController;
use MicroFramework\Core\Api\Endpoint\EndpointManager;

class Api
{

    private array $controllers;

    public function __construct(string ...$controllers)
    {
        $this->controllers = $controllers;
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public function start(): void
    {
        $this->controllers[] = ApiDocumentationController::class;
        $em = new EndpointManager((empty($_GET["route"])) ? "/" : $_GET["route"]);
        $em->getEndpoints(...$this->controllers);
        $em->listen();
    }
}
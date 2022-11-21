<?php

namespace Framework\Controller;

use Framework\AbstractClass\ApiBase;
use Framework\AbstractClass\Entity;
use Framework\API\Attributes\API;
use Framework\API\Attributes\Endpoint;
use Framework\ORM\Mapper;
use Framework\Request\Request;
use Framework\Response\JSONResponse;
use Framework\Router\Attributes\Route;
use ReflectionException;

#[API(appName: "Users")]
class ApiController extends ApiBase
{
    public function __construct(private readonly Mapper $mapper = new Mapper()) {}

    #[Route("/api", method: "GET")]
    #[Endpoint("getAllUsers", description: "A list of all users")]
    public function getAllUsers(): JSONResponse
    {
        $users = $this->mapper->getAll("User");

        return $this->json(Entity::toAssocArrayAll($users));
    }

    /**
     * @throws ReflectionException
     */
    #[Route("/api/new", method: "POST")]
    #[Endpoint("newUser", description: "Add a new user")]
    public function newUser(): JSONResponse
    {
        $this->mapper->make("User", Request::stream());

        return $this->json(Request::stream());
    }

    #[Route("/api/user/{s}:username", method: "GET")]
    #[Endpoint("getUserByUsername", description: "Get a specific user by username", params: ["username" => ["type" => "string", "description" => "Username"]])]
    public function getUserByUsername(string $username): JSONResponse
    {
        $user = $this->mapper->getBy("User", ["username" => $username]);

        return $this->json($user->toAssocArray());
    }
}
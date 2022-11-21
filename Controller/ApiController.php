<?php

namespace Framework\Controller;

use Framework\AbstractClass\API;
use Framework\AbstractClass\Entity;
use Framework\ORM\Mapper;
use Framework\Request\Request;
use Framework\Response\JSONResponse;
use Framework\Router\Attributes\Route;

class ApiController extends API
{
    public function __construct(private readonly Mapper $mapper = new Mapper()) {}

    #[Route("/api", method: "GET")]
    public function getAllUsers(): JSONResponse
    {
        $users = $this->mapper->getAll("User");

        return $this->json(Entity::toAssocArrayAll($users));
    }

    /**
     * @throws \ReflectionException
     */
    #[Route("/api/new", method: "POST")]
    public function newUser()
    {
        $this->mapper->make("User", Request::stream());

        return $this->json(Request::stream());
    }

    #[Route("/api/user/{s}:username", method: "GET")]
    public function getUserByUsername(string $username)
    {
        $user = $this->mapper->getBy("User", ["username" => $username]);

        return $this->json($user->toAssocArray());
    }

}
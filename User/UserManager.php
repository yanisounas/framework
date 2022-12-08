<?php

namespace Framework\User;

use Framework\AbstractClass\Entity;
use Framework\ORM\Mapper;
use Framework\Security\Data;
use ReflectionException;

class UserManager
{

    public function __construct(private readonly Mapper $mapper = new Mapper(), private ?array $errors = null) {}

    /**
     * @param string $entityName
     * @param array|null $values
     * @return void
     * @throws ReflectionException
     */
    public function make(string $entityName, ?array $values = null): void
    {
        $reflect = $this->mapper->_getReflect($entityName);
        $props = $reflect->getProperties();

        $this->mapper->_removeBadProps($props, $values);


        var_dump($values);

    }

    /**
     * @throws ReflectionException
     */
    public function userExists(string $entityName, string $username): bool
    {
        if (!$this->mapper->getBy($entityName, ["username" => $username]))
        {
            $this->errors[] = "Unknown username";
            return false;
        }

        return true;
    }

    /**
     * @throws ReflectionException
     */
    public function login(string $entityName, array $credentials): bool
    {
        foreach ($credentials as &$credential)
            $credential = Data::cleanString($credential);

        if (!$this->userExists($entityName, $credentials["username"]))
            return false;

        $user = $this->mapper->getBy("User", ["username" => $credentials["username"]]);

        if (!password_verify($credentials["password"], $user->password))
        {
            $this->errors[] = "Invalid password";
            return false;
        }

        return true;
    }

    public function getErrors(): array|null
    {
        return $this->errors;
    }
}
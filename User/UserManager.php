<?php

namespace Framework\User;

use Framework\ORM\Mapper;
use ReflectionException;

class UserManager
{

    public static array $errors;

    /**
     * @param string $entityName
     * @param array|null $values
     * @return void
     * @throws ReflectionException
     */
    public static function make(string $entityName, ?array $values = null): void
    {
        $mapper = new Mapper();
        $reflect = $mapper->_getReflect($entityName);
        $props = $reflect->getProperties();

        $mapper->_removeBadProps($props, $values);


        var_dump($values);

    }

    public static function userExists(string $entityName, array $values): void
    {}
}
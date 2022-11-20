<?php

namespace Framework\AbstractClass;

use Framework\ORM\Attributes\Column;
use ReflectionException;

abstract class Entity
{
    public static ?string $TABLE_NAME = null;

    #[Column("int", 11, "AUTO_INCREMENT, PRIMARY KEY")]
    protected int $id;

    public function __set(string $name, $value): void
    {
        $this->$name = $value;
    }

    public function __get(string $name): mixed
    {
        return $this->$name;
    }

    public function toAssocArray(): array
    {
        $assocArray = [];
        foreach ((new \ReflectionClass($this))->getProperties() as $property)
            $assocArray[$property->getName()] = $property->getValue($this);

        unset($assocArray["TABLE_NAME"]);

    public function __construct()
    {

    }

    protected function _isValidValue(string $property, string $value): void
    {
    }

    public function load(array $values): Entity
    {
        foreach ($values as $key => $value)
        {
            $this->__set($key, $value);
        }

        return $this;
    }
}
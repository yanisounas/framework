<?php

namespace Framework\ORM;

use Framework\AbstractClass\Entity;
use Framework\ORM\QueryBuilder\Database;
use ReflectionClass;
use ReflectionException;

class Mapper
{
    private readonly Database $db;

    public function __construct()
    {
        $this->db = new Database($_ENV);
    }

    /**
     * @throws ReflectionException
     */
    private function __getReflect(string $entityName): ReflectionClass
    {
        //TODO: Gérer le cas où l'entity n'existe pas

        $entityName = "\\Framework\\Entity\\". (str_contains($entityName, "Entity") ? $entityName : $entityName . "Entity");
        return new ReflectionClass($entityName);
    }

    /**
     * @throws ReflectionException
     */
    public function getAll(string $entityName): array
    {
        $reflect = $this->__getReflect($entityName);

        $results = $this->db->select( $reflect->getProperty("TABLE_NAME")->getValue() )->exec()->fetchAll();
        $values = [];

        foreach ($results as $result)
        {
            $entity = $reflect->newInstance();
            $entity->load($result);
            $values[] = $entity;
        }

        return $values;
    }

    /**
     * @throws ReflectionException
     */
    public function getBy(string $entityName, array $columnValues = [], ...$kwargs): object
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->__getReflect($entityName);


        $result = $this->db->select( $reflect->getProperty("TABLE_NAME")->getValue() )->where($columnValues)->exec()->fetch();

        return ($reflect->newInstance())->load($result);
    }

    /**
     * @throws ReflectionException
     */
    public function getAllBy(string $entityName, array $columnValues = [], ...$kwargs): array
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->__getReflect($entityName);

        $results = $this->db->select( $reflect->getProperty("TABLE_NAME")->getValue() )->where($columnValues)->exec()->fetchAll();
        $values = [];

        foreach ($results as $result)
        {
            $entity = $reflect->newInstance();
            $entity->load($result);
            $values[] = $entity;
        }

        return $values;
    }

    /**
     * @throws ReflectionException
     */
    public function make(string $entityName, array $columnValues = [], ...$kwargs): void
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->__getReflect($entityName);
        $props = $reflect->getProperties();

        array_walk($props, function (&$item)
        {
            $item = $item->getName();
        });

        foreach (array_keys($columnValues) as $columnName)
        {
            if (in_array($columnName, $props))
                continue;

            unset($columnValues[$columnName]);
        }

        $this->db->insert($reflect->getProperty("TABLE_NAME")->getValue(), $columnValues);
    }

    public function push(Entity $entity)
    {

    }
}
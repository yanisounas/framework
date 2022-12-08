<?php

namespace Framework\ORM;

use Framework\AbstractClass\Entity;
use Framework\ORM\QueryBuilder\Database;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

class Mapper
{
    private readonly Database $db;

    public function __construct()
    {
        $this->db = new Database($_ENV);
    }

    /**
     * @param string $entityName
     * @return ReflectionClass
     * @throws ReflectionException
     */
    public function _getReflect(string $entityName): ReflectionClass
    {
        //TODO: Gérer le cas où l'entity n'existe pas

        $entityName = "\\Framework\\Entity\\". (str_contains($entityName, "Entity") ? $entityName : $entityName . "Entity");
        return new ReflectionClass($entityName);
    }

    /**
     * @param ReflectionProperty[] $entityProps
     * @param array $values
     * @return void
     */
    public function _removeBadProps(array $entityProps, array &$values): void
    {
        array_walk($entityProps, function (&$item)
        {
            $item = $item->getName();
        });

        foreach (array_keys($values) as $key)
        {
            if (in_array($key, $entityProps))
                continue;

            unset($values[$key]);
        }
    }

    /**
     * @param string $entityName
     * @return array
     * @throws ReflectionException
     */
    public function getAll(string $entityName): array
    {
        $reflect = $this->_getReflect($entityName);

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
     * @param string $entityName
     * @param array $columnValues
     * @param mixed ...$kwargs
     * @return object|bool
     * @throws ReflectionException
     */
    public function getBy(string $entityName, array $columnValues = [], mixed ...$kwargs): object|bool
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->_getReflect($entityName);

        $result = $this->db->select( $reflect->getProperty("TABLE_NAME")->getValue() )->where($columnValues)->exec()->fetchOne();

        return ($result) ? ($reflect->newInstance())->load($result) : false;
    }

    /**
     * @param string $entityName
     * @param array $columnValues
     * @param ...$kwargs
     * @return array
     * @throws ReflectionException
     */
    public function getAllBy(string $entityName, array $columnValues = [], ...$kwargs): array
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->_getReflect($entityName);

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
     * @param string $entityName
     * @param array $columnValues
     * @param ...$kwargs
     * @return void
     * @throws ReflectionException
     */
    public function make(string $entityName, array $columnValues = [], ...$kwargs): void
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->_getReflect($entityName);
        $this->_removeBadProps($reflect->getProperties(), $columnValues);

        $this->db->insert($reflect->getProperty("TABLE_NAME")->getValue(), $columnValues)->exec();
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function push(Entity $entity): void
    {
        $this->db->insert($entity::$TABLE_NAME, $entity->toAssocArray());
    }

    /**
     * @throws ReflectionException
     */
    public function delete(string $entityName, array $columnValues = [], ...$kwargs): void
    {
        $columnValues = array_merge($columnValues, $kwargs);
        $reflect = $this->_getReflect($entityName);
        $this->_removeBadProps($reflect->getProperties(), $columnValues);

        $this->db->delete($reflect->getProperty("TABLE_NAME")->getValue())->where($columnValues)->exec();
    }
}
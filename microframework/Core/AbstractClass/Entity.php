<?php

namespace MicroFramework\Core\AbstractClass;

use MicroFramework\Core\ORM\Core\Database;

abstract class Entity
{
    protected readonly Database $db;
    protected ?string $TABLE_NAME = null;

    public function __construct()
    {
        $this->db = new Database($_ENV);
        $this->TABLE_NAME = ($this->TABLE_NAME) ?? strtolower(array_slice(explode("\\", get_class($this)), -1)[0]);
    }

    public function getAll(): array
    {
        return $this->db->select($this->TABLE_NAME)->exec()->fetchAll();
    }
}
<?php

namespace Framework\ORM\QueryBuilder;


use Exception;

class Database
{
    private readonly \PDO $db;
    private readonly Query $query;

    public function __construct(private ?array $opts = null, string ...$opt)
    {
        $this->opts ??= $opt;
        $this->_connect();
        $this->query = new Query($this->db);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call(string $name, array $arguments): mixed
    {
        return call_user_func_array([$this->query, $name], $arguments);
    }

    private function _connect(): void
    {
        $this->db = new \PDO("mysql:host=".$this->opts["DB_HOST"].";port=".$this->opts["DB_PORT"].";dbname=".$this->opts["DB_NAME"].";charset=".$this->opts["DB_CHARSET"], $this->opts["DB_USERNAME"], $this->opts["DB_PASSWORD"]);
    }
}
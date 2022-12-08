<?php

namespace Framework\ORM\QueryBuilder;

use Framework\ORM\Enum\QueryMethod;
use Framework\ORM\Exceptions\ORMException;
use JetBrains\PhpStorm\Deprecated;
use PDO;
use PDOStatement;

class Query
{
    private PDOStatement|string $query;
    private ?string $where = null;
    private ?string $order = null;
    private ?array $values = null;
    private ?string $limit = null;
    private ?QueryMethod $method = null;

    public function __construct(private readonly PDO $db) {}

    #[Deprecated("Unsafe method")]
    public function setQueryString(string $query): Query
    {
        // TODO: remove this method (only here to use join or more complexe queries while they're not implemented)
        $this->method = QueryMethod::CUSTOM;
        $this->query = $query;
        return $this;
    }

    public function select(string $table, array $columns = ['*']): Query
    {
        $this->method = QueryMethod::SELECT;
        $columns = implode(', ', $columns);
        $this->query = "SELECT $columns FROM $table";
        return $this;
    }

    public function delete(string $table): Query
    {
        $this->method = QueryMethod::DELETE;
        $this->query = "DELETE FROM $table";
        return $this;
    }

    public function update(string $table, array $values): Query
    {
        $this->method = QueryMethod::UPDATE;
        $this->query = "UPDATE $table SET ";

        $datas = [];
        foreach ($values as $key => $value)
            $datas[] = "$key=?";

        $this->query .= implode(', ', $datas);
        $this->values = array_merge(($this->values ?? []), array_values($values));

        return $this;
    }

    public function insert(string $table, array $values): Query
    {
        $this->method = QueryMethod::INSERT;

        $columns = implode(', ', array_keys($values));
        $prepared = implode(', ', array_fill(0, count($values), '?'));
        $this->query = "INSERT INTO `$table`($columns) VALUES($prepared)";

        $this->values = array_values($values);
        return $this;
    }

    public function where(?array $where_data = null, mixed ...$data): Query
    {
        $where_data ??= $data;
        $this->where ??= " WHERE ";

        $datas = [];
        foreach ($where_data as $key => $value)
            $datas[] = "$key=?";

        $this->where .= implode(', ', $datas);
        $this->values = array_merge(($this->values ?? []), array_values($where_data));

        return $this;
    }

    private function _where(): Query
    {
        if (!is_null($this->where)) $this->query .= $this->where;

        return $this;
    }

    /**
     * @param array $order_data arr[columns] or arr[columns, order (default: ASC)]
     * @return Query
     */
    public function orderBy(array $order_data): Query
    {
        $this->order ??= " ORDER BY ";
        $temp = [];

        if(array_keys($order_data) !== range(0, count($order_data) - 1))
        {
            foreach ($order_data as $k => $v)
            {
                if (gettype($k) == 'integer')
                {
                    $k = $v;
                    $v = "ASC";
                }

                $temp[] = "$k $v";
            }
        }
        else
        {
            foreach ($order_data as $data)
            {
                $temp[] = "$data ASC";
            }
        }

        $this->order .= implode(', ', $temp);
        return $this;
    }

    private function _order(): Query
    {
        if (!is_null($this->order)) $this->query .= $this->order;

        return $this;
    }

    public function limit(int $limit = 1): Query
    {
        $this->limit ??= " LIMIT $limit";
        return $this;
    }

    public function _limit(): Query
    {
        if (!is_null($this->limit)) $this->query .= $this->limit;

        return $this;
    }

    /**
     * @throws ORMException
     */
    public function exec(): Query
    {
        try
        {
            if (!$this->method || empty($this->query)) throw new ORMException("You have to build a query before calling this method");
            if (($this->method === QueryMethod::UPDATE || $this->method === QueryMethod::DELETE) && is_null($this->where)) throw new ORMException($this->method->name . " need a where statement");

            $this->_where()->_order()->_limit();


            if ($this->values)
                array_walk($this->values, function (&$item)
                {
                    $item = htmlspecialchars($item);
                });

            $this->query = $this->db->prepare($this->query);
            $this->query->execute($this->values);

            $this->values = null;
            $this->where = null;
            $this->limit = null;

            if ($this->method !== QueryMethod::SELECT && $this->method !== QueryMethod::CUSTOM)
                $this->method = null;
        }
        catch (\PDOException $e) {die(throw new \PDOException($e->getMessage()));}
        return $this;
    }

    /**
     * @throws ORMException
     */
    public function fetchOne(int $mode = PDO::FETCH_ASSOC): mixed
    {
        if (!$this->query instanceof PDOStatement) throw new ORMException("Query need to be executed first");
        if ($this->method !== QueryMethod::SELECT && $this->method !== QueryMethod::CUSTOM) throw new ORMException("Fetch not supported for ". $this->method->name);

        return $this->query->fetch($mode);
    }

    /**
     * @throws ORMException
     */
    public function fetchAll(int $mode = PDO::FETCH_ASSOC): array|false
    {
        if (!$this->query instanceof PDOStatement) throw new ORMException("Query need to be executed first");
        if ($this->method !== QueryMethod::SELECT && $this->method !== QueryMethod::CUSTOM) throw new ORMException("FetchAll not supported for " . $this->method->name);

        return $this->query->fetchAll($mode);

    }
}
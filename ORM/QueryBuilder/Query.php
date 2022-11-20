<?php

namespace Framework\ORM\QueryBuilder;

use JetBrains\PhpStorm\Deprecated;

class Query
{
    //TODO: Implement security

    private \PDOStatement|string $sqlQuery;
    private string $queryType;
    private string $whereQuery;
    private string $orderQuery;
    private int $limit;
    private int $offset;

    public function __construct(private \PDO &$db) {}

    public function select(string $table, array $columns = ['*']): Query
    {
        $this->queryType = 'SELECT';

        $columns = implode(', ', $columns);
        $this->sqlQuery = "SELECT $columns FROM $table";
        return $this;
    }

    #[Deprecated("Unsafe method")]
    public function setQuery(string $query): Query
    {
        // TODO: remove this method (only here to use limits, inner join or more complexe queries while they're not implemented)
        $this->sqlQuery = $query;
        return $this;
    }

    public function delete(string $table): Query
    {
        $this->sqlQuery = "DELETE FROM $table";
        return $this;
    }

    public function update(string $table, array $values): Query
    {
        $this->sqlQuery = "UPDATE $table";
        $this->sqlQuery .= " SET " . implode(', ', $this->_formatKwargs($values));
        return $this;
    }

    public function insert(string $table, array $values): void
    {
        $this->queryType = 'INSERT';

        $columns = implode(', ', array_keys($values));
        $v = implode(', ', array_fill(0, count($values), '?'));

        $this->sqlQuery = "INSERT INTO $table($columns) VALUES($v);";

        try {$this->db->prepare($this->sqlQuery)->execute(array_values($values));}
        catch (\PDOException $e) {throw new \PDOException($e->getMessage(), $e->getCode());}

    }

    public function where(array $where_data): Query
    {
        if (empty($this->whereQuery)) $this->whereQuery = ' WHERE ';
        $this->whereQuery .= implode(' AND ', $this->_formatKwargs($where_data));
        return $this;
    }

    public function _orderBy(array $order_data): Query
    {
        if (empty($this->orderQuery)) $this->orderQuery = " ORDER BY ";
        $this->orderQuery .= implode(", ", $this->_orderKwargs($order_data));
        return $this;
    }

    public function limit(int $limit, int $offset = null): Query
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return $this;
    }

    public function exec(?array $params = null): Query
    {
        $this->_where()->_order()->_limit();

        try
        {
            $this->sqlQuery = $this->db->prepare($this->sqlQuery);
            $this->sqlQuery->execute($params);

        }
        catch (\PDOException $e) {throw new \PDOException($e->getMessage());}

        $this->whereQuery = '';
        return $this;
    }

    public function or()
    {
        $this->whereQuery .= ' OR ';
        return $this;
    }

    public function fetchAll(): array
    {
        return $this->sqlQuery->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function fetch()
    {
        return $this->sqlQuery->fetch(\PDO::FETCH_ASSOC);
    }

    private function _formatKwargs(array $kwargs): array
    {
        $temp = [];
        foreach ($kwargs as $key => $args)
        {
            $temp[] = "$key='$args'";
        }
        return $temp;
    }

    private function _orderKwargs(array $kwargs): array
    {
        $temp = [];
        foreach ($kwargs as $key => $args)
        {
            $temp[] = "$key $args";
        }
        return $temp;
    }

    private function _where(): Query
    {
        if(!empty($this->whereQuery)) $this->sqlQuery .= $this->whereQuery;
        return $this;
    }

    private function _order(): Query
    {
        if (!empty($this->orderQuery)) $this->sqlQuery .= $this->orderQuery;
        return $this;
    }

    private function _limit(): Query
    {return $this;}

}
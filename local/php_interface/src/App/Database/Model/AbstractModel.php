<?php

namespace App\Database\Model;

use App\Database\Connection\AbstractConnection;

abstract class AbstractModel extends AbstractConnection
{
    protected object $query;
    public string $tableName;

    public const JOIN_TYPE_INNER = 'INNER';
    public const JOIN_TYPE_LEFT = 'LEFT';
    public const JOIN_TYPE_RIGHT = 'RIGHT';
    public const JOIN_TYPE_CROSS = 'CROSS';

    abstract public function select(array $fields): static;
    abstract public function insert(array $fields): static;
    abstract public function update(array $fields): static;
    abstract public function delete(array $fields): static;
    abstract public function sort(array $fields): static;
    abstract public function where(array $fields): static;
    abstract public function join(string $type, string $ref, string $value): static;
    abstract public function limit(int $limit): static;
    abstract public function offset(int $offset): static;

    abstract public function exec(): object;

    /**
     * @return string
     * @throws \Exception
     */
    public function getTableName(): string
    {
        throw new \Exception('You must override table name');
    }
}

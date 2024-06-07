<?php

namespace App\Database\Model;

use App\Database\Connection\AbstractConnection;

abstract class AbstractModel extends AbstractConnection
{
    protected object $query;
    public string $tableName;

    public const JOIN_TYPE_STANDARD = 'JOIN';
    public const JOIN_TYPE_INNER = 'INNER JOIN';
    public const JOIN_TYPE_LEFT = 'LEFT JOIN';
    public const JOIN_TYPE_RIGHT = 'RIGHT JOIN';
    public const JOIN_TYPE_CROSS = 'CROSS JOIN';

    abstract public function select(array $fields): static;
    abstract public function insert(array $fields): static;
    abstract public function update(array $fields): static;
    abstract public function delete(array $fields): static;
    abstract public function order(array $fields): static;
    abstract public function where(array $fields): static;
    abstract public function join(string $type, string $table, string $reference, string $value): static;
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

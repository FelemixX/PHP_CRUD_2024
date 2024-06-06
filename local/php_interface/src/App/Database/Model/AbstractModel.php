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

    /**
     * @param array $fields
     * @return void
     * @throws \Exception
     */
    protected function checkRequiredFields(array $fields): void
    {
        if (!in_array('ID', $fields)) {
            if (!in_array('*', $fields)) {
                throw new \Exception('ID must be selected');
            }
        }

        foreach ($fields as $field) {
            if (is_array($field)) {
                throw new \Exception('Array of arrays is not supported');
            }
        }
    }

    /**
     * @param array $fields
     * @param string $exception
     * @return bool
     * @throws \Exception
     */
    protected function checkAssocArray(array $fields, string $exception = ''): bool
    {
        if ($fields === []) {
            return true;
        }
        $isAssoc = array_keys($fields) === range(0, count($fields) - 1);

        if ($isAssoc) {
            return true;
        }

        throw new \Exception($exception);
    }
}

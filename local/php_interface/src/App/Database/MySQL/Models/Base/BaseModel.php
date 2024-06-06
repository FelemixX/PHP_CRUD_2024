<?php

namespace App\Database\MySQL\Models\Base;

use App\Database\MySQL\Base;
use App\Database\MySQL\Connection\Credentials;

abstract class BaseModel extends Base
{
    public function __construct()
    {
        parent::__construct(new Credentials());
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \Exception
     */
    public function select(array $fields): static
    {
        $this->checkRequiredFields($fields);

        if (is_array_assoc($fields)) {
            foreach ($fields as $alias => $field) {
                $this->query->select->fields[$alias] = $field;
            }

            return $this;
        }

        foreach ($fields as $field) {
            $this->query->select->fields[] = $field;
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \Exception
     */
    public function insert(array $fields): static
    {
        if (!is_array_assoc($fields)) {
            throw new \Exception('$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');
        }

        foreach ($fields as $alias => $field) {
            $this->query->insert->fields[$alias] = $field;
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function update(array $fields): static
    {
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function delete(array $fields): static
    {
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function where(array $fields): static
    {
        return $this;
    }

    public function join(string $tableName, string $ref, string $value): static
    {
        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static
    {
        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     */
    public function sort(array $fields): static
    {
        return $this;
    }

    /**
     * @return object|$this
     */
    public function exec(): object
    {
        return $this;
    }
}

<?php

namespace App\Database\MySQL\Models\Base;

use App\Database\Model\AbstractModel;
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


        if ($this->checkAssocArray($fields)) {
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
        $this->checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');

        foreach ($fields as $column => $value) {
            $this->query->insert->fields[$column] = $value;
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \Exception
     */
    public function update(array $fields): static
    {
        $this->checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');

        foreach ($fields as $column => $value) {
            $this->query->update->fields[$column] = $value;
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \Exception
     */
    public function delete(array $fields): static
    {
        $this->checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');

        foreach ($fields as $column => $value) {
            $this->query->delete->fields[$column] = $value;
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \Exception
     */
    public function where(array $fields): static
    {
        $this->checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');

        foreach ($fields as $column => $value) {
            $this->query->where->fields[$column] = $value;
        }

        return $this;
    }

    public function join(string $type, string $ref, string $value): static
    {
        if ($type !== static::JOIN_TYPE_CROSS || $type !== static::JOIN_TYPE_LEFT || $type !== static::JOIN_TYPE_RIGHT || $type !== static::JOIN_TYPE_INNER) {
            $class = AbstractModel::class;
            throw new \Exception("Invalid join type $type. Join type must be instance of $class");
        }

        $this->query->join->type = $type;
        $this->query->join->ref = $ref;
        $this->query->join->value = $value;

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit): static
    {
        $this->query->limit = $limit;

        return $this;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset): static
    {
        $this->query->offset = $offset;

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \Exception
     */
    public function sort(array $fields): static
    {
        $this->checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');

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

<?php

namespace App\Database\MySQL\Models\Base;

use App\Database\Model\AbstractModel;
use App\Database\MySQL\Base;
use App\Database\MySQL\Connection\Credentials;
use App\Helper\SQLDataHelper;

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
        SQLDataHelper::checkRequiredFields($fields);
        if (!empty($this->query->update->fields) || !empty($this->query->insert->fields) || !empty($this->query->delete->fields)) {
            throw new \Exception('Updating while UPDATE OR INSERT OR DELETE is active are forbidden');
        }

        if (SQLDataHelper::checkAssocArray($fields)) {
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
        SQLDataHelper::checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');
        if (!empty($this->query->select->fields) || !empty($this->query->update->fields) || !empty($this->query->delete->fields)) {
            throw new \Exception('Updating while SELECT OR UPDATE OR DELETE is active are forbidden');
        }

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
        SQLDataHelper::checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');
        if (!empty($this->query->select->fields) || !empty($this->query->insert->fields) || !empty($this->query->delete->fields)) {
            throw new \Exception('Updating while SELECT OR INSERT OR DELETE is active are forbidden');
        }

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
        SQLDataHelper::checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');
        if (!empty($this->query->select->fields) || !empty($this->query->insert->fields) || !empty($this->query->update->fields)) {
            throw new \Exception('Updating while SELECT OR INSERT OR UPDATE is active are forbidden');
        }

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
        SQLDataHelper::checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');

        foreach ($fields as $column => $value) {
            $this->query->where->fields[$column] = $value;
        }

        return $this;
    }

    public function join(string $type, string $ref, string $value): static
    {
        if ($type !== AbstractModel::JOIN_TYPE_CROSS && $type !== AbstractModel::JOIN_TYPE_LEFT && $type !== AbstractModel::JOIN_TYPE_RIGHT && $type !== AbstractModel::JOIN_TYPE_INNER) {
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
        SQLDataHelper::checkAssocArray($fields, '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]');

        foreach ($fields as $column => $value) {
            $this->query->sort->fields[$column] = $value;
        }

        return $this;
    }

    /**
     * @return object
     * @throws \Exception
     */
    public function exec(): object
    {
        $sql = $this->generateSql();

        return $this->connection->query($sql);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateSql(): string
    {
        $sql = '';

        if (!empty($this->query->select->fields)) {
            $sql = $this->generateSqlCaseSelect();
        }

        return $sql;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateSqlCaseSelect(): string
    {
        $sqlGenerator = new SQLGenerator($this->query, $this->tableName);
        $sql = '';

        $selectSql = $sqlGenerator->generateSelect();
        $limitSql = $sqlGenerator->generateLimit();
        $sql .= $selectSql . $limitSql;

        return $sql;
    }
}

<?php

namespace App\Database\MySQL\Models\Base;

use App\Database\Model\AbstractModel;
use App\Database\MySQL\Base;
use App\Database\MySQL\Connection\Credentials;
use App\Helper\SQLDataHelper;

abstract class BaseModel extends Base
{
    private mixed $dbResult;

    public function __construct()
    {
        parent::__construct(new Credentials());

        $this->query = $this->instantiateQuery();
    }

    private function instantiateQuery(): object
    {
        $query = [
            'select' => (object)['fields' => []],
            'insert' => (object)['fields' => []],
            'update' => (object)['fields' => []],
            'delete' => (object)['fields' => []],
            'where' => (object)['fields' => []],
            'sort' => (object)['fields' => []],
            'join' => [],
            'limit' => 0,
            'offset' => 0,
        ];

        return (object)$query;
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
            throw new \InvalidArgumentException('Updating while UPDATE OR INSERT OR DELETE is active are forbidden');
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
     * @throws \InvalidArgumentException
     */
    public function insert(array $fields): static
    {
        SQLDataHelper::checkAssocArray(
            $fields,
            '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]'
        );
        if (!empty($this->query->select->fields) || !empty($this->query->update->fields) || !empty($this->query->delete->fields)) {
            throw new \InvalidArgumentException('Updating while SELECT OR UPDATE OR DELETE is active are forbidden');
        }

        foreach ($fields as $column => $value) {
            $this->query->insert->fields[$column] = $value;
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function update(array $fields): static
    {
        SQLDataHelper::checkAssocArray(
            $fields,
            '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]'
        );
        if (!empty($this->query->select->fields) || !empty($this->query->insert->fields) || !empty($this->query->delete->fields)) {
            throw new \InvalidArgumentException('Updating while SELECT OR INSERT OR DELETE is active are forbidden');
        }

        foreach ($fields as $column => $value) {
            $this->query->update->fields[$column] = $value;
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function delete(array $fields): static
    {
        SQLDataHelper::checkAssocArray(
            $fields,
            '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]'
        );
        if (!empty($this->query->select->fields) || !empty($this->query->insert->fields) || !empty($this->query->update->fields)) {
            throw new \InvalidArgumentException('Updating while SELECT OR INSERT OR UPDATE is active are forbidden');
        }

        foreach ($fields as $column => $value) {
            $this->query->delete->fields[$column] = $value;
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function where(array $fields): static
    {
        SQLDataHelper::checkAssocArray(
            $fields,
            '$fields must be assoc array. $fields = ["column1" => "value1", "column2" => "value2", "column3" => "value3"]'
        );

        foreach ($fields as $column => $value) {
            $this->query->where->fields[$column] = $value;
        }

        return $this;
    }

    public function join(string $type, string $table, string $reference, string $value): static
    {
        if (!in_array($type, (new \ReflectionClass(AbstractModel::class))->getConstants())) {
            throw new \InvalidArgumentException('Join type must be instance of ' . AbstractModel::class);
        }

        $this->query->join[] = [
            'type' => $type,
            'table' => $table,
            'reference' => $reference,
            'value' => $value,
        ];

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
     * @throws \InvalidArgumentException
     */
    public function order(array $fields): static
    {
        if (SQLDataHelper::checkAssocArray($fields)) {
            foreach ($fields as $column => $value) {
                $this->query->sort->fields[$column] = $value;
            }
        } else {
            foreach ($fields as $value) {
                $this->query->sort->fields[] = $value;
            }
        }

        return $this;
    }

    /**
     * @return object
     * @throws \InvalidArgumentException
     */
    public function exec(): object
    {
        $sql = $this->generateSql();

        if (!empty($this->query->insert->fields) || !empty($this->query->delete->fields) || !empty($this->query->update->fields)) {
            $this->executeBindingQuery($sql);

            return $this;
        }

        if (!empty($this->query->where->fields)) {
            $query = $this->connection->prepare($sql);
            $filterValues = array_values($this->query->where->fields);
            $filterValues = array_map(fn($value): string => empty($value) ? 'NULL' : $value, $filterValues);

            $this->dbResult = $query->execute($filterValues);

            return $this;
        }

        $this->dbResult = $this->connection->query($sql);

        return $this;
    }

    /**
     * @param string $sql
     * @return void
     * @throws \InvalidArgumentException
     */
    private function executeBindingQuery(string $sql): void
    {
        $db = $this->connection->prepare($sql);
        switch ($this->query) {
            case !empty($this->query->insert->fields):
                $db->execute(array_values($this->query->insert->fields));
                break;
            case !empty($this->query->delete->fields):
                $db->execute(array_values($this->query->delete->fields));
                break;
            case !empty($this->query->update->fields):
                if (empty($this->query->where->fields)) {
                    throw new \InvalidArgumentException('Updating without condition is forbidden');
                }
                $db->execute(array_values($this->query->update->fields));
                break;
        }

        $this->dbResult = $db;
    }

    /**
     * @return mixed
     */
    public function get(): mixed
    {
        $this->query = $this->instantiateQuery();

        return $this->dbResult;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateSql(): string
    {
        if (!empty($this->query->select->fields)) {
            return $this->generateSqlCaseSelect();
        }

        return $this->generateSqlCaseModify();
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateSqlCaseSelect(): string
    {
        $sqlGenerator = new SQLGenerator($this->query, $this->tableName);
        $sql = '';

        $selectSql = $sqlGenerator->generateSelect();
        $joinSql = $sqlGenerator->generateJoin();
        $limitSql = $sqlGenerator->generateLimit();
        $offsetSql = $sqlGenerator->generateOffset();
        $whereSql = $sqlGenerator->generateWhere();
        $groupSql = $sqlGenerator->generateOrder();

        $sql .= $selectSql . $joinSql . $whereSql . $groupSql . $limitSql . $offsetSql;

        return $sql;
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateSqlCaseModify(): string
    {
        $sqlGenerator = new SQLGenerator($this->query, $this->tableName);

        switch ($this->query) {
            case !empty($this->query->insert->fields):
                $sql = $sqlGenerator->generateInsert();
                break;
            case !empty($this->query->delete->fields):
                $sql = $sqlGenerator->generateDelete();
                break;
            case !empty($this->query->update->fields):
                $sql = $sqlGenerator->generateUpdate();
                break;
        }

        return $sql;
    }
}

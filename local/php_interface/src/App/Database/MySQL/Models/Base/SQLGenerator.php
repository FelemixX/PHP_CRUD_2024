<?php

namespace App\Database\MySQL\Models\Base;

use App\Helper\SQLDataHelper;

final readonly class SQLGenerator
{
    final public function __construct(private object $query, private string $tableName)
    {
    }

    /**
     * @return string
     * @throws \Exception
     */
    final public function generateSelect(): string
    {
        $sql = 'SELECT ';
        $from = ' FROM ' . $this->tableName;

        $joinIsEmpty = empty($this->query->join);
        if (SQLDataHelper::checkAssocArray($this->query->select->fields)) {
            $selectFields = [];
            foreach ($this->query->select->fields as $field => $value) {
                $as = strval($field);

                if (!$joinIsEmpty && !preg_match('/^\w+\./', $value)) { //Используем JOIN и в названии поля не указано названии таблицы. В таком случае по умолчанию считаем, что забираем данные не из присоединенной таблицы
                    $selectFields[] = is_numeric($field) ? "$this->tableName.$value" : "$this->tableName.$value AS $as";
                } else {
                    $selectFields[] = is_numeric($field) ? "$value" : "$value AS $as";
                }
            }

            $selection = implode(', ', $selectFields);

            return $sql . $selection . $from;
        }

        $selectFields = [];
        foreach ($this->query->select->fields as $value) {
            $selectFields[] = !preg_match('/^\w+\./', $value) ? "$this->tableName.$value" : $value;
        }
        $selection = implode(', ', $selectFields);

        return $sql . $selection . $from;
    }

    /**
     * @return string
     */
    final public function generateInsert(): string
    {
        $sql = "INSERT INTO $this->tableName ";

        $fields = implode(', ', array_keys($this->query->insert->fields));

        $values = str_repeat('?, ', count(array_values((array)$this->query->insert->fields))); //Чтобы использовать prepare и добавлять данные безопасно
        $values = rtrim($values, ', ');

        $sql .= "($fields) VALUES ($values)";

        return $sql;
    }

    /**
     * @return string
     * @throws \Exception
     */
    final public function generateDelete(): string
    {
        $sql = "DELETE FROM $this->tableName WHERE ";

        $sql .= SQLDataHelper::generateBindConditionsString($this->query->delete->fields);

        return $sql;
    }

    /**
     * @return string
     * @throws \Exception
     */
    final public function generateUpdate(): string
    {
        $sql = "UPDATE $this->tableName SET ";

        $fields = implode(' = ?, ', array_keys($this->query->update->fields)) . ' = ?';
        $conditionsString = SQLDataHelper::generateConditionsString($this->query->where->fields);

        $sql .= $fields . ' WHERE ' . $conditionsString;

        return $sql;
    }

    /**
     * @return string
     */
    final public function generateJoin(): string
    {
        $sql = ' ';
        $joinsArray = [];

        foreach ($this->query->join as $params) {
            $joinsArray[] = "{$params['type']} {$params['table']} ON {$params['value']} = {$params['reference']}";
        }

        $joinSql = implode(' ', $joinsArray);

        return $sql . $joinSql;
    }

    /**
     * @throws \Exception
     */
    final public function generateWhere(): string
    {
        if (empty($this->query->where->fields)) {
            return '';
        }
        $sql = ' WHERE ';
        $joinIsEmpty = empty($this->query->join);

        $conditionsArray = [];
        foreach ($this->query->where->fields as $condition => $conditionRight) {
            $conditionOperator = SQLDataHelper::parseConditionOperator($condition);
            if (!$conditionOperator) {
                throw new \InvalidArgumentException('Condition operator is not valid');
            }

            $conditionLeft = ltrim($condition, $conditionOperator);
            $replacedOperator = match ($conditionOperator) {
                '!', '!=' => '<>',  //Обратная совместимость, все дела
                default => $conditionOperator,
            };
            if (empty($this->query->select->fields)) {
                $conditionsArray[] = !$joinIsEmpty && !preg_match('/^\w+\./', $conditionLeft) ? "$this->tableName.$conditionLeft $replacedOperator ?" : "$conditionLeft $replacedOperator ?";
            } else {
                $conditionsArray[] = !$joinIsEmpty && !preg_match('/^\w+\./', $conditionLeft) ? "$this->tableName.$conditionLeft $replacedOperator $conditionRight" : "$conditionLeft $replacedOperator $conditionRight";
            }
        }

        $conditionsString = implode(' AND ', $conditionsArray);

        $sql .= $conditionsString;

        return $sql;
    }

    /**
     * @return string
     * @throws \Exception
     */
    final public function generateOrder(): string
    {
        $joinIsEmpty = empty($this->query->join);

        if (empty($this->query->sort->fields)) {
            return !$joinIsEmpty ? " ORDER BY $this->tableName.ID ASC" : " ORDER BY ID ASC ";
        }

        $sql = ' ORDER BY ';
        $ordersArray = [];

        if (SQLDataHelper::checkAssocArray($this->query->sort->fields)) {
            foreach ($this->query->sort->fields as $field => $order) {
                $ordersArray[] = "$field $order";
            }
        } else {
            foreach ($this->query->sort->fields as $order) {
                $ordersArray[] = !$joinIsEmpty && !preg_match('/^\w+\./', $order) ? "$this->tableName.$order" : $order;
            }
        }

        $orderSql = implode(', ', $ordersArray);

        return $sql . $orderSql;
    }

    /**
     * @return string
     */
    final public function generateLimit(): string
    {
        if ($this->query->limit === 0) {
            return '';
        }

        $sql = ' LIMIT ' . $this->query->limit;

        return $sql;
    }

    /**
     * @return string
     */
    final public function generateOffset(): string
    {
        if ($this->query->offset === 0) {
            return '';
        }

        $sql = ' OFFSET ' . $this->query->offset;

        return $sql;
    }
}

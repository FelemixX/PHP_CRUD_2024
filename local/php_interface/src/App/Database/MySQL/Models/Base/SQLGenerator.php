<?php

namespace App\Database\MySQL\Models\Base;

use App\Helper\SQLDataHelper;

final readonly class SQLGenerator
{
    final public function __construct(private object $query, private string $tableName) {}

    /**
     * @return string
     * @throws \Exception
     */
    final public function generateSelect(): string
    {
        $sql = 'SELECT ';
        $from = ' FROM ' . $this->tableName;

        if (SQLDataHelper::checkAssocArray($this->query->select->fields)) {
            $selectFields = [];
            foreach ($this->query->select->fields as $field => $value) {
                $as = strval($field);

                $selectFields[] = is_numeric($field) ? $value : "$value AS $as";
            }

            $selection = implode(', ', $selectFields);

            return $sql . $selection . $from;
        }

        $selection = implode(', ', $this->query->select->fields);

        return $sql . $selection . $from;
    }

    /**
     * @throws \Exception
     */
    final public function generateWhere(): string
    {
        $sql = ' WHERE ';

        $conditionsArray = [];
        foreach ($this->query->where->fields as $condition => $conditionRight) {

            $conditionOperator = SQLDataHelper::parseConditionOperator($condition);
            if (!$conditionOperator) {
                throw new \Exception('Condition operator is not valid');
            }

            if (empty($conditionRight)) {
                $conditionRight = 'null';
            }

            $conditionLeft = ltrim($condition, $conditionOperator);
            $replacedOperator = match($conditionOperator) {
                '!', '!=' => '<>',  //Обратная совместимость, все дела
                default => $conditionOperator,
            };

            $conditionsArray[] = "$conditionLeft $replacedOperator $conditionRight";
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
        if (empty($this->query->sort->fields)) {
            return ' ORDER BY ID ASC';
        }

        $sql = ' ORDER BY ';

        $ordersArray = [];
        if (SQLDataHelper::checkAssocArray($this->query->sort->fields)) {
            foreach ($this->query->sort->fields as $field => $order) {
                $ordersArray[] = "$field $order";
            }
        } else {
            foreach ($this->query->sort->fields as $order) {
                $ordersArray[] = "$order";
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

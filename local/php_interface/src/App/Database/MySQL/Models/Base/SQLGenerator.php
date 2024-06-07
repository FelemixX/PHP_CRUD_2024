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

        $joinIsEmpty = empty($this->query->join);
        if (SQLDataHelper::checkAssocArray($this->query->select->fields)) {
            $selectFields = [];
            foreach ($this->query->select->fields as $field => $value) {
                $as = strval($field);

                if (!$joinIsEmpty && !preg_match('/\w+\./', $value)) { //Используем JOIN и в названии поля не указано названии таблицы. В таком случае по умолчанию считаем, что забираем данные не из присоединенной таблицы
                    $selectFields[] = is_numeric($field) ? $this->tableName . ".$value" : $this->tableName . ".$value AS $as";
                } else {
                    $selectFields[] = is_numeric($field) ? $value : $this->tableName . "$value AS $as";
                }
            }

            $selection = implode(', ', $selectFields);

            return $sql . $selection . $from;
        }

        $selection = implode(', ', $this->query->select->fields);

        return $sql . $selection . $from;
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
        $sql = ' WHERE ';
        $joinIsEmpty = empty($this->query->join);

        $conditionsArray = [];
        foreach ($this->query->where->fields as $condition => $conditionRight) {

            $conditionOperator = SQLDataHelper::parseConditionOperator($condition);
            if (!$conditionOperator) {
                throw new \Exception('Condition operator is not valid');
            }

            if (empty($conditionRight)) {
                $conditionRight = 'NULL';
            }

            $conditionLeft = ltrim($condition, $conditionOperator);
            $replacedOperator = match($conditionOperator) {
                '!', '!=' => '<>',  //Обратная совместимость, все дела
                default => $conditionOperator,
            };

            $conditionsArray[] = !$joinIsEmpty && !preg_match('/\w+\./', $conditionLeft) ? "$this->tableName.$conditionLeft $replacedOperator $conditionRight" : "$conditionLeft $replacedOperator $conditionRight";
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
                $ordersArray[] = !$joinIsEmpty && !preg_match('/\w+\./', $order) ? "$this->tableName.$field $order" : "$field $order";
            }
        } else {
            foreach ($this->query->sort->fields as $order) {
                $ordersArray[] = !$joinIsEmpty && !preg_match('/\w+\./', $order) ? "$this->tableName.$order" : $order;
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

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
        $sql = 'SELECT';
        $from = ' FROM ' . $this->tableName;

        if (SQLDataHelper::checkAssocArray($this->query->select->fields)) {
            $lastKey = array_key_last($this->query->select->fields);
            foreach ($this->query->select->fields as $field => $value) {
                $as = strval($field);

                if ($field === $lastKey) {
                    if (is_numeric($field)) {
                        $sql .= " $value";
                    } else {
                        $sql .= " $value AS $as";
                    }
                } else {
                    if (is_numeric($field)) {
                        $sql .= " $value,";
                    } else {
                        $sql .= " $value AS $as,";
                    }
                }
            }

            return $sql . $from;
        }

        $lastKey = array_key_last($this->query->select->fields);
        foreach ($this->query->select->fields as $key => $field) {
            if ($key === $lastKey) {
                $sql .= " $field";
            } else {
                $sql .= " $field,";
            }
        }

        return $sql . $from;
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
}

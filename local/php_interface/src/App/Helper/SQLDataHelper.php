<?php

namespace App\Helper;

class SQLDataHelper
{
    /**
     * @param array $fields
     * @return void
     * @throws \Exception
     */
    public static function checkRequiredFields(array $fields): void
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
    public static function checkAssocArray(array $fields, string $exception = ''): bool
    {
        if ($fields === []) {
            return false;
        }

        $isAssoc = array_keys($fields) !== range(0, count($fields) - 1);

        if ($isAssoc) {
            return true;
        }

        if (mb_strlen($exception)) {
            throw new \Exception($exception);
        }
        return false;
    }

    /**
     * @param string $condition
     * @return false|string
     */
    public static function parseConditionOperator(string $condition): false|string
    {
        switch ($condition) {
            case str_contains($condition, '>'):
                $condOperator = '>';
                break;
            case str_contains($condition, '<'):
                $condOperator = '<';
                break;
            case str_contains($condition, '<>'):
                $condOperator = '<>';
                break;
            case str_contains($condition, '!='):
                $condOperator = '!=';
                break;
            case str_contains($condition, '!'):
                $condOperator = '!';
                break;
            case str_contains($condition, '<='):
                $condOperator = '<=';
                break;
            case str_contains($condition, '>='):
                $condOperator = '>=';
                break;
            case str_contains($condition, '='):
                $condOperator = '=';
                break;
            case str_contains($condition, 'BETWEEN '):
                $condOperator = 'BETWEEN';
                break;
            case str_contains($condition, 'LIKE '):
                $condOperator = 'LIKE';
                break;
            case str_contains($condition, 'IN '):
                $condOperator = 'IN';
                break;
            default:
                $condOperator = false;
        }

        return $condOperator;
    }

    /**
     * @param array $conditions
     * @return string
     * @throws \Exception
     */
    public static function generateBindConditionsString(array $conditions): string
    {
        $conditionsArray = [];
        foreach ($conditions as $condition => $conditionRight) {
            $conditionOperator = static::parseConditionOperator($condition);
            if (!$conditionOperator) {
                throw new \Exception('Condition operator is not valid');
            }

            $conditionLeft = ltrim($condition, $conditionOperator);
            $replacedOperator = match ($conditionOperator) {
                '!', '!=' => '<>',  //Обратная совместимость, все дела
                default => $conditionOperator,
            };

            $conditionsArray[] = "$conditionLeft $replacedOperator ?";
        }

        return implode(' AND ', $conditionsArray);
    }

    /**
     * @param array $conditions
     * @return string
     * @throws \Exception
     */
    public static function generateConditionsString(array $conditions): string
    {
        $conditionsArray = [];
        foreach ($conditions as $condition => $conditionRight) {
            $conditionOperator = static::parseConditionOperator($condition);
            if (!$conditionOperator) {
                throw new \Exception('Condition operator is not valid');
            }

            $conditionLeft = ltrim($condition, $conditionOperator);
            $replacedOperator = match ($conditionOperator) {
                '!', '!=' => '<>',  //Обратная совместимость, все дела
                default => $conditionOperator,
            };

            $conditionsArray[] = "$conditionLeft $replacedOperator '$conditionRight'";
        }

        return implode(' AND ', $conditionsArray);
    }
}

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
}

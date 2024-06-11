<?php

namespace App\Database\MySQL\Models;

use App\Database\MySQL\Models\Base\BaseModel;

class UnifiedModel extends BaseModel
{
    public static function getTableName(): string
    {
        return '';
    }
}

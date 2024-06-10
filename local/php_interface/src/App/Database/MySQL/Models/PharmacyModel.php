<?php

namespace App\Database\MySQL\Models;

use App\Database\MySQL\Models\Base\BaseModel;

class PharmacyModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        return 'pharmacy';
    }
}

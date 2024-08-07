<?php

namespace App\Database\MySQL\Models;

use App\Database\MySQL\Models\Base\BaseModel;

class ProductModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        return 'product';
    }
}

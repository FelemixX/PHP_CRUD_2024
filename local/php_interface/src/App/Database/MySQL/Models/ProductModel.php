<?php

namespace App\Database\MySQL\Models;

use App\Database\MySQL\Models\Base\BaseModel;

class ProductModel extends BaseModel
{
    public function getTableName(): string
    {
        return 'product';
    }
}

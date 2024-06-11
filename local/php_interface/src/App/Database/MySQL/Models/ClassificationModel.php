<?php

namespace App\Database\MySQL\Models;

use App\Database\MySQL\Models\Base\BaseModel;

class ClassificationModel extends BaseModel
{
    /**
     * @inheritDoc
     */
    public static function getTableName(): string
    {
        return 'classification_of_pharmacy_products';
    }
}

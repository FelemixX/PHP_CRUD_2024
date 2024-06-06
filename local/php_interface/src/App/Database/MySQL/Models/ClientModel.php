<?php

namespace App\Database\MySQL\Models;

use App\Database\MySQL\Models\Base\BaseModel;

class ClientModel extends BaseModel
{
    public function getTableName(): string
    {
        return 'client';
    }
}

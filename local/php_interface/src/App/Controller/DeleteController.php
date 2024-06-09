<?php

namespace App\Controller;

use App\Database\MySQL\Models\Base\BaseModel;

class DeleteController extends Base
{
    public function process(): ?int
    {
        return $this->model->delete([
            '=ID' => $this->id
        ])
            ->exec()
            ->get()
            ->rowCount();
    }
}

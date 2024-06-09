<?php

namespace App\Controller;

use App\Database\MySQL\Models\Base\BaseModel;

class UpdateController extends Base
{
    public function process(): ?int
    {
        return $this->model->update($this->fields)
            ->where(['=ID' => $this->id])
            ->exec()
            ->get()
            ->rowCount();
    }
}

<?php

namespace App\Controller\Simple;

use App\Controller\Base;

class UpdateController extends Base
{
    /**
     * @return int|null
     */
    public function process(): ?int
    {
        return $this->model->update($this->fields)
            ->where(['=ID' => $this->id])
            ->exec()
            ->get()
            ->rowCount();
    }
}

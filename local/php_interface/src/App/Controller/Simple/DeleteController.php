<?php

namespace App\Controller\Simple;

use App\Controller\Base;

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

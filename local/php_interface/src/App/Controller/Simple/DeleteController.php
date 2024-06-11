<?php

namespace App\Controller\Simple;

use App\Controller\Base;

class DeleteController extends Base
{
    public function process(): ?int
    {
        foreach ($this->fields as $idx => $field) {
            $this->fields[$this->model::getTableName() . '.' . $idx] = $field;
            unset($this->fields[$idx]);
        }

        return $this->model->delete([
            '=ID' => $this->id
        ])
            ->exec()
            ->get()
            ->rowCount();
    }
}

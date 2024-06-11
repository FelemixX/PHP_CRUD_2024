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
        foreach ($this->fields as $idx => $field) {
            $this->fields[$this->model::getTableName() . '.' . $idx] = $field;
            unset($this->fields[$idx]);
        }

        return $this->model->update($this->fields)
            ->where(['=ID' => $this->id])
            ->exec()
            ->get()
            ->rowCount();
    }
}

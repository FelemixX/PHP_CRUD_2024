<?php

namespace App\Controller\Simple;

use App\Controller\Base;
use App\Database\MySQL\Models\Base\BaseModel;

class CreateController extends Base
{
    /**
     * @param object $requestValues
     * @param BaseModel $model
     */
    public function __construct(protected object $requestValues, protected BaseModel $model)
    {
        $this->fields = $this->prepareFields((array)$this->requestValues);
        foreach ($this->fields as $idx => $field) {
            $this->fields[$this->model::getTableName() . '.' . $idx] = $field;
            unset($this->fields[$idx]);
        }

        if (empty($this->fields)) {
            throw new \InvalidArgumentException('Fields can not be empty');
        }
    }

    /**
     * @return int|null
     */
    public function process(): ?int
    {
        return $this->model->insert($this->fields)
            ->exec()
            ->get()
            ->rowCount();
    }
}

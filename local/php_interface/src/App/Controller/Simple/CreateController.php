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

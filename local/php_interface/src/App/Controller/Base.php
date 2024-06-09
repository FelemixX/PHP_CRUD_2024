<?php

namespace App\Controller;

use App\Database\MySQL\Models\Base\BaseModel;

abstract class Base
{
    protected int $id;
    protected array $fields;

    abstract public function process();

    public function __construct(protected object $requestValues, protected BaseModel $model)
    {
        if (!$this->requestValues?->id && !$this->requestValues?->ID && $this->requestValues) {
            throw new \Exception('No id passed');
        }

        $this->id = $this->requestValues->id ?: $this->requestValues->ID;
        $this->fields = $this->prepareFields((array)$this->requestValues);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    protected function prepareFields(array $fields): array
    {
        foreach ($fields as $key => $field) {
            if (strtolower($key) == 'id' || strtolower($key) == 'action') {
                unset ($fields[$key]);
            }
        }

        return $fields;
    }
}

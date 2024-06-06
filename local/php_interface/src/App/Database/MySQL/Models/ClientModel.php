<?php

namespace App\Database\MySQL\Models;

use App\Database\MySQL\Base;
use App\Database\MySQL\Connection\Credentials;

class ClientModel extends Base
{
    public function __construct()
    {
        parent::__construct(new Credentials());

        $this->tableName = $this->getTableName();
    }

    public function getTableName(): string
    {
        return 'client';
    }

    public function select(): static
    {
        return $this;
    }

    public function insert(): static
    {
        return $this;
    }

    public function update(): static
    {
        return $this;
    }

    public function delete(): static
    {
        return $this;
    }

    public function exec(): object
    {
        return $this;
    }
}

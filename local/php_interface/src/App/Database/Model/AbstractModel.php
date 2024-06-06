<?php

namespace App\Database\Model;

use App\Database\Connection\AbstractConnection;

abstract class AbstractModel extends AbstractConnection
{
    protected object $query;

    abstract public function select(): static;
    abstract public function insert(): static;
    abstract public function update(): static;
    abstract public function delete(): static;
    abstract public function exec(): object;
}

<?php

namespace App\Database\Connection;

abstract class AbstractConnection
{
    protected string $host;
    protected string $username;
    protected string $password;
    protected string $database;
    protected string $port;
    protected object $connection;
}

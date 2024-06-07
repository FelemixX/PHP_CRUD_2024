<?php

namespace App\Database\MySQL\Connection;

readonly class Credentials
{
    public function __construct(
        public string $host = 'php_crud_db',
        public string $username = 'bitrix',
        public string $password = '123',
        public string $database = 'bitrix',
        public string $port = '3306'
    ) {
    }
}

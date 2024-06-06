<?php

namespace App\Database\MySQL;

use App\Database\Model\AbstractModel;
use App\Database\MySQL\Connection\Credentials;

abstract class Base extends AbstractModel
{
    /**
     * @param Credentials $credentials
     */
    public function __construct(Credentials $credentials)
    {
        $this->host = $credentials->host;
        $this->username = $credentials->username;
        $this->password = $credentials->password;
        $this->database = $credentials->database;
        $this->port = $credentials->port;
        $this->connection = $this->createConnection();
    }

    /**
     * @return \PDO
     */
    private function createConnection(): \PDO
    {
        try {
            $connectionString = "mysql:host=$this->host;port=$this->port;dbname=$this->database";

            return new \PDO($connectionString, $this->username, $this->password);
        } catch (\PDOException $pdoException) {
            $this->handleConnectionError($pdoException);
        }
    }

    /**
     * @param \Throwable $exception
     * @return never
     */
    private function handleConnectionError(\Throwable $exception): never
    {
        $curDate = date('Y-m-d');
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/log/database/db_error_$curDate.log", "\n" . date('d-m-Y H:i:s', time()) . ' ' . __FILE__ . ':' . __LINE__ . ' : ' . "\n" . var_export($exception->getMessage(),true) . "\n-------------------\n", FILE_APPEND);
        die('Something went wrong with the database connection');
    }
}

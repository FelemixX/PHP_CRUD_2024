<?php

namespace App\Database\MySQL;

use App\Database\Model\AbstractModel;
use App\Database\MySQL\Connection\Credentials;

abstract class Base extends AbstractModel
{
    /**
     * @param Credentials $credentials
     * @throws \Exception
     */
    public function __construct(Credentials $credentials)
    {
        $this->host = $credentials->host;
        $this->username = $credentials->username;
        $this->password = $credentials->password;
        $this->database = $credentials->database;
        $this->port = $credentials->port;
        $this->connection = $this->createConnection();
        $this->tableName = $this->getTableName();

        $this->query = $this->instantiateQuery();
    }

    private function instantiateQuery(): object
    {
        $query = [
            'select' => (object)['fields' => []],
            'insert' => (object)['fields' => []],
            'update' => (object)['fields' => []],
            'delete' => (object)['fields' => []],
            'where' => (object)['fields' => []],
            'join' => (object)['type' => '', 'ref' => ''],
            'limit' => 0,
            'offset' => 0,
        ];

        return (object)$query;
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

        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/log/database/$curDate/db_error_.log", "\n" . date('d-m-Y H:i:s', time()) . ' ' . __FILE__ . ':' . __LINE__ . ' : ' . "\n" . var_export($exception->getMessage(),true) . "\n-------------------\n", FILE_APPEND);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/local/log/database/$curDate/db_error_.log", "\n" . date('d-m-Y H:i:s', time()) . ' ' . __FILE__ . ':' . __LINE__ . ' : ' . "\n" . var_export($exception->getTraceAsString(),true) . "\n-------------------\n", FILE_APPEND);

        die('<div class="container mx-auto my-auto text-danger text-uppercase text-center border border-danger">Something went wrong with the database connection</div>');
    }
}

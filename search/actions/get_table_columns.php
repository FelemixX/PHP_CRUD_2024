<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/vendor/autoload.php');

use App\Database\MySQL\Models\UnifiedModel;
use App\Response\Interface\ResponseTypesInterface;
use App\Response\ResponseFactory;

$response = ResponseFactory::create(ResponseTypesInterface::JSON);

if ($_SERVER['REQUEST_METHOD'] !== 'GET' && !mb_strlen($_GET['tableName'])) {
    $response->setStatusCode(400);
    $response->setSuccess(false);
    $response->setMessage('Request method is not supported');
    $response->send();
    die();
}

$tableName = htmlspecialchars_decode(trim($_GET['tableName']));

try {
    $unifiedModel = new UnifiedModel();
    $tableColumns = array_column($unifiedModel->executeQueryByString("DESCRIBE $tableName")->get()->fetchAll(\PDO::FETCH_ASSOC), 'Field');

    $response = ResponseFactory::create(ResponseTypesInterface::JSON);
    $response->setStatusCode(200);
    $response->setSuccess(true);
    $response->setData($tableColumns);
    $response->send();
} catch (Throwable) {
    $response = ResponseFactory::create(ResponseTypesInterface::JSON);
    $response->setStatusCode(500);
    $response->setSuccess(false);
    $response->setMessage('Something went wrong');
    $response->send();
}

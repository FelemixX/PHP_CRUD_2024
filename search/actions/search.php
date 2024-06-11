<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/vendor/autoload.php');

use App\Database\MySQL\Models\UnifiedModel;
use App\Response\Interface\ResponseTypesInterface;
use App\Response\ResponseFactory;

$response = ResponseFactory::create(ResponseTypesInterface::JSON);

if ($_SERVER['REQUEST_METHOD'] !== 'GET' && !mb_strlen($_GET['query']) && !mb_strlen($_GET['tableName']) && !mb_strlen($_GET['tableColumn'])) {
    $response->setStatusCode(400);
    $response->setSuccess(false);
    $response->setMessage('Request method is not supported');
    $response->send();
    die();
}

$query = htmlspecialchars_decode(trim($_GET['query']));
$tableName = htmlspecialchars_decode(trim($_GET['tableName']));
$tableColumn = htmlspecialchars_decode(trim($_GET['tableColumn']));

try {
    $unifiedModel = new UnifiedModel();
    $data = $unifiedModel
        ->executeQueryByString("SELECT * FROM $tableName WHERE $tableColumn LIKE '%$query%'")
        ->get()
        ->fetchAll(\PDO::FETCH_ASSOC);

    $result = [
        'columns' => array_keys((array)$data[array_key_first((array)$data)]),
        'items' => $data,
    ];

    $response = ResponseFactory::create(ResponseTypesInterface::JSON);
    $response->setStatusCode(200);
    $response->setSuccess(true);
    $response->setData($result);
    $response->send();
} catch (Throwable) {
    $response = ResponseFactory::create(ResponseTypesInterface::JSON);
    $response->setStatusCode(500);
    $response->setSuccess(false);
    $response->setMessage('Something went wrong');
    $response->send();
}

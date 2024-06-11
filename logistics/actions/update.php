<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/vendor/autoload.php');

use App\Controller\Simple\UpdateController;
use App\Database\MySQL\Models\ClientModel;
use App\Response\Interface\ResponseTypesInterface;
use App\Response\ResponseFactory;

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    $response = ResponseFactory::create(ResponseTypesInterface::JSON);
    $response->setStatusCode(400);
    $response->setSuccess(false);
    $response->setMessage('Request method is not supported');
    $response->send();
    die();
}

$input = file_get_contents('php://input');
$data = json_decode($input);

try {
    $updateController = new UpdateController($data, new \App\Database\MySQL\Models\LogisticsModel());
    $response = ResponseFactory::create(ResponseTypesInterface::JSON);

    $result = $updateController->process();
    if (!$result) {
        $response->setStatusCode(409);
        $response->setSuccess(true);
        $response->setMessage('Update failed');
        $response->send();
    }

    $response->setStatusCode(200);
    $response->setSuccess(true);
    $response->setData(['updatedRowsCount' => $result]);
    $response->send();
} catch (Throwable $e) {
    $response = ResponseFactory::create(ResponseTypesInterface::JSON);
    $response->setStatusCode(500);
    $response->setSuccess(false);
    $response->setMessage('Something went wrong');
    $response->send();
}

<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/vendor/autoload.php');

use App\Controller\Simple\DeleteController;
use App\Response\Interface\ResponseTypesInterface;
use App\Response\ResponseFactory;

$response = ResponseFactory::create(ResponseTypesInterface::JSON);

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    $response->setStatusCode(400);
    $response->setSuccess(false);
    $response->setMessage('Request method is not supported');
    $response->send();
    die();
}

$input = file_get_contents('php://input');
$data = json_decode($input);

try {
    $deleteController = new DeleteController($data, new \App\Database\MySQL\Models\ClassificationModel());
    $result = $deleteController->process();
    if (!$result) {
        $response->setStatusCode(409);
        $response->setSuccess(true);
        $response->setMessage('Deletion failed');
        $response->send();
    }

    $response->setStatusCode(200);
    $response->setSuccess(true);
    $response->setData(['deletedRowsCount' => $result]);
    $response->send();
} catch (Throwable $e) {
    $response = ResponseFactory::create(ResponseTypesInterface::JSON);
    $response->setStatusCode(500);
    $response->setSuccess(false);
    $response->setMessage('Something went wrong');
    $response->send();
}

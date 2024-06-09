<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/vendor/autoload.php');

use App\Controller\Simple\CreateController;
use App\Database\MySQL\Models\ClientModel;
use App\Response\Interface\ResponseTypesInterface;
use App\Response\ResponseFactory;

$response = ResponseFactory::create(ResponseTypesInterface::JSON);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response->setStatusCode(400);
    $response->setSuccess(false);
    $response->setMessage('Request method is not supported');
    $response->send();
    die();
}

$input = file_get_contents('php://input');
$data = json_decode($input);

try {
    $createController = new CreateController($data, new ClientModel());
    $result = $createController->process();
    if (!$result) {
        $response->setStatusCode(409);
        $response->setSuccess(true);
        $response->setMessage('Creation failed');
        $response->send();
    }

    $response->setStatusCode(201);
    $response->setSuccess(true);
    $response->setData(['createdRowsCount' => $result]);
    $response->send();
} catch (Throwable $e) {
    $response = ResponseFactory::create(ResponseTypesInterface::JSON);
    $response->setStatusCode(500);
    $response->setSuccess(false);
    $response->setMessage('Something went wrong');
    $response->send();
}

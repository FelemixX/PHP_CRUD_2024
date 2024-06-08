<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/vendor/autoload.php');

if ($_SERVER['REQUEST_METHOD'] !== 'PATCH') {
    die('Wrong request');
}

$input = file_get_contents('php://input');
$data = json_decode($input, true);

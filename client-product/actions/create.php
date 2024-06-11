<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !intval($_POST['client_id']) && !intval($_POST['product_id'])) {
    die('Wrong request method');
}

$clientId = intval($_POST['client_id']);
$productId = intval($_POST['product_id']);

$clientProductModel = new \App\Database\MySQL\Models\ClientProductModel();

try {
    $insert = $clientProductModel->insert([
        'ID_CLIENT' => $clientId,
        'ID_PRODUCT' => $productId,
    ])
        ->exec()
        ->get()
        ->rowCount();

    header( "refresh:3;url=/client-product/" );
} catch (Throwable) {
    include ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php');
    die();
}

?>

<div class="container mx-auto my-auto">
    <?php if (!$insert): ?>
        <div class="container mx-auto my-auto text-danger text-uppercase text-center border border-danger">
            Something went wrong or requested data was not found.
        </div>
    <?php else: ?>
        <div class="container mx-auto my-auto text-success text-uppercase text-center border border-success">
            Done
        </div>
    <?php endif; ?>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');


if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !intval($_POST['id'])) {
    die('Wrong request method');
}

$id = intval($_POST['id']);
$clientProductModel = new \App\Database\MySQL\Models\ClientProductModel();

try {
    $delete = $clientProductModel->delete([
        '=ID' => $id
    ])
        ->exec()
        ->get()
        ->rowCount();

    header( "refresh:3;url=/client-product/");
} catch (Throwable $e) {
    include ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php');
    die();
}
?>
<div class="container mx-auto my-auto">
    <?php if (!$delete): ?>
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

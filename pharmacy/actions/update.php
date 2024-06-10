<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');


if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !intval($_POST['id']) && !intval($_POST['corp_id']) && !intval($_POST['city_id'])) {
    die('Wrong request method');
}

$id = intval($_POST['id']);
$cityId = intval($_POST['city_id']);
$corpId = intval($_POST['corp_id']);
$address = strval($_POST['address']) ?: '';
$name = strval($_POST['name']) ?: '';
$contacts = strval($_POST['contacts']) ?: '';
$pharmacyModel = new \App\Database\MySQL\Models\PharmacyModel();

try {
    $update = $pharmacyModel->update([
        'CITY_FK' => $cityId,
        'CORPORTATION_FK' => $corpId,
        'NAME' => $name,
        'ADDRESS' => $address,
        'CONTACTS' => $contacts,
    ])
        ->where([
            '=ID' => $id,
        ])
        ->exec()
        ->get()
        ->rowCount();

    header( "refresh:3;url=/pharmacy/");

} catch (Throwable) {
    include ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php');
    die();
}
?>

<div class="container mx-auto my-auto">
    <?php if (!$update): ?>
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

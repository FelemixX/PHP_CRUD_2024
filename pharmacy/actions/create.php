<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !intval($_POST['corp_id']) && !intval($_POST['city_id']) /*&& !strval($_POST['name']) && !strval($_POST['address']) && !strval($_POST['contacts'])*/) {
    die('Wrong request method');
}

$corpId = intval($_POST['corp_id']);
$cityId = intval($_POST['city_id']);
$address = strval($_POST['address']) ?: '';
$name = strval($_POST['name']) ?: '';
$contacts = strval($_POST['contacts']) ?: '';

$pharmacyModel = new \App\Database\MySQL\Models\PharmacyModel();

try {
    $insert = $pharmacyModel->insert([
        'CORPORTATION_FK' => $corpId,
        'CITY_FK' => $cityId,
        'NAME' => $name,
        'ADDRESS' => $address,
        'CONTACTS' => $contacts,
    ])
        ->exec()
        ->get()
        ->rowCount();

    header( "refresh:3;url=/pharmacy/" );
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

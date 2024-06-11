<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

if (!intval($_GET['id']) || !intval($_GET['client_id']) || !intval($_GET['product_id'])) {
    die('<div class="container mx-auto my-auto"><div class="container mx-auto my-auto text-danger text-uppercase text-center border border-danger">Something went wrong or requested data was not found.</div></div>');
}

$id = intval($_GET['id']);
$productId = intval($_GET['product_id']);
$clientId = intval($_GET['client_id']);

$productModel = new \App\Database\MySQL\Models\ProductModel();
$clientModel = new \App\Database\MySQL\Models\ClientModel();

$products = $productModel->select(['ID', 'NAME', 'PRICE'])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
$clients = $clientModel->select(['ID', 'FULL_NAME', 'PHONE_NUMBER'])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
?>

<div class="container mx-auto my-auto">
    <form method="POST" action="/client-product/actions/update.php">
        <fieldset>
            <legend class="text-center">
                Изменение данных в таблице <?= \App\Database\MySQL\Models\ClientProductModel::getTableName() ?>
            </legend>
            <div class="mb-3">
                <label class="form-label">
                    ID записи
                </label>
                <input type="hidden" class="d-none" value="<?= $id ?>" name="id">
                <input class="form-control" value="<?= $id ?>" placeholder="<?= $id ?>" disabled>
            </div>
            <div class="mb-3">
                <label for="client" class="form-label">
                    Клиенты
                </label>
                <select id="client" name="client_id" class="form-select">
                    <?php foreach ($clients as $client): ?>
                        <option <?= $clientId === $client['ID'] ? 'selected' : '' ?> value="<?= $client['ID'] ?>">
                            <?= $client['FULL_NAME'] ?> (тел. <?= $client['PHONE_NUMBER'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="product" class="form-label">
                    Товары
                </label>
                <select id="product" name="product_id" class="form-select">
                    <?php foreach ($products as $product): ?>
                        <option <?= $productId === $product['ID'] ? 'selected' : '' ?> value="<?= $product['ID'] ?>">
                            <?= $product['NAME'] ?> (<?= $product['PRICE'] ?> руб.)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Изменить</button>
        </fieldset>
    </form>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

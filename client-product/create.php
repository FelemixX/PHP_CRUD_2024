<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

$productModel = new \App\Database\MySQL\Models\ProductModel();
$clientModel = new \App\Database\MySQL\Models\ClientModel();

$products = $productModel->select(['ID', 'NAME', 'PRICE'])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
$clients = $clientModel->select(['ID', 'FULL_NAME', 'PHONE_NUMBER'])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
?>

<div class="container mx-auto my-auto">
    <form method="POST" action="/client-product/actions/create.php">
        <fieldset>
            <legend class="text-center">
                Добавление в таблицу <?= \App\Database\MySQL\Models\ClientProductModel::getTableName() ?>
            </legend>
            <div class="mb-3">
                <label for="client" class="form-label">
                    Клиенты
                </label>
                <select id="client" name="client_id" class="form-select">
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['ID'] ?>">
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
                        <option value="<?= $product['ID'] ?>">
                            <?= $product['NAME'] ?> (<?= $product['PRICE'] ?> руб.)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Добавить</button>
        </fieldset>
    </form>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

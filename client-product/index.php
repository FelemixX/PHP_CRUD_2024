<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

use App\Database\Model\AbstractModel;
use App\Database\MySQL\Models\ClientModel;
use App\Database\MySQL\Models\ClientProductModel;
use App\Database\MySQL\Models\ProductModel;

$clientProduct = new ClientProductModel();
$tableName = $clientProduct::getTableName();
$clientTableName = ClientModel::getTableName();
$productTableName = ProductModel::getTableName();

$data = $clientProduct->select([
    'ID',
    'CLIENT_ID' => "$clientTableName.ID",
    'CLIENT_NAME' => "$clientTableName.FULL_NAME",
    'CLIENT_PHONE' => "$clientTableName.PHONE_NUMBER",
    'PRODUCT_ID' => "$productTableName.ID",
    'PRODUCT_NAME' => "$productTableName.NAME",
    'PRODUCT_PRICE' => "$productTableName.PRICE",
])
    ->join(AbstractModel::JOIN_TYPE_INNER, ProductModel::getTableName(), "$productTableName.ID", "$tableName.ID_PRODUCT")
    ->join(AbstractModel::JOIN_TYPE_INNER, ClientModel::getTableName(), "$clientTableName.ID", "$tableName.ID_CLIENT")
    ->exec();

$items = $data->get()->fetchAll(PDO::FETCH_ASSOC);
$rows = $modalRows = array_keys($items[array_key_first($items)]);
?>
<?php if (!empty($items)): ?>
    <div class="container mx-auto my-auto">
        <div class="table-responsive">
            <table class="table table-hover border border-success text-center align-middle">
                <thead>
                <tr>
                    <?php foreach ($rows as $row): ?>
                        <td class="border border-success fw-bold">
                            <?= $row ?>
                        </td>
                    <?php endforeach; ?>
                    <td class="fw-bold" colspan="2">Действие</td>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <th scope="row">
                            <?= $item['ID'] ?>
                        </th>
                        <td class="border border-success">
                            <?= $item['CLIENT_ID'] ?>
                        </td>
                        <td class="border border-success">
                            <?= $item['CLIENT_NAME'] ?>
                        </td>
                        <td class="border border-success">
                            <?= $item['CLIENT_PHONE'] ?>
                        </td>
                        <td class="border border-success">
                            <?= $item['PRODUCT_ID'] ?>
                        </td>
                        <td class="border border-success">
                            <?= $item['PRODUCT_NAME'] ?>
                        </td>
                        <td class="border border-success">
                            <?= $item['PRODUCT_PRICE'] ?>
                        </td>
                        <td class="border border-success">
                            <a href="/client-product/update.php?id=<?= $item['ID'] ?>&client_id=<?= $item['CLIENT_ID'] ?>&product_id=<?= $item['PRODUCT_ID'] ?>" class="btn btn-success">
                                Изменить
                            </a>
                        </td>
                        <td class="border border-success">
                            <a href="/client-product/delete.php?id=<?= $item['ID'] ?>&client_id=<?= $item['CLIENT_ID'] ?>&product_id=<?= $item['PRODUCT_ID'] ?>" class="btn btn-danger" disabled>
                                Удалить
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-row-reverse">
            <a href="/client-product/create.php" class="btn btn-primary">
                Добавить
            </a>
        </div>
    </div>
<?php else: ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php' ?>
<?php endif; ?>

<script>
    var ajaxPath = '/client-product/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

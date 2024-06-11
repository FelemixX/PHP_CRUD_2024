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
                        <td class="border border-success" data-value-row-number="1" data-disabled="true">
                            <?= $item['CLIENT_ID'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="2">
                            <?= $item['CLIENT_NAME'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="3">
                            <?= $item['CLIENT_PHONE'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="4" data-disabled="true">
                            <?= $item['PRODUCT_ID'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="5">
                            <?= $item['PRODUCT_NAME'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="6">
                            <?= $item['PRODUCT_PRICE'] ?>
                        </td>
                        <td class="border border-success">
                            <button type="button" class="btn btn-success" disabled>
                                Изменить
                            </button>
                        </td>
                        <td class="border border-success">
                            <button type="button" class="btn btn-danger" disabled>
                                Удалить
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-row-reverse">
            <button type="button" class="btn btn-primary" disabled>
                Создать
            </button>
        </div>
    </div>
<?php else: ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php' ?>
<?php endif; ?>

<script>
    var ajaxPath = '/client-product/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

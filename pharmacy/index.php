<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

use App\Database\Model\AbstractModel;
use App\Database\MySQL\Models\CorporationModel;


$pharmacyModel = new \App\Database\MySQL\Models\PharmacyModel();
$corporationModel = new CorporationModel();
$cityModel = new \App\Database\MySQL\Models\CityModel();

$pharmacyTableName = $pharmacyModel::getTableName();
$corporationTableName = $corporationModel::getTableName();
$cityTableName = $cityModel::getTableName();

$pharmacy = $pharmacyModel->select([
    'ID',
    'NAME',
    'ADDRESS',
    'CONTACTS',
    'CORPORTATION_ID' => 'CORPORTATION_FK',
    'CITY_ID' => 'CITY_FK',
    'CITY_NAME' => "$cityTableName.NAME",
    'CITY_REGION' => "$cityTableName.REGION",
    'CORPORATION_NAME' => "$corporationTableName.NAME",
    'CORPORATION_ACTUAL_OWNER' => "$corporationTableName.ACTUAL_OWNER",
])
    ->order(['NAME' => 'DESC', 'ID' => 'ASC'])
    ->join(AbstractModel::JOIN_TYPE_INNER, $corporationTableName , "$corporationTableName.ID", "$pharmacyTableName.CORPORTATION_FK")
    ->join(AbstractModel::JOIN_TYPE_INNER, $cityTableName, "$cityTableName.ID", "$pharmacyTableName.CITY_FK")
    ->exec();

$data = $pharmacy->get()->fetchAll(PDO::FETCH_ASSOC);
$rows = $modalRows = array_keys($data[array_key_first($data)]);
?>
<?php if (!empty($data)): ?>
    <div class="container mx-auto my-auto">
        <table class="table-hover table-responsive border border-success text-center align-middle">
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
            <?php foreach ($data as $item): ?>
                <tr>
                    <th scope="row">
                        <?= $item['ID'] ?>
                    </th>
                    <td class="border border-success" data-value-row-number="1">
                        <?= $item['NAME'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="2">
                        <?= $item['ADDRESS'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="3">
                        <?= $item['CONTACTS'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="3">
                        <?= $item['CORPORTATION_ID'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="3">
                        <?= $item['CITY_ID'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="3">
                        <?= $item['CITY_NAME'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="3">
                        <?= $item['CITY_REGION'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="3">
                        <?= $item['CORPORATION_NAME'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="3">
                        <?= $item['CORPORATION_ACTUAL_OWNER'] ?>
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
    var ajaxPath = '/corporation/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

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

if (!empty($_GET)) {
    $order = $_GET;
} else {
    $order = ['NAME' => 'DESC', 'ID' => 'ASC'];
}

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
    ->order($order)
    ->join(AbstractModel::JOIN_TYPE_INNER, $corporationTableName , "$corporationTableName.ID", "$pharmacyTableName.CORPORTATION_FK")
    ->join(AbstractModel::JOIN_TYPE_INNER, $cityTableName, "$cityTableName.ID", "$pharmacyTableName.CITY_FK")
    ->exec();

$data = $pharmacy->get()->fetchAll(PDO::FETCH_ASSOC);
$rows = $modalRows = array_keys($data[array_key_first($data)]);
?>
<?php if (!empty($data)): ?>
    <div class="container-fluid mx-auto my-auto">
        <div class="table-responsive">
            <table class="table table-hover border border-success text-center align-middle">
                <thead>
                <tr>
                    <?php foreach ($rows as $row): ?>
                        <td class="border border-success fw-bold user-select-none" data-row="<?= $row ?>">
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
                            <a href="/pharmacy/update.php?id=<?= $item['ID'] ?>&corp_id=<?= $item['CORPORTATION_ID'] ?>&city_id=<?= $item['CITY_ID'] ?>" class="btn btn-success">
                                Изменить
                            </a>
                        </td>
                        <td class="border border-success">
                            <a href="/pharmacy/delete.php?id=<?= $item['ID'] ?>&corp_id=<?= $item['CORPORTATION_ID'] ?>&city_id=<?= $item['CITY_ID'] ?>" class="btn btn-danger" disabled>
                                Удалить
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-row-reverse">
            <a href="/pharmacy/create.php" class="btn btn-primary">
                Добавить
            </a>
        </div>
    </div>
<?php else: ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php' ?>
<?php endif; ?>

<script>
    var ajaxPath = '/corporation/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

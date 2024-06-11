<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

use App\Database\Model\AbstractModel;

$ratingModel = new \App\Database\MySQL\Models\RatingModel();
$pharmacyModel = new \App\Database\MySQL\Models\PharmacyModel();

$ratingTableName = $ratingModel->getTableName();
$pharmacyTableName = $pharmacyModel::getTableName();


$rating = $ratingModel->select([
    'ID',
    'GRADE',
    'PHARMACY_ID' => 'ID_PHARMACY',
    'PHARMACY_NAME' => "$pharmacyTableName.NAME",
    'PHARMACY_ADDRESS' => "$pharmacyTableName.ADDRESS",
])
    ->order(['GRADE' => 'DESC', 'ID' => 'ASC'])
    ->join(AbstractModel::JOIN_TYPE_INNER, $pharmacyTableName , "$pharmacyTableName.ID", "$ratingTableName.ID_PHARMACY")
    ->exec();

$data = $rating->get()->fetchAll(PDO::FETCH_ASSOC);
$rows = $modalRows = array_keys($data[array_key_first($data)]);
?>
<?php if (!empty($data)): ?>
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
                <?php foreach ($data as $item): ?>
                    <tr>
                        <th scope="row">
                            <?= $item['ID'] ?>
                        </th>
                        <td class="border border-success" data-value-row-number="1" data-disabled="true">
                            <?= $item['GRADE'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="2">
                            <?= $item['PHARMACY_ID'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="3">
                            <?= $item['PHARMACY_NAME'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="4" data-disabled="true">
                            <?= $item['PHARMACY_ADDRESS'] ?>
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
    var ajaxPath = '/rating/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

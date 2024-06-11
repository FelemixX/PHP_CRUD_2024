<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

use App\Database\Model\AbstractModel;
use App\Database\MySQL\Models\EmployeeModel;

$employeeModel = new \App\Database\MySQL\Models\EmployeeModel();
$pharmacyModel = new \App\Database\MySQL\Models\PharmacyModel();


$pharmacyTableName = $pharmacyModel::getTableName();
$employeeTableName = $employeeModel->getTableName();

$employee = $employeeModel->select([
    'ID',
    'FULL_NAME',
    'POST',
    'PHARMACY_ID' => 'PHARMACY_FK',
    'PHARMACY_NAME' => "$pharmacyTableName.NAME",
    'PHARMACY_ADDRESS' => "$pharmacyTableName.ADDRESS",
])
    ->order(['FULL_NAME' => 'DESC', 'ID' => 'ASC'])
    ->join(AbstractModel::JOIN_TYPE_INNER, $pharmacyTableName , "$pharmacyTableName.ID", "$employeeTableName.PHARMACY_FK")
    ->exec();

$data = $employee->get()->fetchAll(PDO::FETCH_ASSOC);
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
                        <td class="border border-success" data-value-row-number="1">
                            <?= $item['FULL_NAME'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="2">
                            <?= $item['PHARMACY_ID'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="3">
                            <?= $item['PHARMACY_NAME'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="3">
                            <?= $item['PHARMACY_ADDRESS'] ?>
                        </td>
                        <td class="border border-success">
                            <button type="button" class="btn btn-success" disabled>
                                Изменить
                            </button>
                        </td>
                        <td class="border border-success">
                            <button type="button" class="btn btn-danger" disabled >
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
    var ajaxPath = '/employee/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

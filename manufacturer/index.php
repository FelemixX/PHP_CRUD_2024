<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

$manufacturerModel = new \App\Database\MySQL\Models\ManufacturerModel();

if (!empty($_GET)) {
    $order = $_GET;
} else {
    $order = ['NAME' => 'ASC', 'ID' => 'ASC'];
}

$manufacturer = $manufacturerModel->select(['ID', 'NAME', 'CONTACTS'])
    ->order($order)
    ->exec();

$data = $manufacturer->get()->fetchAll(PDO::FETCH_ASSOC);
$rows = $modalRows = array_keys($data[array_key_first($data)]);
?>
<?php if (!empty($data)): ?>
    <div class="container mx-auto my-auto">
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
                <?php foreach ($data as $client): ?>
                    <tr>
                        <th scope="row">
                            <?= $client['ID'] ?>
                        </th>
                        <td class="border border-success" data-value-row-number="1">
                            <?= $client['NAME'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="2">
                            <?= $client['CONTACTS'] ?>
                        </td>
                        <td class="border border-success">
                            <button type="button" class="btn btn-success" data-action="update" data-bs-title="Изменить" data-id="<?= $client['ID'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#tableActionModal"
                            >
                                Изменить
                            </button>
                        </td>
                        <td class="border border-success">
                            <button type="button" class="btn btn-danger" data-action="delete" data-bs-title="Удалить" data-id="<?= $client['ID'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#tableActionModal"
                            >
                                Удалить
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-row-reverse">
            <button type="button" class="btn btn-primary" data-action="create" data-bs-title="Создать"
                    data-bs-toggle="modal" data-bs-target="#tableActionModal"
            >
                Создать
            </button>
        </div>
    </div>
<?php else: ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php'; ?>
<?php endif; ?>

<script>
    var ajaxPath = '/manufacturer/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php') ?>

<?php
$clientModel = new \App\Database\MySQL\Models\ClientModel();

$clients = $clientModel->select(['ID', 'FULL_NAME', 'PHONE_NUMBER'])
    ->order(['FULL_NAME' => 'ASC', 'ID' => 'ASC'])
    ->exec();

$rows = $modalRows = array_keys((array)$clients->get()->fetch(PDO::FETCH_ASSOC));
$clientsData = $clients->get()->fetchAll(PDO::FETCH_ASSOC);
?>
<?php if (!empty($clientsData)): ?>
    <div class="container mx-auto my-auto">
        <table class="table table-hover table-responsive border border-success text-center align-middle">
            <thead>
            <tr>
                <?php foreach ($rows as $row): ?>
                    <td class="border border-success">
                        <?= $row ?>
                    </td>
                <?php endforeach; ?>
                <td colspan="2">Действие</td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($clientsData as $client): ?>
                <tr>
                    <th scope="row">
                        <?= $client['ID'] ?>
                    </th>
                    <td class="border border-success" data-value-row-number="1">
                        <?= $client['FULL_NAME'] ?>
                    </td>
                    <td class="border border-success" data-value-row-number="2">
                        <?= $client['PHONE_NUMBER'] ?>
                    </td>
                    <td class="border border-success">
                        <button type="button" class="btn btn-primary" data-action="update" data-bs-title="Изменить" data-id="<?= $client['ID'] ?>"
                                data-bs-toggle="modal" data-bs-target="#tableActionModal"
                        >
                            Изменить
                        </button>
                    </td>
                    <td class="border border-success">
                        <button type="button" class="btn btn-primary" data-action="delete" data-bs-title="Удалить" data-id="<?= $client['ID'] ?>"
                                data-bs-toggle="modal" data-bs-target="#tableActionModal"
                        >
                            Удалить
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="d-flex flex-row-reverse">
            <button type="button" class="btn btn-primary" data-action="create" data-bs-title="Создать">
                Создать
            </button>
        </div>
    </div>
<?php else: ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php' ?>
<?php endif; ?>

<script>
    var ajaxPath = '/client/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

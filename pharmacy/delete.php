<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

if (!intval($_GET['id']) || !intval($_GET['corp_id']) || !intval($_GET['city_id'])) {
    die('<div class="container mx-auto my-auto"><div class="container mx-auto my-auto text-danger text-uppercase text-center border border-danger">Something went wrong or requested data was not found.</div></div>');
}

$corpModel = new \App\Database\MySQL\Models\CorporationModel();
$cityModel = new \App\Database\MySQL\Models\CityModel();

$id = intval($_GET['id']);
$corpId = intval($_GET['corp_id']);
$cityId = intval($_GET['city_id']);

$corpModel = new \App\Database\MySQL\Models\CorporationModel();
$cityModel = new \App\Database\MySQL\Models\CityModel();

$corps = $corpModel->select(['ID', 'NAME', 'ACTUAL_OWNER'])
    ->where(['=ID' => $corpId])
    ->exec()
    ->get()
    ->fetchAll(\PDO::FETCH_ASSOC);
$cities = $cityModel->select(['ID', 'NAME', 'REGION'])
    ->where(['=ID' => $cityId])
    ->exec()
    ->get()
    ->fetchAll(\PDO::FETCH_ASSOC);
?>

<div class="container mx-auto my-auto">
    <form method="POST" action="/pharmacy/actions/delete.php">
        <fieldset>
            <legend class="text-center">
                Удаление из таблицы <?= \App\Database\MySQL\Models\PharmacyModel::getTableName() ?>
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
                    Город
                </label>
                <select id="client" class="form-select" disabled>
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city['ID'] ?>">
                            <?= $city['NAME'] ?> (<?= $city['REGION'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="product" class="form-label">
                    Организация
                </label>
                <select id="product" class="form-select" disabled>
                    <?php foreach ($corps as $corp): ?>
                        <option value="<?= $corp['ID'] ?>">
                            <?= $corp['NAME'] ?> (<?= $corp['ACTUAL_OWNER'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Удалить</button>
        </fieldset>
    </form>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

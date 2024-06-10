<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

if (!intval($_GET['id']) || !intval($_GET['corp_id']) || !intval($_GET['city_id'])) {
    die('<div class="container mx-auto my-auto"><div class="container mx-auto my-auto text-danger text-uppercase text-center border border-danger">Something went wrong or requested data was not found.</div></div>');
}

$id = intval($_GET['id']);
$corpId = intval($_GET['corp_id']);
$cityId = intval($_GET['city_id']);

$pharmacyModel = new App\Database\MySQL\Models\PharmacyModel();
$corpModel = new \App\Database\MySQL\Models\CorporationModel();
$cityModel = new \App\Database\MySQL\Models\CityModel();

$corps = $corpModel->select(['ID', 'NAME', 'ACTUAL_OWNER'])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
$cities = $cityModel->select(['ID', 'NAME', 'REGION'])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
$pharmaInfo = $pharmacyModel->select(['ID', 'NAME', 'ADDRESS', 'CONTACTS'])->where(['=ID' => $id])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
?>

<div class="container mx-auto my-auto">
    <form method="POST" action="/pharmacy/actions/update.php">
        <fieldset>
            <legend class="text-center">
                Изменение данных в таблице <?= \App\Database\MySQL\Models\PharmacyModel::getTableName() ?>
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
                    Города
                </label>
                <select id="client" name="city_id" class="form-select">
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city['ID'] ?>">
                            <?= $city['NAME'] ?> (<?= $city['REGION'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="product" class="form-label">
                    Организации
                </label>
                <select id="product" name="corp_id" class="form-select">
                    <?php foreach ($corps as $corp): ?>
                        <option value="<?= $corp['ID'] ?>">
                            <?= $corp['NAME'] ?> (<?= $corp['ACTUAL_OWNER'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">
                    Название
                </label>
                <input type="text" class="form-control" id="name" name="name" placeholder="<?= $pharmaInfo['NAME'] ?>" value="<?= $pharmaInfo['NAME'] ?>">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">
                    Адрес
                </label>
                <input type="text" class="form-control" id="address" name="address" placeholder="<?= $pharmaInfo['ADDRESS'] ?>" value="<?= $pharmaInfo['ADDRESS'] ?>">
            </div>
            <div class="mb-3">
                <label for="contacts" class="form-label">
                    Контакты
                </label>
                <input type="text" class="form-control" id="contacts" name="contacts" placeholder="<?= $pharmaInfo['CONTACTS'] ?>" value="<?= $pharmaInfo['CONTACTS'] ?>">
            </div>
            <button type="submit" class="btn btn-success">Изменить</button>
        </fieldset>
    </form>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

$corporationModel = new \App\Database\MySQL\Models\CorporationModel();
$cityModel = new \App\Database\MySQL\Models\CityModel();

$corps = $corporationModel->select(['ID', 'NAME', 'ACTUAL_OWNER'])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
$cities = $cityModel->select(['ID', 'NAME', 'REGION'])->exec()->get()->fetchAll(\PDO::FETCH_ASSOC);
?>

<div class="container mx-auto my-auto">
    <form method="POST" action="/pharmacy/actions/create.php">
        <fieldset>
            <legend class="text-center">
                Добавление в таблицу <?= \App\Database\MySQL\Models\PharmacyModel::getTableName() ?>
            </legend>
            <div class="mb-3">
                <label for="city" class="form-label">
                    Города
                </label>
                <select id="city" name="city_id" class="form-select">
                    <?php foreach ($cities as $city): ?>
                        <option value="<?= $city['ID'] ?>">
                            <?= $city['NAME'] ?> (<?= $city['REGION'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="corp" class="form-label">
                    Организации
                </label>
                <select id="corp" name="corp_id" class="form-select">
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
                <input type="text" class="form-control" id="name" name="name">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">
                    Адрес
                </label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="mb-3">
                <label for="contacts" class="form-label">
                    Контакты
                </label>
                <input type="text" class="form-control" id="contacts" name="contacts">
            </div>
            <button type="submit" class="btn btn-success">Добавить</button>
        </fieldset>
    </form>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

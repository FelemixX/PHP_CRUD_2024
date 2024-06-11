<?php


include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

$unifiedModel = new \App\Database\MySQL\Models\UnifiedModel();
$tablesNames = $unifiedModel
    ->executeQueryByString('SELECT table_name FROM information_schema.tables WHERE table_schema = "bitrix"')
    ->get()
    ->fetchAll(\PDO::FETCH_ASSOC);
$tablesNames = array_column($tablesNames, 'TABLE_NAME')
?>

<div class="container mx-auto my-auto">
    <form class="search-form">
        <fieldset>
            <legend class="text-center">
                Поиск
            </legend>
            <div class="mb-3">
                <label for="table-name">В какой таблице ищем</label>
                <select class="table-selector form-select" name="table-name" required>
                    <option selected value="">Выберите таблицу</option>
                    <?php foreach ($tablesNames as $tableName): ?>
                        <option value="<?= $tableName ?>">
                            <?= $tableName ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3 d-none">
                <label for="table-column">По какому столбцу ищем</label>
                <select class="column-selector form-select" name="table-column" required>
                    <option selected value="">Выберите столбец</option>
                    <option value="12312">Выберите стsdfsdfолбец</option>
                </select>
            </div>
            <div class="mb-3 d-none">
                <label for="search">Что ищем</label>
                <input class="search-input form-control" id="search" name="search" required>
            </div>
            <button type="submit" class="btn btn-success">Поиск</button>
        </fieldset>
    </form>

    <div class="table-responsive d-none mt-4">
        <table class="table table-hover border border-success text-center align-middle">
            <thead>
                <tr class="table-head__rows">
                </tr>
            </thead>
            <tbody class="table-body">
            </tbody>
        </table>
    </div>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

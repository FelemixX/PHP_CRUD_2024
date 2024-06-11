<?php

include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php');

$unifiedModel = new \App\Database\MySQL\Models\UnifiedModel();

//Аналитические запросы (???)

//SELECT ID, SUM(PRICE) as PRICE_SUM, NAME FROM product
//EXPLAIN SELECT ID, SUM(PRICE) as PRICE_SUM, NAME FROM product
//SELECT ID, AVG(PRICE) as PRICE_SUM, NAME FROM product
//SELECT client_product.ID  AS ID, client.FULL_NAME as CLIENT_NAME, product.NAME  as PRODUCT_NAME, AVG(product.PRICE) as AVG_PRICE, SUM(product.PRICE) as PRODUCT_PRICE_SUM FROM client_product INNER JOIN client ON client_product.ID_CLIENT = client.ID INNER JOIN product ON client_product.ID_PRODUCT = product.ID GROUP BY client.ID ORDER BY AVG_PRICE DESC

if ($_GET['sql']) {
    try {
        $query = $unifiedModel
            ->executeQueryByString(htmlspecialchars_decode(trim($_GET['sql'])))
            ->get()
            ->fetchAll(\PDO::FETCH_ASSOC);
        echo '<script>console.log(' . json_encode($query) . ');</script>'; //TODO: DELETE LOGGING
    } catch (Throwable $exception) {
        echo '<div class="container mx-auto my-auto text-danger text-uppercase text-center border border-danger">Something went wrong.</div>';
        include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php');
        die();
    }
}
?>
<div class="container mx-auto my-auto">
    <form class="search-form" method="GET">
        <fieldset>
            <legend class="text-center">
                Произвольный запрос
            </legend>
            <div class="mb-3">
                <label for="sql">Запрос</label>
                <input class="form-control" name="sql" value="<?= $_GET['sql'] ?>">
            </div>
            <button type="submit" class="btn btn-success">Отправить</button>
        </fieldset>
    </form>

    <?php if (!empty($query)): ?>
        <div class="table-responsive mt-4">
            <table class="table table-hover border border-success text-center align-middle">
                <thead>
                <tr class="table-head__rows">
                    <?php $rows = array_keys((array)$query[array_key_first((array)$query)]); ?>
                    <?php foreach ($rows as $row): ?>
                        <td class="border border-success fw-bold">
                            <?= $row ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
                </thead>
                <tbody class="table-body">
                    <?php foreach ($query as $item): ?>
                    <tr>
                        <?php foreach ($item as $value): ?>
                            <td class="border border-success">
                                <?= $value ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php'); ?>

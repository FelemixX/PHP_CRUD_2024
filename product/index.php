<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php')

$productModel = new \App\Database\MySQL\Models\ProductModel();

$products = $productModel->select(['ID', 'NAME', 'PRICE'])
    ->order(['NAME' => 'ASC', 'PRICE' => 'DESC', 'ID' => 'ASC'])
    ->exec();

$productsData = $products->get()->fetchAll(PDO::FETCH_ASSOC);
$rows = $modalRows = array_keys($productsData[array_key_first($productsData)]);
?>
<?php if (!empty($productsData)): ?>
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
                <?php foreach ($productsData as $product): ?>
                    <tr>
                        <th scope="row">
                            <?= $product['ID'] ?>
                        </th>
                        <td class="border border-success" data-value-row-number="1">
                            <?= $product['NAME'] ?>
                        </td>
                        <td class="border border-success" data-value-row-number="2">
                            <?= $product['PRICE'] ?>
                        </td>
                        <td class="border border-success">
                            <button type="button" class="btn btn-success" data-action="update" data-bs-title="Изменить" data-id="<?= $product['ID'] ?>"
                                    data-bs-toggle="modal" data-bs-target="#tableActionModal"
                            >
                                Изменить
                            </button>
                        </td>
                        <td class="border border-success">
                            <button type="button" class="btn btn-danger" data-action="delete" data-bs-title="Удалить" data-id="<?= $product['ID'] ?>"
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
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/empty.php' ?>
<?php endif; ?>

<script>
    var ajaxPath = '/product/actions/';
</script>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

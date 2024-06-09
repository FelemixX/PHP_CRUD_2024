<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php') ?>

<div class="container mx-auto my-auto">
    <table class="table table-hover table-responsive border border-success text-center align-middle">
        <thead>
        <tr>
            <td class="border border-success fw-bold">#</td>
            <td class="border border-success fw-bold">Сущность</td>
            <td class="border border-success fw-bold">Таблицы</td>
            <td class="fw-bold" colspan="2">Ссылка</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td class="border border-success">Client</td>
            <td class="border border-success">client</td>
            <td class="border border-success">
                <a href="/client/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td class="border border-success">Product</td>
            <td class="border border-success">product</td>
            <td class="border border-success">
                <a href="/product/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        <tr>
            <th scope="row">3</th>
            <td class="border border-success">ClientProduct</td>
            <td class="border border-success">client_product</td>
            <td class="border border-success">
                <a href="/client-product/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

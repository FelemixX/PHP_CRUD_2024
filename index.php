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
        <tr>
            <th scope="row">4</th>
            <td class="border border-success">Corporation</td>
            <td class="border border-success">corporation</td>
            <td class="border border-success">
                <a href="/corporation/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        <tr>
            <th scope="row">5</th>
            <td class="border border-success">City</td>
            <td class="border border-success">city</td>
            <td class="border border-success">
                <a href="/city/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        <tr>
            <th scope="row">6</th>
            <td class="border border-success">Classification</td>
            <td class="border border-success">classification</td>
            <td class="border border-success">
                <a href="/classification/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        <tr>
            <th scope="row">7</th>
            <td class="border border-success">Employee</td>
            <td class="border border-success">employee</td>
            <td class="border border-success">
                <a href="/employee/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        <tr>
            <th scope="row">8</th>
            <td class="border border-success">Logistics</td>
            <td class="border border-success">logistics</td>
            <td class="border border-success">
                <a href="/logistics/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        <tr>
            <th scope="row">9</th>
            <td class="border border-success">Manufacturer</td>
            <td class="border border-success">manufacturer</td>
            <td class="border border-success">
                <a href="/manufacturer/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        <tr>
            <th scope="row">10</th>
            <td class="border border-success">Rating</td>
            <td class="border border-success">rating</td>
            <td class="border border-success">
                <a href="/rating/" class="btn btn-success">Перейти</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<?php include_once ($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

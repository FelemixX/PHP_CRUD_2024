<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/header.php') ?>


<div class="container mx-auto my-auto">
    <table class="table table-hover table-responsive border border-success text-center align-middle">
        <thead>
            <tr>
                <td class="border border-success">#</td>
                <td class="border border-success">2</td>
                <td class="border border-success">3</td>
                <td class="border border-success">4</td>
            </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td class="border border-success">Mark</td>
            <td class="border border-success">Otto</td>
            <td class="border border-success">@mdo</td>
            <td class="border border-success">
                <button type="button" class="btn btn-primary">Изменить</button>
            </td>
            <td class="border border-success">
                <button type="button" class="btn btn-primary">Удалить</button>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="d-flex flex-row-reverse">
        <button type="button" class="btn btn-primary">Создать</button>
    </div>


</div>

<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/local/templates/default/footer.php') ?>

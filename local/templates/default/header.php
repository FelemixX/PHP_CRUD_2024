<?php require_once ($_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/lib/vendor/autoload.php'); ?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" type="image/x-icon" href="/local/templates/default/assets/img/favicon.jpeg">

    <!-- bootstrap -->

    <!--  css  -->
    <link rel="stylesheet" href="/local/templates/default/assets/css/bootstrap/bootstrap.min.css">

    <!--  js  -->
    <script src="/local/templates/default/assets/js/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- /bootstrap -->
    <script src="/local/templates/default/assets/js/main.js"></script>

    <?php
    $requestUri = $_SERVER['REQUEST_URI'];
    $path = preg_replace('/\/\w+\.php$/', '', parse_url($requestUri, PHP_URL_PATH)); //Оставляем возможность подключать пользовательские скрипты
    ?>
    <?php if (!empty($path) && file_exists($_SERVER['DOCUMENT_ROOT'] . $path . 'script.js')): ?>
        <script src="<?= $path ?>/script.js"></script>
    <?php endif; ?>

    <title>CRUD</title>
</head>
<body class="d-flex flex-column vh-100">
<header>
    <nav class="navbar navbar-expand-lg bg-success" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">CRUD</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02"
                    aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/pharmacy/">
                            Pharmacy
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/client/">
                            Client
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/product/">
                            Product
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/client-product/">
                            Client-Product
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/city/">
                            City
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/corporation/">
                            Corporation
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/classification/">
                            Classification
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/employee/">
                            Employee
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/logistics/">
                            Logistics
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/manufacturer/">
                            Manufacturer
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/rating/">
                            Rating
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/search/">
                            Search
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="/query/">
                            Query
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

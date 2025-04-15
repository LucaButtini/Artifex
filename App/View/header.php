<?php
$appConfig= require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl = $appConfig['baseURL'].$appConfig['prjName'];
$href=$appConfig['baseURL'].$appConfig['prjName'].$appConfig['css'];

ob_start(); // Per evitare warning "headers already sent"
session_start();

$page = basename($_SERVER["SCRIPT_NAME"]);
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- CSS Personalizzato -->
    <link rel="stylesheet" href="<?= $href ?>">

    <title><?= $title ?? 'Artifex' ?></title>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-primary navbar-dark sticky-top py-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $baseUrl ?>index.php"><strong>Artifex</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarText">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $page == 'index.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page == 'services.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>services.php">Servizi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page == 'events.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>events.php">Eventi</a>
                </li>
                <?php if (isset($_SESSION['user_nome'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $baseUrl ?>logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'login.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $page == 'register.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>register.php">Registrati</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5 text-center rounded-4 flex-grow-1">

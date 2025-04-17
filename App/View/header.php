<?php
$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];
$href = $appConfig['baseURL'] . $appConfig['prjName'] . $appConfig['css'];

session_start(); // Inizializza la sessione
$page = basename($_SERVER["SCRIPT_NAME"]); // Ottiene il nome della pagina corrente
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $href ?>">

    <title><?= $title ?? 'Artifex' ?></title>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg bg-primary navbar-dark sticky-top py-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $baseUrl ?>index.php"><strong>Artifex</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
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
            </ul>

            <div class="d-flex">
                <?php if (isset($_SESSION['user_nome'])) { ?>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i> <?= $_SESSION['user_nome'] ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li><a class="dropdown-item" href="<?= $baseUrl ?>profile.php"><i class="bi bi-gear-fill"></i> Impostazioni</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= $baseUrl ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php } else { ?>
                    <a class="btn btn-dark btn-outline-light" href="<?= $baseUrl ?>login.php"><i class="bi bi-box-arrow-in-right"></i> Login</a>
                    <a class="btn btn-dark ms-2 btn-outline-light" href="<?= $baseUrl ?>register.php"><i class="bi bi-person-plus"></i> Registrati</a>
                <?php } ?>
            </div>
        </div>
    </div>
</nav>

<!-- Div principale per le pagine -->
<div class="container mt-5 flex-grow-1">
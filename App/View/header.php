<?php
// App/View/header.php

// Avvia la sessione solo se non è già stata avviata
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Config e URL base
$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl   = $appConfig['baseURL'] . $appConfig['prjName'];
$href      = $baseUrl . $appConfig['css'];

// Pagina corrente (per l'active link)
$page      = basename($_SERVER["SCRIPT_NAME"]);

// Prendi dal sessione, se presenti, i dati completi di visitatore o admin
$visitorSession = $_SESSION['visitor'] ?? null;
$adminSession   = $_SESSION['admin']   ?? null;

// Il nome da mostrare in navbar
$username = $visitorSession['nome']
    ?? $adminSession['username']
    ?? null;
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $href ?>">
    <title><?= $title ?? 'Artifex' ?></title>
</head>
<body class="d-flex flex-column min-vh-100">

<nav class="navbar navbar-expand-lg bg-primary navbar-dark sticky-top py-3">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $baseUrl ?>"><strong>Artifex</strong></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $page==='index.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page==='services.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>home/services">Servizi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $page==='events.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>events.php">Eventi</a>
                </li>
                <?php if ($adminSession): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $page==='adminDashboard.php' ? 'active' : '' ?>" href="<?= $baseUrl ?>admin/dashboard">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
            <div class="d-flex">
                <?php if ($username): ?>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= $username ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="<?= $baseUrl ?>info">
                                    <i class="bi bi-gear-fill"></i> Info Profilo
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= $baseUrl ?>logout">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="btn btn-dark btn-outline-light me-2" href="<?= $baseUrl ?>form/login/visitor">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </a>
                    <a class="btn btn-dark btn-outline-light" href="<?= $baseUrl ?>form/insert/visitor">
                        <i class="bi bi-person-plus"></i> Registrati
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-5 flex-grow-1">

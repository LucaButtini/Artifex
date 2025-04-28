<?php
// App/View/adminDashboard.php

$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];

// Avvio sessione se serve
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Solo admin può vedere
if (!isset($_SESSION['admin'])) {
    header('Location: ' . $baseUrl);
    exit;
}

// Recupera nome admin
$adminSession = $_SESSION['admin'];
$username = $adminSession['username'] ?? 'Admin';

// Includi i modelli necessari per interagire con il database
require_once 'App/Model/Event.php';
require_once 'App/Model/Schedule.php';
require_once 'App/Model/Guide.php';

$eventModel = new Event($appConfig['db']);
$scheduleModel = new Schedule($appConfig['db']);
$guideModel = new Guide($appConfig['db']);

// Recupera i dati
$events = $eventModel->getAll();
$schedules = $scheduleModel->getAll();
$guides = $guideModel->getAll();

// Gestione inserimento/modifica/eliminazione
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_event'])) {
        // Inserimento nuovo evento
        $eventModel->create($_POST);
    } elseif (isset($_POST['create_schedule'])) {
        // Inserimento nuova data visita
        $scheduleModel->create($_POST);
    } elseif (isset($_POST['create_guide'])) {
        // Inserimento nuova guida
        $guideModel->create($_POST);
    } elseif (isset($_POST['delete_event'])) {
        // Eliminazione evento
        $eventModel->delete($_POST['event_id']);
    } elseif (isset($_POST['delete_schedule'])) {
        // Eliminazione data visita
        $scheduleModel->delete($_POST['schedule_id']);
    } elseif (isset($_POST['delete_guide'])) {
        // Eliminazione guida
        $guideModel->delete($_POST['guide_id']);
    }

    // Ricarica la pagina per mostrare le modifiche
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Amministratore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">

<?php require 'header.php'; ?>

<div class="container mt-5 flex-grow-1">
    <h1 class="mb-4">Benvenuto, <?= htmlspecialchars($username) ?>!</h1>

    <div class="row g-4">

        <!-- Card: Gestione Eventi -->
        <div class="col-md-4">
            <div class="card text-bg-primary shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Eventi</h5>
                    <p class="card-text">Inserisci, modifica o elimina eventi culturali.</p>
                    <a href="#" class="btn btn-light mt-auto" data-bs-toggle="collapse" data-bs-target="#createEventForm">
                        Aggiungi Evento
                    </a>

                    <!-- Form di creazione evento -->
                    <div class="collapse mt-3" id="createEventForm">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="event_title" class="form-label">Titolo Evento</label>
                                <input type="text" name="event_title" id="event_title" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="event_price" class="form-label">Prezzo</label>
                                <input type="number" name="event_price" id="event_price" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="event_guide" class="form-label">Guida</label>
                                <input type="text" name="event_guide" id="event_guide" class="form-control" required>
                            </div>
                            <button type="submit" name="create_event" class="btn btn-primary">Crea Evento</button>
                        </form>
                    </div>

                    <!-- Lista Eventi -->
                    <h6 class="mt-4">Eventi Esistenti</h6>
                    <ul class="list-group">
                        <?php foreach ($events as $event): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($event['title']) ?></strong><br>
                                Prezzo: <?= htmlspecialchars($event['price']) ?> €
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                    <button type="submit" name="delete_event" class="btn btn-danger btn-sm float-end">
                                        Elimina
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Card: Gestione Date Visite -->
        <div class="col-md-4">
            <div class="card text-bg-success shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Date Visite</h5>
                    <p class="card-text">Organizza il calendario delle visite guidate.</p>
                    <a href="#" class="btn btn-light mt-auto" data-bs-toggle="collapse" data-bs-target="#createScheduleForm">
                        Aggiungi Data Visita
                    </a>

                    <!-- Form di creazione data visita -->
                    <div class="collapse mt-3" id="createScheduleForm">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="schedule_date" class="form-label">Data Visita</label>
                                <input type="date" name="schedule_date" id="schedule_date" class="form-control" required>
                            </div>
                            <button type="submit" name="create_schedule" class="btn btn-primary">Crea Data Visita</button>
                        </form>
                    </div>

                    <!-- Lista Date Visite -->
                    <h6 class="mt-4">Date Esistenti</h6>
                    <ul class="list-group">
                        <?php foreach ($schedules as $schedule): ?>
                            <li class="list-group-item">
                                Data: <?= htmlspecialchars($schedule['date']) ?>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="schedule_id" value="<?= $schedule['id'] ?>">
                                    <button type="submit" name="delete_schedule" class="btn btn-danger btn-sm float-end">
                                        Elimina
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Card: Gestione Guide -->
        <div class="col-md-4">
            <div class="card text-bg-warning shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">Guide</h5>
                    <p class="card-text">Gestisci il personale guida per gli eventi.</p>
                    <a href="#" class="btn btn-light mt-auto" data-bs-toggle="collapse" data-bs-target="#createGuideForm">
                        Aggiungi Guida
                    </a>

                    <!-- Form di creazione guida -->
                    <div class="collapse mt-3" id="createGuideForm">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="guide_name" class="form-label">Nome Guida</label>
                                <input type="text" name="guide_name" id="guide_name" class="form-control" required>
                            </div>
                            <button type="submit" name="create_guide" class="btn btn-primary">Crea Guida</button>
                        </form>
                    </div>

                    <!-- Lista Guide -->
                    <h6 class="mt-4">Guide Esistenti</h6>
                    <ul class="list-group">
                        <?php foreach ($guides as $guide): ?>
                            <li class="list-group-item">
                                <strong><?= htmlspecialchars($guide['name']) ?></strong>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="guide_id" value="<?= $guide['id'] ?>">
                                    <button type="submit" name="delete_guide" class="btn btn-danger btn-sm float-end">
                                        Elimina
                                    </button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-5">
        <a href="<?= $baseUrl ?>" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i> Torna alla Home
        </a>
    </div>
</div>

<?php require 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

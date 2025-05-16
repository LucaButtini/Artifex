<?php
// App/View/dashboard.php
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Dashboard Amministratore';
require 'header.php';
?>

<h1 class="mb-4 text-center text-danger"><strong>Dashboard Amministratore</strong></h1>

    <!-- 1) Contatori -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Eventi</h5>
                    <p class="card-text display-4"><?= /**@var $totEventi*/$totEventi ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Visite programmate</h5>
                    <p class="card-text display-4"><?=/**@var $totVisite*/ $totVisite ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Guide</h5>
                    <p class="card-text display-4"><?= /**@var $totGuide*/$totGuide ?></p>
                </div>
            </div>
        </div>
    </div>


    <!--  Elenco completo di Visite -->
    <h2>Visite guidate</h2>
    <a href="<?= $baseUrl ?>visits_create" class="btn btn-sm btn-success mb-3">Nuova Visita</a>
    <table class="table table-bordered mb-5">
        <thead>
        <tr>
            <th>ID Visita</th>
            <th>Titolo</th>
            <th>Durata</th>
            <th>Luogo</th>
            <th>Azioni</th>
        </tr>
        </thead>
        <tbody>
        <?php
        /**@var $visiteModel*/
        foreach ($visiteModel->showAll() as $v): ?>
            <tr>
                <td><?= $v['id_visita'] ?></td>
                <td><?= htmlspecialchars($v['titolo']) ?></td>
                <td><?= substr($v['durata_media'],0,5) ?></td>
                <td><?= htmlspecialchars($v['luogo']) ?></td>
                <td>
                    <a href="<?= $baseUrl ?>visits_edit/<?= $v['id_visita'] ?>" class="btn btn-sm btn-primary">Modifica</a>
                    <a href="<?= $baseUrl ?>visits_delete/<?= $v['id_visita'] ?>" class="btn btn-sm btn-danger">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 4) Elenco completo di Eventi -->
    <h2>Eventi</h2>
    <a href="<?= $baseUrl ?>events_create" class="btn btn-sm btn-success mb-3">Nuovo Evento</a>
    <table class="table table-bordered mb-5">
        <thead>
        <tr>
            <th>ID Evento</th>
            <th>Prezzo</th>
            <th>Min Persone</th>
            <th>Max Persone</th>
            <th>Guida</th>
            <th>Azioni</th>
        </tr>
        </thead>
        <tbody>
        <?php
        /**@var $eventModel*/
        foreach ($eventModel->showAll() as $e): ?>
            <tr>
                <td><?= $e['id_evento'] ?></td>
                <td>€<?= number_format($e['prezzo'],2,',','.') ?></td>
                <td><?= $e['min_persone'] ?></td>
                <td><?= $e['max_persone'] ?></td>
                <td><?= htmlspecialchars($e['guida']) ?></td>
                <td>
                    <a href="<?= $baseUrl ?>events_edit/<?= $e['id_evento'] ?>" class="btn btn-sm btn-primary">Modifica</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 5) Elenco completo di Guide -->
    <h2>Guide</h2>
    <a href="<?= $baseUrl ?>guides_create" class="btn btn-sm btn-success mb-3">Nuova Guida</a>
    <table class="table table-bordered mb-5">
        <thead>
        <tr>
            <th>ID Guida</th>
            <th>Nome</th>
            <th>Cognome</th>
            <th>Data Nascita</th>
            <th>Luogo Nascita</th>
            <th>Titolo di Studio</th>
            <th>Azioni</th>
        </tr>
        </thead>
        <tbody>
        <?php
        /**@var $guideModel*/
        foreach ($guideModel->showAll() as $g): ?>
            <tr>
                <td><?= $g['id_guida'] ?></td>
                <td><?= htmlspecialchars($g['nome']) ?></td>
                <td><?= htmlspecialchars($g['cognome']) ?></td>
                <td><?= date('d/m/Y', strtotime($g['data_nascita'])) ?></td>
                <td><?= htmlspecialchars($g['luogo_nascita']) ?></td>
                <td><?= htmlspecialchars($g['titolo_studio']) ?></td>
                <td>
                    <a href="<?= $baseUrl ?>guides_edit/<?= $g['id_guida'] ?>" class="btn btn-sm btn-primary">Modifica</a>
                    <a href="<?= $baseUrl ?>guides_delete/<?= $g['id_guida'] ?>" class="btn btn-sm btn-danger">Elimina</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php require 'footer.php'; ?>

<?php
// App/View/dashboard.php
$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');

require 'header.php';
?>

<div class="container mt-5">
    <h1 class="mb-4">Dashboard Amministratore</h1>

    <!-- Sezione statistiche -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Eventi</h5>
                    <p class="card-text">Totale Eventi: <?= htmlspecialchars($totEventi) ?></p>
                    <a href="<?= $baseUrl ?>/admin/events" class="btn btn-light">Visualizza Eventi</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Visite</h5>
                    <p class="card-text">Totale Visite: <?= htmlspecialchars($totVisite) ?></p>
                    <a href="<?= $baseUrl ?>/admin/schedules" class="btn btn-light">Visualizza Visite</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Guide</h5>
                    <p class="card-text">Totale Guide: <?= htmlspecialchars($totGuide) ?></p>
                    <a href="<?= $baseUrl ?>/admin/guides" class="btn btn-light">Visualizza Guide</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Eventi recenti -->
    <h3 class="mb-3">Eventi Recenti</h3>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID Evento</th><th>Nome Evento</th><th>Data</th><th>Azioni</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($eventModel)): ?>
            <?php foreach ($eventModel->showAll() as $event): ?>
                <tr>
                    <td><?= htmlspecialchars($event['id_evento']) ?></td>
                    <td><?= htmlspecialchars($event['nome']) ?></td>
                    <td><?= htmlspecialchars($event['data_evento']) ?></td>
                    <td>
                        <a href="<?= $baseUrl ?>/admin/events/edit/<?= htmlspecialchars($event['id_evento']) ?>" class="btn btn-sm btn-primary">Modifica</a>
                        <form action="<?= $baseUrl ?>/admin/events/delete" method="POST" style="display:inline">
                            <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['id_evento']) ?>">
                            <button class="btn btn-sm btn-danger">Elimina</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Nessun evento disponibile.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Link di navigazione per gestire i dati -->
    <div class="mt-4">
        <a href="<?= $baseUrl ?>/admin/events/create" class="btn btn-success">Aggiungi Evento</a>
        <a href="<?= $baseUrl ?>/admin/schedules/create" class="btn btn-success">Aggiungi Programmazione</a>
        <a href="<?= $baseUrl ?>/admin/guides/create" class="btn btn-success">Aggiungi Guida</a>
    </div>
</div>

<?php require 'footer.php'; ?>

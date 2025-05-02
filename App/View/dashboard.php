<?php
// App/View/dashboard.php
$appConfig = require dirname(__DIR__,2) . '/appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
require 'header.php';
?>
<div class="container mt-5">
    <h1 class="mb-4">Dashboard Amministratore</h1>

    <!-- Statistiche -->
    <div class="row mb-4">
        <?php foreach ([
                           ['label'=>'Eventi','count'=>$totEventi,'route'=>'events','bg'=>'primary'],
                           ['label'=>'Visite','count'=>$totVisite,'route'=>'schedules','bg'=>'success'],
                           ['label'=>'Guide','count'=>$totGuide,'route'=>'guides','bg'=>'warning'],
                       ] as $card): ?>
            <div class="col-md-4 mb-3">
                <div class="card text-white bg-<?= $card['bg'] ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= $card['label'] ?></h5>
                        <p class="card-text">Totale <?= $card['label'] ?>: <?= htmlspecialchars($card['count']) ?></p>
                        <a href="<?= $baseUrl ?>/admin/<?= $card['route'] ?>" class="btn btn-light">
                            Visualizza <?= $card['label'] ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Eventi Recenti -->
    <h3>Eventi Recenti</h3>
    <table class="table table-striped mb-4">
        <thead>
        <tr>
            <th>ID</th>
            <th>Titolo Visita</th>
            <th>Data/Ora</th>
            <th>Azioni</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($recentEvents)): ?>
            <?php foreach ($recentEvents as $ev): ?>
                <tr>
                    <td><?= htmlspecialchars($ev['id_evento']) ?></td>
                    <td><?= htmlspecialchars($ev['titolo']) ?></td>
                    <td><?= htmlspecialchars($ev['data_visita']) ?></td>
                    <td>
                        <a href="<?= $baseUrl ?>/admin/events/edit/<?= htmlspecialchars($ev['id_evento']) ?>"
                           class="btn btn-sm btn-primary">Modifica</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Nessuna programmazione trovata.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>

    <!-- Link rapide -->
    <div class="mb-5">
        <a href="<?= $baseUrl ?>/admin/events/create"    class="btn btn-success">Aggiungi Evento</a>
        <a href="<?= $baseUrl ?>/admin/schedules/create" class="btn btn-success">Aggiungi Programmazione</a>
        <a href="<?= $baseUrl ?>/admin/guides/create"    class="btn btn-success">Aggiungi Guida</a>
    </div>
</div>
<?php require 'footer.php'; ?>

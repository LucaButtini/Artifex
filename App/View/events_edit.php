<?php
// App/View/events_edit.php
$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Modifica evento';
require 'header.php';
?>
<div class="container mt-5">
    <h2>Modifica Evento #<?= htmlspecialchars($event['id_evento']) ?></h2>
    <form action="<?= $baseUrl ?>/admin/events/update" method="POST">
        <input type="hidden" name="event_id" value="<?= htmlspecialchars($event['id_evento']) ?>">

        <div class="mb-3">
            <label for="prezzo" class="form-label">Prezzo</label>
            <input type="number" step="0.01" class="form-control" id="prezzo" name="event_price"
                   value="<?= htmlspecialchars($event['prezzo']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="min_persone" class="form-label">Min Persone</label>
            <input type="number" class="form-control" id="min_persone" name="min_persone"
                   value="<?= htmlspecialchars($event['min_persone']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="max_persone" class="form-label">Max Persone</label>
            <input type="number" class="form-control" id="max_persone" name="max_persone"
                   value="<?= htmlspecialchars($event['max_persone']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="guida" class="form-label">ID Guida</label>
            <input type="number" class="form-control" id="guida" name="event_guide"
                   value="<?= htmlspecialchars($event['guida']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        <a href="<?= $baseUrl ?>admin/dashboard" class="btn btn-secondary">Annulla</a>
    </form>
</div>
<?php require 'footer.php'; ?>

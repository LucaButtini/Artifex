<?php
// App/View/events_edit.php
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Modifica evento';
require 'header.php';
?>
<div class="container mt-5">
    <h2>Modifica Evento #<?= $event['id_evento'] ?></h2>
    <form action="<?= $baseUrl ?>/admin/events_edit/<?= $event['id_evento'] ?>" method="POST">
        <div class="mb-3">
            <label for="prezzo" class="form-label">Prezzo</label>
            <input type="number" step="0.01" class="form-control" id="prezzo" name="prezzo"
                   value="<?= $event['prezzo'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="min_persone" class="form-label">Min Persone</label>
            <input type="number" class="form-control" id="min_persone" name="min_persone"
                   value="<?= $event['min_persone'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="max_persone" class="form-label">Max Persone</label>
            <input type="number" class="form-control" id="max_persone" name="max_persone"
                   value="<?= $event['max_persone'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="guida" class="form-label">ID Guida</label>
            <input type="number" class="form-control" id="guida" name="guida"
                   value="<?= $event['guida'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
        <a href="<?= $baseUrl ?>/dashboard" class="btn btn-secondary">Annulla</a>
    </form>
</div>
<?php require 'footer.php'; ?>

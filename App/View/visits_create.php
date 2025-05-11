<?php
// App/View/visits_create.php

$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Crea Guida';

require 'header.php';
?>
<div class="container mt-5">
    <h2>Crea Nuova Visita Guidata</h2>
    <form action="<?= $baseUrl ?>/admin/visits_create" method="POST">
        <div class="mb-3">
            <label for="titolo" class="form-label">Titolo</label>
            <input type="text" class="form-control" id="titolo" name="titolo" required>
        </div>
        <div class="mb-3">
            <label for="durata_media" class="form-label">Durata (HH:MM:SS)</label>
            <input type="text" class="form-control" id="durata_media" name="durata_media" required>
        </div>
        <div class="mb-3">
            <label for="luogo" class="form-label">Luogo</label>
            <input type="text" class="form-control" id="luogo" name="luogo" required>
        </div>
        <button type="submit" class="btn btn-success">Crea</button>
        <a href="<?= $baseUrl ?>/dashboard" class="btn btn-secondary">Annulla</a>
    </form>
</div>
<?php require 'footer.php'; ?>

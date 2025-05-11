<?php
// App/View/guides_create.php
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Crea Guida';
require 'header.php';
?>
<div class="container mt-5">
    <h2>Crea Nuova Guida</h2>
    <form action="<?= $baseUrl ?>guides_create" method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
        </div>
        <div class="mb-3">
            <label for="cognome" class="form-label">Cognome</label>
            <input type="text" class="form-control" id="cognome" name="cognome" required>
        </div>
        <div class="mb-3">
            <label for="data_nascita" class="form-label">Data di Nascita</label>
            <input type="date" class="form-control" id="data_nascita" name="data_nascita" required>
        </div>
        <div class="mb-3">
            <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
            <input type="text" class="form-control" id="luogo_nascita" name="luogo_nascita" required>
        </div>
        <button type="submit" class="btn btn-success">Crea</button>
        <a href="<?= $baseUrl ?>dashboard" class="btn btn-secondary">Annulla</a>
    </form>
</div>
<?php require 'footer.php'; ?>

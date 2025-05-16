<?php
// App/View/guides_delete.php
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Elimina guida';
require 'header.php';
?>
    <div class="container mt-5">
        <h2>Elimina Guida #<?= htmlspecialchars($guide['id_guida']) ?></h2>
        <p>Sei sicuro di voler eliminare la guida <?= htmlspecialchars($guide['nome'] . ' ' . $guide['cognome']) ?>?</p>
        <form action="<?= $baseUrl ?>/guides_delete/<?= $guide['id_guida'] ?>" method="POST">
            <button type="submit" class="btn btn-danger">Conferma Eliminazione</button>
            <a href="<?= $baseUrl ?>/admin/dashboard" class="btn btn-secondary">Annulla</a>
        </form>
    </div>
<?php require 'footer.php'; ?>
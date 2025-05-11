<?php
// App/View/events_delete.php
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Elimina evento';
require 'header.php';
?>
<div class="container mt-5">
    <h2>Elimina Evento #<?= $event['id_evento'] ?></h2>
    <p>Sei sicuro di voler eliminare l’evento con prezzo €<?= number_format($event['prezzo'],2,',','.') ?>?</p>
    <form action="<?= $baseUrl ?>/admin/events_delete/<?= $event['id_evento'] ?>" method="POST">
        <button type="submit" class="btn btn-danger">Elimina</button>
        <a href="<?= $baseUrl ?>/dashboard" class="btn btn-secondary">Annulla</a>
    </form>
</div>
<?php require 'footer.php'; ?>

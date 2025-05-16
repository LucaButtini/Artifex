<?php
// App/View/visits_delete.php
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title     = 'Elimina Visita';
require 'header.php';
?>
    <div class="container mt-5">
        <h2>Elimina Visita Guidata #<?= /**@var $visit*/htmlspecialchars($visit['id_visita']) ?></h2>
        <p>Sei sicuro di voler eliminare la visita "<?= htmlspecialchars($visit['titolo']) ?>"?</p>
        <form action="<?= $baseUrl ?>visits_delete/<?= $visit['id_visita'] ?>" method="POST">
            <button type="submit" class="btn btn-danger">Conferma Eliminazione</button>
            <a href="<?= $baseUrl ?>/admin/dashboard" class="btn btn-secondary">Annulla</a>
        </form>
    </div>
<?php require 'footer.php'; ?>
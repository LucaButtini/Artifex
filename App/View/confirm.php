<?php
$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Conferma</title>
</head>
<body>

<div class="card text-center p-4 rounded-4">
    <div class="card-body">
        <i class="bi bi-check-circle text-success display-4"></i>
        <h1 class="text-success mt-3">Operazione effettuata con successo!</h1>
        <p class="lead">Il tuo aggiornamento è stato salvato correttamente.</p>
        <a href="<?= $baseUrl ?>" class="btn btn-primary mt-3">Torna alla Home</a>
    </div>
</div>

</body>
</html>

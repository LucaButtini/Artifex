<?php
$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Errore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card text-center p-4 rounded-4 shadow" style="max-width: 400px;">
        <div class="card-body">
            <i class="bi bi-x-circle text-danger display-4"></i>
            <h1 class="text-danger mt-3">Si è verificato un errore!</h1>
            <p class="lead">Qualcosa è andato storto durante l'operazione.</p>
            <a href="<?= $baseUrl ?>" class="btn btn-outline-danger mt-3">Torna alla Home</a>
        </div>
    </div>
</div>

</body>
</html>

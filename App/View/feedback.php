<?php
$title= "Feedback";

$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl   = $appConfig['baseURL'] . $appConfig['prjName'];
require 'header.php'; ?>

<div class="container mt-5">
    <div class="alert alert-<?= htmlspecialchars($messageType) ?> text-center">
        <?= htmlspecialchars($message) ?>
    </div>
    <div class="text-center mt-3">
        <a href="<?= $baseUrl ?>" class="btn btn-outline-primary">Torna alla home</a>
    </div>
</div>

<?php require 'footer.php'; ?>

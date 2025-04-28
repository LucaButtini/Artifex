<?php
// App/View/profile.php

$appConfig = require dirname(__DIR__,2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = $appConfig['baseURL'] . $appConfig['prjName'];
$title     = 'Il Mio Profilo';

require 'header.php';

$visitor = $visitor ?? null;
$admin   = $admin   ?? null;
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Il Mio Profilo</h2>

    <div class="row">
        <!-- Colonna dei dati profilo -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php
                        // Verifica la presenza di un admin o visitor e mostra il nome
                        if ($admin): ?>
                            Amministratore: <?= $admin['username'] ?>
                        <?php elseif ($visitor): ?>
                            Visitatore: <?= $visitor['nome'] ?>
                        <?php else: ?>
                            Utente sconosciuto
                        <?php endif; ?>
                    </h5>

                    <?php if ($admin || $visitor): ?>
                        <ul class="list-group list-group-flush">
                            <?php if ($visitor): ?>
                                <li class="list-group-item"><strong>Email:</strong> <?= $visitor['email'] ?></li>
                                <li class="list-group-item"><strong>Nazionalità:</strong> <?= $visitor['nazionalita'] ?></li>
                                <li class="list-group-item"><strong>Telefono:</strong> <?= $visitor['telefono'] ?></li>
                                <li class="list-group-item"><strong>Lingua base:</strong> <?= $visitor['lingua_nome'] ?? 'Non specificata' ?></li>
                            <?php elseif ($admin): ?>
                                <li class="list-group-item"><strong>Email:</strong> <?= $admin['email'] ?></li>
                            <?php endif; ?>
                        </ul>
                    <?php elseif (!$admin && !$visitor): ?>
                        <p>Non sei loggato. Effettua il login o registrati.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Sezione cambio password -->
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">Modifica Password</h5>

            <?php if (isset($pwdError)): ?>
                <div class="alert alert-danger"><?= $pwdError ?></div>
            <?php elseif (isset($pwdSuccess)): ?>
                <div class="alert alert-success"><?= $pwdSuccess ?></div>
            <?php endif; ?>

            <form action="<?= $baseUrl ?>profile/changePassword" method="POST" class="row g-3">
                <div class="col-md-4">
                    <label for="old_password" class="form-label">Password Attuale</label>
                    <input type="password" class="form-control" id="old_password" name="old_password" required>
                </div>
                <div class="col-md-4">
                    <label for="new_password" class="form-label">Nuova Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
                <div class="col-md-4">
                    <label for="confirm_password" class="form-label">Conferma Nuova</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-dark">Aggiorna Password</button>
                </div>
            </form>
        </div>
    </div>

</div>

<?php require 'footer.php'; ?>

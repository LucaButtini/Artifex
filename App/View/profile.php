<?php
// App/View/profile.php
//$bookings = $bookings ?? [];
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl = rtrim($appConfig['baseURL'] . $appConfig['prjName'], '/');
$title   = 'Il Mio Profilo';

require 'header.php';

$visitor = $_SESSION['visitor'] ?? null;
$admin = $_SESSION['admin'] ?? null;
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Il Mio Profilo</h2>

    <?php if (isset($admin['username']) || isset($visitor['nome'])): ?>
        <div class="row">
            <!-- Colonna dei dati profilo -->
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">
                            <?php if (isset($admin['username'])): ?>
                                Amministratore: <?= $admin['username'] ?>
                            <?php elseif (isset($visitor['nome'])): ?>
                                Visitatore: <?= $visitor['nome'] ?>
                            <?php endif; ?>
                        </h5>

                        <ul class="list-group list-group-flush">
                            <?php if (isset($visitor['nome'])): ?>
                                <li class="list-group-item"><strong>Email:</strong> <?= $visitor['email'] ?></li>
                                <li class="list-group-item"><strong>Nazionalità:</strong> <?= $visitor['nazionalita'] ?></li>
                                <li class="list-group-item"><strong>Telefono:</strong> <?= $visitor['telefono'] ?></li>
                                <li class="list-group-item"><strong>Lingua base:</strong> <?= $visitor['lingua_nome'] ?? 'Non specificata' ?></li>
                            <?php elseif (isset($admin['username'])): ?>
                                <li class="list-group-item"><strong>Email:</strong> <?= $admin['email'] ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($visitor)): ?>
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h5 class="card-title">Le tue prenotazioni confermate</h5>

                    <?php if (empty($bookings)): ?>
                        <p>Non hai prenotazioni confermate.</p>
                    <?php else: ?>
                        <ul class="list-group">
                            <?php foreach ($bookings as $booking):
                                ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($booking['titolo']) ?></strong><br>
                                    Data: <?= date('d/m/Y H:i', strtotime($booking['data_visita'])) ?><br>
                                    Luogo: <?= htmlspecialchars($booking['luogo']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>


        <!-- Sezione cambio password -->
        <div class="card shadow-sm mt-4">
            <div class="card-body">
                <h5 class="card-title">Modifica Password</h5>


                <?php if (isset($pwdError)): ?>
                    <div class="alert alert-danger"><?= $pwdError?></div>
                <?php elseif (isset($pwdSuccess)): ?>
                    <div class="alert alert-success"><?= $pwdSuccess ?></div>
                <?php endif; ?>

                <?php if($_SESSION['visitor']) {?>
                <form action="<?= $baseUrl ?>visitor/changePwd" method="POST" class="row g-3">
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
                <?php }else{
                //if($_SESSION['admin']) {
                ?>
                    <form action="<?= $baseUrl ?>admin/changePwd" method="POST" class="row g-3">
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
                <?php }?>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            Non sei loggato. Effettua il login o registrati.
        </div>
    <?php endif; ?>
</div>

<?php require 'footer.php'; ?>

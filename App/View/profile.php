<?php
// App/View/profile.php

// Recupera baseUrl e titolo
$appConfig = require dirname(__DIR__,2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl   = $appConfig['baseURL'] . $appConfig['prjName'];
$title     = 'Il Mio Profilo';

require 'header.php';
?>

<div class="container mt-5 mb-5">
    <h2 class="mb-4">Il Mio Profilo</h2>

    <div class="row">

        <!-- Sezione dati profilo -->
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><?= isset($_SESSION['admin'])
                            ? 'Amministratore: ' . htmlspecialchars($_SESSION['admin'])
                            : 'Visitatore: ' . htmlspecialchars($visitor['nome'])
                        ?></h5>
                    <ul class="list-group list-group-flush">
                        <?php if (isset($visitor)): // mostro solo per visitatori ?>
                            <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($visitor['email']) ?></li>
                            <li class="list-group-item"><strong>Nazionalità:</strong> <?= htmlspecialchars($visitor['nazionalita']) ?></li>
                            <li class="list-group-item"><strong>Telefono:</strong> <?= htmlspecialchars($visitor['telefono']) ?></li>
                            <li class="list-group-item"><strong>Lingua base:</strong> <?= htmlspecialchars($visitor['lingua_nome']) ?></li>
                        <?php else: // amministratore ?>
                            <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($admin['email']) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sezione prenotazioni (solo visitatori) -->
        <?php if (isset($visitor)): ?>
            <div class="col-md-8">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">I Miei Eventi Prenotati</h5>
                        <?php if (empty($bookings)): ?>
                            <p class="text-muted">Nessuna prenotazione futura.</p>
                        <?php else: ?>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th>Data &amp; Ora</th>
                                    <th>Luogo</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($bookings as $b): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($b['titolo']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($b['data_visita'])) ?></td>
                                        <td><?= htmlspecialchars($b['luogo']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- Sezione cambio password -->
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Modifica Password</h5>
            <?php if (isset($pwdError)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($pwdError) ?></div>
            <?php elseif (isset($pwdSuccess)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($pwdSuccess) ?></div>
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

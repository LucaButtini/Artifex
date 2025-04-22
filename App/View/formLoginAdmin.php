<?php
$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];

$title = "Login Amministratore";
require 'header.php';
?>

<div class="d-flex justify-content-center align-items-center">
    <div class="card shadow" style="width: 350px;">
        <div class="card-body">
            <h2 class="text-center text-danger mb-4">Login Amministratore</h2>

            <?php if (isset($error)){ ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php } ?>

            <form action="<?= $baseUrl ?>login/admin" method="POST">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Inserisci il tuo nome" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci la tua email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci la password" required>
                </div>

                <button type="submit" class="btn btn-dark w-100">Accedi</button>
            </form>

            <p class="text-center mt-3">
                Non hai un account? <a href="<?= $baseUrl ?>form/insert/visitor">Registrati</a>
            </p>

            <p class="text-center mt-1">
                Sei un visitatore? <a href="<?= $baseUrl ?>form/login/visitor">Accedi al tuo account</a>
            </p>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>

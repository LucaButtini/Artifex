<?php
$appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
$baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];

$fields = require dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'Attributes' . DIRECTORY_SEPARATOR . 'visitatoriAttributes.php';

$title = "Registrazione";
require 'header.php';
?>

<div class="d-flex justify-content-center align-items-center">
    <div class="card shadow" style="width: 350px;">
        <div class="card-body">
            <h2 class="text-center text-primary mb-4">Registrati</h2>


            <?php if (isset($error)){ ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php }; ?>

            <!-- Messaggio di successo -->
            <?php if (isset($success)){ ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php } ?>

            <form action="<?= $baseUrl ?>insert/onevisitor" method="POST">
                <?php foreach ($fields as $name => $props){ ?>
                    <div class="mb-3">
                        <label for="<?= $name ?>" class="form-label"><?= $props['label'] ?>:</label>
                        <input
                                type="<?= $props['type'] ?>"
                                class="form-control"
                                id="<?= $name ?>"
                                name="<?= $name ?>"
                                placeholder="Inserisci <?= $props['label'] ?>"
                                required
                        >
                    </div>
                <?php } ?>

                <button type="submit" class="btn btn-dark w-100">Registrati</button>
            </form>

            <p class="text-center mt-3">
                Hai già un account? <a href="<?= $baseUrl ?>form/login/visitor">Accedi</a>
            </p>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>

<?php
$title = "Registrazione";
$fields = require 'App\Attributes\visitatoriAttributes.php';
require 'header.php';
?>

<div class="d-flex justify-content-center align-items-center">
    <div class="card shadow" style="width: 350px;">
        <div class="card-body">
            <h2 class="text-center text-primary mb-4">Registrati</h2>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php endif; ?>

            <form action="formRegistration.php" method="POST">
                <?php foreach ($fields as $name => $props): ?>
                    <div class="mb-3">
                        <label for="<?= $name ?>" class="form-label"><?= $props['label'] ?>:</label>
                        <input type="<?= $props['type'] ?>" class="form-control" id="<?= $name ?>" name="<?= $name ?>" placeholder="Inserisci <?= $props['label'] ?>" required>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-primary w-100">Registrati</button>
            </form>

            <p class="text-center mt-3">
                Hai già un account? <a href="/artifex/login">Accedi</a>
            </p>
        </div>
    </div>
</div>

<?php require 'footer.php'; ?>

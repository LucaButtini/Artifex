<?php require 'header.php'; ?>
<div class="container mt-5">
    <h1 class="mb-4">Modifica Guida</h1>
    <form method="POST" action="admin/guides/update">
        <input type="hidden" name="id" value="<?= $guida['id_guida'] ?? '' ?>">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" value="<?= htmlspecialchars($guida['nome'] ?? '') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
    </form>
</div>
<?php require 'footer.php'; ?>

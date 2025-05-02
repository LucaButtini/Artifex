<?php require 'header.php'; ?>
<div class="container mt-5">
    <h1 class="mb-4">Crea Nuova Guida</h1>
    <form method="POST" action="admin/guides/create">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Crea</button>
    </form>
</div>
<?php require 'footer.php'; ?>

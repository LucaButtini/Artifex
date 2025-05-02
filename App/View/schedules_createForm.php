<?php require 'header.php'; ?>
<div class="container mt-5">
    <h1 class="mb-4">Crea Nuova Programmazione</h1>
    <form method="POST" action="admin/schedules/create">
        <div class="mb-3">
            <label for="data" class="form-label">Data e Ora</label>
            <input type="datetime-local" name="data" id="data" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Crea</button>
    </form>
</div>
<?php require 'footer.php'; ?>

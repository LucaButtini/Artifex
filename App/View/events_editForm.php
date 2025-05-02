<?php require 'header.php'; ?>
<div class="container mt-5">
    <h1 class="mb-4">Modifica Evento</h1>
    <form method="POST" action="admin/events/update">
        <input type="hidden" name="id" value="<?= $evento['id_evento'] ?? '' ?>">
        <div class="mb-3">
            <label for="titolo" class="form-label">Titolo</label>
            <input type="text" name="titolo" id="titolo" class="form-control" value="<?= htmlspecialchars($evento['titolo'] ?? '') ?>" required>
        </div>
        <div class="mb-3">
            <label for="descrizione" class="form-label">Descrizione</label>
            <textarea name="descrizione" id="descrizione" class="form-control"><?= htmlspecialchars($evento['descrizione'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="data_visita" class="form-label">Data Visita</label>
            <input type="datetime-local" name="data_visita" id="data_visita" class="form-control" value="<?= htmlspecialchars($evento['data_visita'] ?? '') ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
    </form>
</div>
<?php require 'footer.php'; ?>

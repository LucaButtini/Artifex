<?php require 'header.php'; ?>
<div class="container mt-5">
    <h1 class="mb-4">Crea Nuovo Evento</h1>
    <form method="POST" action="admin/events/create">
        <div class="mb-3">
            <label for="titolo" class="form-label">Titolo</label>
            <input type="text" name="titolo" id="titolo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="descrizione" class="form-label">Descrizione</label>
            <textarea name="descrizione" id="descrizione" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="data_visita" class="form-label">Data Visita</label>
            <input type="datetime-local" name="data_visita" id="data_visita" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Crea Evento</button>
    </form>
</div>
<?php require 'footer.php'; ?>

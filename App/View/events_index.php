<?php
require 'header.php';
?>
<div class="container mt-5">
    <h1 class="mb-4">Gestione Eventi</h1>
    <a href="events_createForm.php" class="btn btn-primary mb-3">+ Aggiungi Nuovo Evento</a>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Titolo</th>
            <th>Data</th>
            <th>Azioni</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($eventi)): ?>
            <?php foreach ($eventi as $ev): ?>
                <tr>
                    <td><?= htmlspecialchars($ev['id_evento']) ?></td>
                    <td><?= htmlspecialchars($ev['titolo']) ?></td>
                    <td><?= htmlspecialchars($ev['data_visita']) ?></td>
                    <td>
                        <a href="events_editForm.php?id=<?= htmlspecialchars($ev['id_evento']) ?>" class="btn btn-sm btn-warning">Modifica</a>
                        <form method="POST" action="admin/events/delete" style="display:inline;">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($ev['id_evento']) ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Elimina</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4">Nessun evento trovato.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
<?php require 'footer.php'; ?>

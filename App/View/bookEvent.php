<?php
// App/View/bookEvent.php
$title = 'Prenota Evento';
require 'header.php';

// $evento contiene:
//  id_evento, prezzo, min_persone, max_persone, guida,
//  titolo_visita, durata_media, luogo, data_visita
?>

<div class="container mt-5">
    <h1 class="mb-4"><?= htmlspecialchars($evento['titolo_visita']) ?></h1>

    <?php
    // durata
    [$h,$m,] = explode(':', $evento['durata_media']);
    $data = date("d/m/Y H:i", strtotime($evento['data_visita']));
    ?>

    <ul class="list-unstyled">
        <li><strong>Luogo:</strong> <?= htmlspecialchars($evento['luogo']) ?></li>
        <li><strong>Data:</strong> <?= $data ?></li>
        <li><strong>Durata:</strong> <?= (int)$h ?>h <?= (int)$m ?>m</li>
        <li><strong>Prezzo:</strong> €<?= number_format($evento['prezzo'], 2, ',', '.') ?></li>
        <li><strong>Partecipanti:</strong> <?= $evento['min_persone'] ?>–<?= $evento['max_persone'] ?></li>
        <li><strong>Guida:</strong>
            <?= htmlspecialchars($evento['guida_nome'] . ' ' . $evento['guida_cognome']) ?>
        </li>
    </ul>


    <form method="POST" action="/Artifex/home/book-events" class="mt-4">
        <input type="hidden" name="id_evento" value="<?= htmlspecialchars($evento['id_evento']) ?>">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="confirm" id="confirm" value="yes" required>
            <label class="form-check-label" for="confirm">
                Confermo la prenotazione di questo evento.
            </label>
        </div>
        <button type="submit" class="btn btn-dark">Prenota Ora</button>
        <a href="/Artifex/events" class="btn btn-secondary ms-2">Annulla</a>
    </form>
</div>

<?php require 'footer.php'; ?>

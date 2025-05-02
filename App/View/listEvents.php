<?php
$title = 'Elenco Eventi';
require 'header.php';
?>

    <h1 class="mb-4 text-center">Eventi Programmati</h1>

    <?php if (empty($eventi)): ?>
        <p class="text-center">Nessun evento disponibile.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($eventi as $evento): ?>
                <?php
                $durata_parts = explode(':', $evento['durata_media']);
                $ore = (int)$durata_parts[0];
                $minuti = (int)$durata_parts[1];
                $data_formattata = date("d/m/Y H:i", strtotime($evento['data_visita']));
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($evento['titolo_visita']) ?></h5>
                            <ul class="list-unstyled mt-2 flex-grow-1">
                                <li><strong>Luogo:</strong> <?= htmlspecialchars($evento['luogo']) ?></li>
                                <li><strong>Data:</strong> <?= $data_formattata ?></li>
                                <li><strong>Durata:</strong> <?= $ore ?> ore <?= $minuti ?> minuti</li>
                                <li><strong>Prezzo:</strong> €<?= number_format($evento['prezzo'], 2) ?></li>
                                <li><strong>Partecipanti:</strong> <?= $evento['min_persone'] ?>–<?= $evento['max_persone'] ?></li>
                                <li><strong>Guida ID:</strong> <?= $evento['guida'] ?></li>
                            </ul>
                            <a href="/artifex/home/book-events?id=<?= $evento['id_evento'] ?>" class="btn btn-dark mt-3">Prenota</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


<?php
require 'footer.php';
?>

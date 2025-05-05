<?php
$title = 'Il tuo Carrello';
require 'header.php';
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Il tuo Carrello</h1>

    <?php if (empty($eventDs)): ?>
        <div class="alert alert-warning text-center">
            Non hai ancora prenotato nessun evento.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($eventDs as $evento):
                // calcolo durata e data
                [$h,$m] = explode(':',$evento['durata_media']);
                $data = date("d/m/Y H:i", strtotime($evento['data_visita']));
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($evento['titolo_visita']) ?></h5>
                            <ul class="list-unstyled mt-2 flex-grow-1">
                                <li><strong>Luogo:</strong> <?= htmlspecialchars($evento['luogo']) ?></li>
                                <li><strong>Data:</strong> <?= $data ?></li>
                                <li><strong>Durata:</strong> <?= (int)$h ?>h <?= (int)$m ?>m</li>
                                <li><strong>Prezzo:</strong> €<?= number_format($evento['prezzo'],2,',','.') ?></li>
                                <li><strong>Partecipanti:</strong> <?= $evento['min_persone'] ?>–<?= $evento['max_persone'] ?></li>
                                <li><strong>Guida:</strong> <?= htmlspecialchars($evento['guida_nome'].' '.$evento['guida_cognome']) ?></li>
                            </ul>
                            <form method="POST" action="/Artifex/home/book-events" class="mt-auto">
                                <!-- qui puoi mettere una rotta per rimuovere dal carrello -->
                                <button type="button" class="btn btn-secondary w-100" disabled>
                                    Confermato
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="/Artifex/home/events" class="btn btn-outline-primary">Torna agli eventi</a>
    </div>
</div>

<?php require 'footer.php'; ?>

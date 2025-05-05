<?php
$title = 'Il tuo Carrello';
require 'header.php';
?>


    <h1 class="text-center mb-4">Il tuo Carrello</h1>

    <?php if (empty($eventDs)): ?>
        <div class="alert alert-warning text-center">
            Non hai ancora prenotato nessun evento.
        </div>
    <?php else: ?>
        <div class="row">
            <?php
            $totalPrice = 0; // Variabile per il totale
            foreach ($eventDs as $evento):
                // calcolo durata e data
                [$h, $m] = explode(':', $evento['durata_media']);
                $data = date("d/m/Y H:i", strtotime($evento['data_visita']));
                $totalPrice += $evento['prezzo']; // Aggiungi il prezzo al totale
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($evento['titolo_visita']) ?></h5>
                            <ul class="list-unstyled mt-2 flex-grow-1">
                                <li><strong>Luogo:</strong> <?= htmlspecialchars($evento['luogo']) ?></li>
                                <li><strong>Data:</strong> <?= $data ?></li>
                                <li><strong>Durata:</strong> <?= (int)$h ?>h <?= (int)$m ?>m</li>
                                <li><strong>Prezzo:</strong> €<?= number_format($evento['prezzo'], 2, ',', '.') ?></li>
                                <li><strong>Guida:</strong> <?= htmlspecialchars($evento['guida_nome'] . ' ' . $evento['guida_cognome']) ?></li>
                            </ul>
                            <form method="POST" action="/Artifex/cart/remove" class="mt-auto">
                                <input type="hidden" name="id_evento" value="<?= $evento['id_evento'] ?>">
                                <button type="submit" class="btn btn-danger w-100">Rimuovi</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <h4>Totale: €<?= number_format($totalPrice, 2, ',', '.') ?></h4>
            <a href="/Artifex/payment" class="btn btn-success">Vai al pagamento</a>
        </div>

    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="/Artifex/home/events" class="btn btn-outline-primary">Torna agli eventi</a>
    </div>


<?php require 'footer.php'; ?>

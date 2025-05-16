<?php
$title= "Checkout";

$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl   = $appConfig['baseURL'] . $appConfig['prjName'];

require 'header.php'; ?>

<div class="container mt-5">
    <h1 class="display-4 text-center""><strong>Conferma il pagamento</strong></h1>

    <?php if (empty($events)): //controllo in più in caso di errori?>
        <div class="alert alert-warning text-center">
            Il tuo carrello è vuoto.
        </div>
        <div class="text-center mt-3">
            <a href="<?= $baseUrl?>cart" class="btn btn-outline-primary">Torna al carrello</a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php $total = 0; foreach ($events as $e):
                [$h,$m] = explode(':',$e['durata_media']);
                $data   = date("d/m/Y H:i", strtotime($e['data_visita']));
                $total += $e['prezzo'];
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($e['titolo_visita']) ?></h5>
                            <p class="flex-grow-1">
                                <strong>Luogo:</strong> <?= htmlspecialchars($e['luogo']) ?><br>
                                <strong>Data:</strong> <?= $data ?><br>
                                <strong>Durata:</strong> <?= (int)$h ?>h <?= (int)$m ?>m<br>
                                <strong>Guida:</strong> <?= htmlspecialchars($e['guida_nome'].' '.$e['guida_cognome']) ?>
                            </p>
                            <p><strong>Prezzo:</strong> €<?= number_format($e['prezzo'],2,',','.') ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <h3 class="text-end">Totale da pagare: €<?= number_format($total,2,',','.') ?></h3>

        <div class="text-center mt-4">
            <form method="POST" action="<?= $baseUrl ?>cart/checkout" class="d-inline">
                <button type="submit" class="btn btn-success btn-lg">Conferma Pagamento</button>
            </form>
            <form method="POST" action="<?= $baseUrl ?>cart/pdf-preview" class="d-inline ms-2">
                <button type="submit" class="btn btn-secondary btn-lg">Scarica Biglietto in PDF</button>
            </form>
            <a href="<?= $baseUrl ?>cart" class="btn btn-lg ms-2 btn-outline-primary">Annulla</a>
        </div>

    <?php endif; ?>
</div>

<?php require 'footer.php'; ?>

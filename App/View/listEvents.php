<?php
$title = 'Elenco Eventi';
require 'header.php';
?>

<div class="text-center mt-5 mb-5">
    <h1 class="display-4"><strong>Eventi Culturali</strong></h1>
    <p class="lead" style="max-width: 700px; margin: 1rem auto;">
       I nostri eventi,
        scopri le date e le guide che ti accompagnero nelle visite!
    </p>

    <!--<div class="mb-4">
        <img src="/Artifex/Public/Immagini/artifex-service2-1.webp" alt="Immagine home Artifex" class="img-fluid rounded shadow home-image" id="imEv">
    </div>-->
    <div style="height: 1200px; overflow: hidden; border-radius: 10px;">
        <img src="/Artifex/Public/Immagini/artifex-service2-1.webp"
             alt="Immagine home Artifex"
             class=" w-100 img-fluid rounded shadow home-image"
             style="object-fit: cover; object-position: top;">
    </div>

</div>

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
                                <li><strong>Guida:</strong> <?= htmlspecialchars($evento['guida_nome'] . ' ' . $evento['guida_cognome']) ?></li>

                            </ul>
                            <a href="/Artifex/home/book-events?id=<?= $evento['id_evento'] ?>" class="btn btn-dark mt-3">Prenota</a>


                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


<?php
require 'footer.php';
?>

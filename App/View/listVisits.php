<?php
$title = 'Elenco Visite';
require 'header.php';
?>


    <div class="text-center mt-5 mb-5">
        <h1 class="display-4"><strong>Visite Guidate</strong></h1>
        <p class="lead" style="max-width: 700px; margin: 1rem auto;">
            Queste sono le nostre visite guidate,
            immergiti nella storia e prova nuove emozioni!
        </p>

        <div class="mb-4">
            <img src="/Artifex/Public/Immagini/artifex-service1.webp" alt="Immagine home Artifex" class="img-fluid rounded shadow home-image">
        </div>
    </div>

    <?php if (empty($visite)): ?>
        <p class="text-center">Al momento non ci sono visite disponibili.</p>
    <?php else: ?>
        <div class="row">
            <?php foreach ($visite as $visita): ?>
                <?php
                $durata_parts = explode(':', $visita['durata_media']);
                $ore = (int)$durata_parts[0];
                $minuti = (int)$durata_parts[1];
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= htmlspecialchars($visita['titolo']) ?></h5>
                            <p class="card-text flex-grow-1"><?= htmlspecialchars($visita['luogo']) ?></p>
                            <ul class="list-unstyled mt-2">
                                <li><strong>Durata:</strong> <?= $ore ?> ore <?= $minuti ?> minuti</li>
                            </ul>
                            <!--<a href="/artifex/home/book-events?id=<?= $visita['id_visita'] ?>" class="btn btn-dark mt-3">Prenota</a>-->
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>


<?php
require 'footer.php';
?>

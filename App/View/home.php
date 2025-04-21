<?php
$title = 'Home';
require 'header.php';
?>

    <div class="text-center mt-5 mb-5">
        <h1 class="display-4"><strong>Benvenuto su Artifex</strong></h1>
        <p class="lead" style="max-width: 700px; margin: 1rem auto;">
            Siamo una società specializzata nell'organizzazione di visite guidate ed eventi culturali.
            Scopri con noi luoghi affascinanti, esperienze uniche e storie da vivere.
        </p>

        <div class="mb-4">
            <img src="/Artifex/Public/Immagini/artifex-home.webp" alt="Immagine home Artifex" class="img-fluid rounded shadow home-image">
        </div>
    </div>

    <!-- Sezione Servizi -->
    <h2 class="text-center mb-4">I Nostri Servizi</h2>
    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="/Artifex/Public/Immagini/artifex-service1.webp" class="card-img-top rounded card-img card-img-fixed" alt="Visite Guidate">
                <div class="card-body">
                    <h5 class="card-title">Visite Guidate</h5>
                    <p class="card-text">Esplora i luoghi storici con le nostre visite guidate professionali, come i Musei Vaticani e la Cappella Sistina.</p>
                    <a href="/artifex/home/guided-tours" class="btn btn-dark">Scopri di più</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="/Artifex/Public/Immagini/artifex-service2-1.webp" class="card-img-top rounded card-img card-img-fixed" alt="Eventi Culturali">
                <div class="card-body">
                    <h5 class="card-title">Eventi Culturali</h5>
                    <p class="card-text">Partecipa ai nostri eventi culturali e scopri nuove esperienze, come concerti e mostre.</p>
                    <a href="/artifex/home/cultural-events" class="btn btn-dark">Scopri di più</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4 shadow-sm">
                <img src="/Artifex/Public/Immagini/artifex-service3.webp" class="card-img-top rounded card-img card-img-fixed" alt="Prenotazione Eventi">
                <div class="card-body">
                    <h5 class="card-title">Prenotazione Eventi</h5>
                    <p class="card-text">
                        Aggiungi più visite al tuo carrello e completa la prenotazione con un unico pagamento.
                    </p>
                    <a href="/artifex/home/book-events" class="btn btn-dark">Scopri di più</a>
                </div>
            </div>
        </div>
    </div>

<?php
require 'footer.php';
?>
<?php
$title = 'Home';
require 'header.php';
?>

    <header>
        <h1>Benvenuto su Artifex</h1>
    </header>

    <section id="intro">
        <p>Artifex offre le migliori visite guidate a siti di interesse storico e culturale. Esplora itinerari unici, prenota le tue visite e goditi un’esperienza indimenticabile.</p>
    </section>

    <section id="servizi">
        <h2>I nostri servizi</h2>
        <ul>
            <li>Visualizzazione delle visite organizzate</li>
            <li>Registrazione e login per accedere a funzionalità personalizzate</li>
            <li>Prenotazione degli eventi con un unico pagamento</li>
            <li>Download del biglietto in formato PDF con QR code</li>
        </ul>
    </section>

    <section id="azioni">
        <h2>Agisci subito</h2>
        <div class="buttons">
            <a class="btn" href="register.php">Registrati</a>
            <a class="btn" href="login.php">Login</a>
            <a class="btn" href="events.php">Prenota un evento</a>
        </div>
    </section>

<?php
require 'footer.php';
?>

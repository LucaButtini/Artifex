<?php
namespace App\Controller;


use App\Model\Visit;
use App\Model\Event;


require_once 'App/Model/Visit.php';
require_once 'App/Model/Event.php';

class ServiceController
{
    private $db;

    public function __construct($db)
    {
        // Avvia la sessione se non è già stata avviata
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = $db;
    }

    // Metodo per mostrare l'elenco di tutte le visite
    public function listVisits(): void
    {
        // prendo tutte le visite
        $visitModel = new Visit($this->db);
        $visite = $visitModel->showAll();


        require 'App/View/listVisits.php';
    }

    // Metodo per mostrare l'elenco degli eventi
    public function listEvents(): void
    {
        //check sessione
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            // Se non loggato, reindirizza al form di login
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

        // recupera tutti gli eventi
        $eventModel = new Event($this->db);
        $eventi = $eventModel->showAll();

        require 'App/View/listEvents.php';
    }

    // Metodo per gestire l'invio del form di prenotazione evento
    public function bookEventSubmit(): void
    {
        // Avvia la sessione se necessario
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Recupera i dati dal form e dalla sessione
        $visitor = $_SESSION['visitor'] ?? null;
        $idEvent = $_POST['id_evento'] ?? null;
        $confirm = $_POST['confirm'] ?? null;

        // Controlla che i dati siano presenti e confermati
        if (! $idEvent || $confirm !== 'yes') {
            echo "<div class='alert alert-danger'>Dati mancanti o non confermati.</div>";
            exit;
        }

        // Verifica se esiste già una prenotazione per lo stesso evento da parte dello stesso visitatore
        $sql = "SELECT * FROM prenotazioni
                WHERE id_visitatore = :vid AND id_evento = :eid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':vid', $visitor['id_visitatore']);
        $stmt->bindValue(':eid', $idEvent);
        $stmt->execute();

        if ($stmt->fetch()) {
            // Se già prenotato, mostra messaggio informativo
            $message = "Hai già prenotato questo evento.";
            $messageType = "info";
            require 'App/View/feedback.php';
            return;
        }

        // Inserisce la nuova prenotazione
        $ins = "INSERT INTO prenotazioni (id_visitatore, id_evento)
                VALUES (:vid, :eid)";
        $stmt2 = $this->db->prepare($ins);
        $stmt2->bindValue(':vid', $visitor['id_visitatore']);
        $stmt2->bindValue(':eid', $idEvent);
        $ok = $stmt2->execute();

        // controlli con messaggio
        if ($ok) {
            $message = "Prenotazione avvenuta con successo!";
            $messageType = "success";
        } else {
            $message = "Errore nella prenotazione. Riprova.";
            $messageType = "danger";
        }

        require 'App/View/feedback.php';
    }

    // metodo per form prenotazioen evento
    public function bookEventForm(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;

        // Recupera l'id dell'evento dalla query string
        $idEvent = $_GET['id'] ?? null;
        if (!$idEvent) {
            echo "<div class='alert alert-danger'>ID evento mancante.</div>";
            exit;
        }

        // Query per recuperare i dettagli dell'evento
        $sql = "
            SELECT 
                e.id_evento,
                e.prezzo,
                e.min_persone,
                e.max_persone,
                e.guida,
                g.nome    AS guida_nome,
                g.cognome AS guida_cognome,
                v.titolo        AS titolo_visita,
                v.durata_media,
                v.luogo         AS luogo,
                ev.data_visita
            FROM eventi e
            JOIN eventi_visite ev ON ev.id_evento = e.id_evento
            JOIN visite v         ON ev.id_visita  = v.id_visita
            JOIN guide g          ON e.guida       = g.id_guida
            WHERE e.id_evento = :id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $idEvent);
        $stmt->execute();

        // Recupera i dati dell'evento
        $evento = $stmt->fetch();

        // Se l'evento non esiste, mostra un messaggio di errore
        if (! $evento) {
            echo "<div class='alert alert-warning'>Evento non trovato.</div>";
            exit;
        }

        require 'App/View/bookEvent.php';
    }
}

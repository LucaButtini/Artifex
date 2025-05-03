<?php
namespace App\Controller;

use App\Model\Visit;
use App\Model\Event;

// Include il file giusto
require_once 'App/Model/Visit.php';
require_once 'App/Model/Event.php';

class ServiceController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function listVisits()
    {
        $visitModel = new Visit($this->db);
        $visite = $visitModel->showAll();

        require 'App/View/listVisits.php';
    }

    public function listEvents()
    {
        $eventModel = new Event($this->db);
        $eventi = $eventModel->showAll();
        require 'App/View/listEvents.php';
    }
    public function bookEventSubmit()
    {
        $visitor = $_SESSION['visitor'] ?? null;
        /*if (! $visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }*/

        $idEvent   = $_POST['id_evento']   ?? null;
        $confirm   = $_POST['confirm']     ?? null;

        if (! $idEvent || $confirm !== 'yes') {
            echo "<div class='alert alert-danger'>Dati mancanti o non confermati.</div>";
            exit;
        }

        // controlla se esiste già
        $sql = "SELECT * FROM prenotazioni
                WHERE id_visitatore = :vid AND id_evento = :eid";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':vid', $visitor['id_visitatore'], \PDO::PARAM_INT);
        $stmt->bindValue(':eid', $idEvent, \PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch()) {
            echo "<div class='alert alert-info'>Hai già prenotato questo evento.</div>";
            exit;
        }

        // inserisci
        $ins = "INSERT INTO prenotazioni (id_visitatore, id_evento)
                VALUES (:vid, :eid)";
        $stmt2 = $this->db->prepare($ins);
        $stmt2->bindValue(':vid', $visitor['id_visitatore'], \PDO::PARAM_INT);
        $stmt2->bindValue(':eid', $idEvent, \PDO::PARAM_INT);
        $ok = $stmt2->execute();

        if ($ok) {
            echo "<div class='container mt-5 alert alert-success text-center'>
                   Prenotazione avvenuta con successo!
                  </div>";
        } else {
            echo "<div class='container mt-5 alert alert-danger text-center'>
                   Errore nella prenotazione, riprova.
                  </div>";
        }
    }

    public function bookEventForm()
    {
        $visitor = $_SESSION['visitor'] ?? null;
        // (ho lasciato commented il redirect verso il login)
        // if (!$visitor) { … }

        $idEvent = $_GET['id'] ?? null;
        if (!$idEvent) {
            echo "<div class='alert alert-danger'>ID evento mancante.</div>";
            exit;
        }

        $sql = "
      SELECT 
        e.id_evento,
        e.prezzo,
        e.min_persone,
        e.max_persone,
        -- prendo qui l'id della guida ma non lo useremo in view
        e.guida,
        g.nome    AS guida_nome,
        g.cognome AS guida_cognome,
        v.titolo        AS titolo_visita,
        v.durata_media,
        v.luogo         AS luogo,          -- alias 'luogo'
        ev.data_visita
      FROM eventi e
      JOIN eventi_visite ev ON ev.id_evento = e.id_evento
      JOIN visite v       ON ev.id_visita  = v.id_visita
      JOIN guide g        ON e.guida       = g.id_guida   -- join con guide
      WHERE e.id_evento = :id
      LIMIT 1
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $idEvent, \PDO::PARAM_INT);
        $stmt->execute();
        $evento = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (! $evento) {
            echo "<div class='alert alert-warning'>Evento non trovato.</div>";
            exit;
        }

        require 'App/View/bookEvent.php';
    }


}

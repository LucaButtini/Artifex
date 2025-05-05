<?php
namespace App\Controller;

use App\Model\Booking;
use App\Model\Event;

require 'App/Model/Booking.php';
require 'App/Model/Event.php';
require 'vendor/autoload.php';

class CartController
{
    private $db;

    public function __construct($db)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = $db;
    }

    // Mostra il carrello del visitatore loggato
    public function index(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

        // Prendo tutte le prenotazioni di questo visitatore
        $bookingModel = new Booking($this->db);
        $all = $bookingModel->showAll();

        // Filtro solo quelle dello user
        $myBookings = array_filter($all, fn($b) => $b['id_visitatore'] == $visitor['id_visitatore']);

        // Carico i dettagli evento per ognuna
        $eventModel = new Event($this->db);
        $eventDs = [];
        foreach ($myBookings as $b) {
            $stmt = $this->db->prepare("SELECT e.*, ev.data_visita, v.titolo AS titolo_visita, v.luogo, v.durata_media, g.nome AS guida_nome, g.cognome AS guida_cognome
        FROM eventi e
        JOIN eventi_visite ev ON e.id_evento = ev.id_evento
        JOIN visite v ON ev.id_visita = v.id_visita
        JOIN guide g ON e.guida = g.id_guida
        WHERE e.id_evento = :id");

            if (!$stmt || !$stmt->execute([':id' => $b['id_evento']])) {
                continue; // salta se errore
            }

            $event = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($event) {
                $eventDs[] = $event;
            }
        }

        require 'App/View/cart.php';
    }

    // Rimuove una prenotazione dal carrello
    public function remove(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_evento'])) {
            $id_evento = $_POST['id_evento'];

            $bookingModel = new Booking($this->db);
            if ($bookingModel->removeBooking($visitor['id_visitatore'], $id_evento)) {
                // Redirigi al carrello aggiornato
                header('Location: /Artifex/cart');
                exit;
            }
        }
    }

    // Mostra il form di checkout
    public function checkoutForm(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

        // prendi tutte le prenotazioni di questo visitatore
        $bookingModel = new Booking($this->db);
        $all         = $bookingModel->showAll();
        $myBookings  = array_filter($all, fn($b) => $b['id_visitatore']==$visitor['id_visitatore']);

        // ottieni dettagli eventi
        $events = [];
        foreach ($myBookings as $b) {
            $stmt = $this->db->prepare(
                "SELECT e.id_evento, ev.data_visita, v.titolo AS titolo_visita, 
                      v.luogo, v.durata_media, e.prezzo, g.nome AS guida_nome, g.cognome AS guida_cognome
               FROM eventi e
               JOIN eventi_visite ev ON ev.id_evento=e.id_evento
               JOIN visite v       ON ev.id_visita=v.id_visita
               JOIN guide g        ON e.guida=g.id_guida
               WHERE e.id_evento=:id"
            );
            $stmt->execute([':id'=>$b['id_evento']]);
            if ($evt = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $events[] = $evt;
            }
        }

        require 'App/View/checkout.php';
    }

    // Elabora il pagamento e svuota il carrello
    public function checkoutSubmit(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

        // elimina tutte le prenotazioni di questo visitatore
        $stmt = $this->db->prepare(
            "DELETE FROM prenotazioni WHERE id_visitatore = :vid"
        );
        $stmt->execute([':vid'=>$visitor['id_visitatore']]);

        // messaggio di conferma
        $message     = "Pagamento ricevuto! Le tue prenotazioni sono confermate.";
        $messageType = "success";
        require 'App/View/feedback.php';
    }

}


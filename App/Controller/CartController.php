<?php
namespace App\Controller;

use App\Model\Booking;
use App\Model\Event;
use TCPDF;


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

        $myBookings = array_filter($all, fn($b) =>
            $b['id_visitatore'] == $visitor['id_visitatore'] && !$b['pagata']
        );


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
        $myBookings = array_filter($all, fn($b) =>
            $b['id_visitatore'] == $visitor['id_visitatore'] && !$b['pagata']
        );


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
            if ($evt = $stmt->fetch()) {
                $events[] = $evt;
            }
        }

        require 'App/View/checkout.php';
    }



    public function generateTicket(int $eventId): void
    {
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

        // Prendi i dettagli della prenotazione
        $stmt = $this->db->prepare(
            "SELECT p.id_prenotazione, v.nome AS visitor_name, v.cognome AS visitor_lastname,
            ev.data_visita, vis.titolo AS event_title, vis.luogo AS event_place
         FROM prenotazioni p
         JOIN eventi e            ON p.id_evento = e.id_evento
         JOIN eventi_visite ev    ON e.id_evento = ev.id_evento
         JOIN visite vis          ON ev.id_visita = vis.id_visita
         JOIN visitatori v        ON p.id_visitatore = v.id_visitatore
         WHERE p.id_evento = :evt AND p.id_visitatore = :vid"
        );
        $stmt->execute([
            ':evt' => $eventId,
            ':vid' => $visitor['id_visitatore']
        ]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            die('Prenotazione non trovata');
        }

        // --- TCPDF ---
        $pdf = new \TCPDF();
        $pdf->AddPage();
        $pdf->SetFillColor(230, 230, 230);
        $pdf->Rect(0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'F');

        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetTextColor(40, 40, 120);
        $pdf->Cell(0, 12, 'Biglietto Artifex', 0, 1, 'C');
        $pdf->Ln(5);

        // Visitatori
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(0);
        $pdf->Cell(0, 8, "Nome: {$row['visitor_name']}", 0, 1);
        $pdf->Cell(0, 8, "Cognome: {$row['visitor_lastname']}", 0, 1);

        // Evento
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetTextColor(235, 79, 52);
        $pdf->Cell(0, 10, "Evento: {$row['event_title']}", 0, 1);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->SetTextColor(0);
        $pdf->Cell(0, 8, "Luogo: {$row['event_place']}", 0, 1);
        $pdf->Cell(0, 8, "Data: " . date('d/m/Y H:i', strtotime($row['data_visita'])), 0, 1);
        $pdf->Ln(8);

        // Data emissione
        $pdf->SetFont('helvetica', 'I', 10);
        $pdf->Cell(0, 6, 'Emissione: ' . date('d/m/Y H:i'), 0, 1, 'R');
        $pdf->Ln(10);

        // --- QR CODE direttamente con TCPDF ---
        $qrText = "Prenotazione: {$row['id_prenotazione']}\n"
            . "Visitatore: {$row['visitor_name']} {$row['visitor_lastname']}\n"
            . "Evento: {$row['event_title']}\n"
            . "Luogo: {$row['event_place']}\n"
            . "Data: " . date('d/m/Y H:i', strtotime($row['data_visita']));

        $pdf->write2DBarcode($qrText, 'QRCODE,H', 80, $pdf->GetY(), 50, 50, [], 'N');
        $pdf->Ln(60);

        // Footer
        $pdf->SetFont('helvetica', 'I', 9);
        $pdf->Cell(0, 5, 'Grazie per aver scelto Artifex!', 0, 1, 'C');

        $pdf->Output("Biglietto_{$row['event_title']}.pdf", 'I');
    }



    public function checkoutAndGeneratePDF(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

        // 1) Preleva tutte le prenotazioni NON pagate
        $stmt = $this->db->prepare(
            "SELECT 
            p.id_visitatore, 
            p.id_evento, 
            ev.data_visita, 
            vis.titolo   AS event_title, 
            vis.luogo    AS event_place,
            g.nome       AS guida_nome, 
            g.cognome    AS guida_cognome,
            v.nome       AS visitor_name      -- <--- solo v.nome
         FROM prenotazioni p
         JOIN eventi e         ON p.id_evento = e.id_evento
         JOIN eventi_visite ev ON e.id_evento = ev.id_evento
         JOIN visite vis      ON ev.id_visita = vis.id_visita
         JOIN guide g         ON e.guida = g.id_guida
         JOIN visitatori v    ON p.id_visitatore = v.id_visitatore
         WHERE p.id_visitatore = :vid AND p.pagata = FALSE"
        );
        $stmt->execute([':vid' => $visitor['id_visitatore']]);
        $prenotazioni = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if (empty($prenotazioni)) {
            die('Nessuna prenotazione da processare.');
        }

        // 2) Marca tutte come pagate
        $upd = $this->db->prepare("UPDATE prenotazioni SET pagata = TRUE WHERE id_visitatore = :vid");
        $upd->execute([':vid' => $visitor['id_visitatore']]);

        // 3) Genera PDF
        $pdf = new \TCPDF();
        $pdf->SetTitle('Biglietti Artifex');

        foreach ($prenotazioni as $row) {
            $pdf->AddPage();
            // Header
            $pdf->SetFont('helvetica', 'B', 18);
            $pdf->Cell(0, 12, 'Biglietto Artifex', 0, 1, 'C');
            $pdf->Ln(5);

            // Visitatore (solo nome)
            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 8, "Visitatore: {$row['visitor_name']}", 0, 1);
            $pdf->Ln(3);

            // Evento
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetTextColor(235, 79, 52);
            $pdf->Cell(0, 10, "Evento: {$row['event_title']}", 0, 1);
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(0);
            $pdf->Cell(0, 8, "Luogo: {$row['event_place']}", 0, 1);
            $pdf->Cell(0, 8, "Data: " . date('d/m/Y H:i', strtotime($row['data_visita'])), 0, 1);
            $pdf->Ln(8);

            // Emissione
            $pdf->SetFont('helvetica', 'I', 10);
            $pdf->Cell(0, 6, 'Emissione: ' . date('d/m/Y H:i'), 0, 1, 'R');
            $pdf->Ln(10);

            // QR code
            $qrText = "Visitatore: {$row['visitor_name']}\n"
                . "Evento: {$row['event_title']}\n"
                . "Luogo: {$row['event_place']}\n"
                . "Data: " . date('d/m/Y H:i', strtotime($row['data_visita'])) . "\n"
                . "IDs: vis={$row['id_visitatore']} evt={$row['id_evento']}";

            $pdf->write2DBarcode($qrText, 'QRCODE,H', 80, $pdf->GetY(), 50, 50, [], 'N');
            $pdf->Ln(60);

            // Footer
            $pdf->SetFont('helvetica', 'I', 9);
            $pdf->Cell(0, 5, 'Grazie per aver scelto Artifex!', 0, 1, 'C');
        }

        $pdf->Output('Biglietti_Artifex.pdf', 'I');
    }



    public function previewPDF(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

        $stmt = $this->db->prepare(
            "SELECT 
            p.id_visitatore, 
            p.id_evento, 
            ev.data_visita, 
            vis.titolo   AS event_title, 
            vis.luogo    AS event_place,
            g.nome       AS guida_nome, 
            g.cognome    AS guida_cognome,
            v.nome       AS visitor_name
         FROM prenotazioni p
         JOIN eventi e         ON p.id_evento = e.id_evento
         JOIN eventi_visite ev ON e.id_evento = ev.id_evento
         JOIN visite vis       ON ev.id_visita = vis.id_visita
         JOIN guide g          ON e.guida = g.id_guida
         JOIN visitatori v     ON p.id_visitatore = v.id_visitatore
         WHERE p.id_visitatore = :vid AND p.pagata = FALSE"
        );
        $stmt->execute([':vid' => $visitor['id_visitatore']]);
        $prenotazioni = $stmt->fetchAll();

        if (empty($prenotazioni)) {
            die('Nessuna prenotazione da mostrare.');
        }

        $pdf = new \TCPDF();
        $pdf->SetTitle('Anteprima Biglietti Artifex');

        foreach ($prenotazioni as $row) {
            $pdf->AddPage();
            $pdf->SetFont('helvetica', 'B', 18);
            $pdf->Cell(0, 12, 'Anteprima Biglietto', 0, 1, 'C');
            $pdf->Ln(5);

            $pdf->SetFont('helvetica', '', 12);
            $pdf->Cell(0, 8, "Visitatore: {$row['visitor_name']}", 0, 1);
            $pdf->Ln(3);

            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->SetTextColor(235, 79, 52);
            $pdf->Cell(0, 10, "Evento: {$row['event_title']}", 0, 1);
            $pdf->SetFont('helvetica', '', 12);
            $pdf->SetTextColor(0);
            $pdf->Cell(0, 8, "Luogo: {$row['event_place']}", 0, 1);
            $pdf->Cell(0, 8, "Data: " . date('d/m/Y H:i', strtotime($row['data_visita'])), 0, 1);
            $pdf->Ln(8);

            $pdf->SetFont('helvetica', 'I', 10);
            $pdf->Cell(0, 6, 'Emissione: ' . date('d/m/Y H:i'), 0, 1, 'R');
            $pdf->Ln(10);

            $qrText = "Visitatore: {$row['visitor_name']}\n"
                . "Evento: {$row['event_title']}\n"
                . "Luogo: {$row['event_place']}\n"
                . "Data: " . date('d/m/Y H:i', strtotime($row['data_visita'])) . "\n"
                . "IDs: vis={$row['id_visitatore']} evt={$row['id_evento']}";

            $pdf->write2DBarcode($qrText, 'QRCODE,H', 80, $pdf->GetY(), 50, 50, [], 'N');
            $pdf->Ln(60);

            $pdf->SetFont('helvetica', 'I', 9);
            $pdf->Cell(0, 5, 'Grazie per aver scelto Artifex!', 0, 1, 'C');
        }

        $pdf->Output('Anteprima_Biglietti_Artifex.pdf', 'I');
    }





    // Elabora il pagamento e svuota il carrello
    public function checkoutSubmit(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;
        if (!$visitor) {
            header('Location: /Artifex/form/login/visitor');
            exit;
        }

// segna come pagate tutte le prenotazioni del visitatore
        $stmt = $this->db->prepare(
            "UPDATE prenotazioni SET pagata = TRUE WHERE id_visitatore = :vid"
        );
        $stmt->execute([':vid' => $visitor['id_visitatore']]);


        // messaggio di conferma
        $message     = "Pagamento ricevuto! Le tue prenotazioni sono confermate.";
        $messageType = "success";
        require 'App/View/feedback.php';
    }

}


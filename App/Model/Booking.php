<?php
namespace App\Model;
use Exception;
use PDO;

class Booking {
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    // Restituisce tutte le prenotazioni
    public function showAll(): array {
        $bookings = [];
        $query = 'SELECT * FROM prenotazioni';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while ($booking = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bookings[] = $booking;
            }
            $stmt->closeCursor();
        } catch(Exception $e) {
            logError($e);
        }
        return $bookings;
    }

    // Rimuove una prenotazione
    public function removeBooking(int $id_visitatore, int $id_evento): bool
    {
        $query = 'DELETE FROM prenotazioni WHERE id_visitatore = :id_visitatore AND id_evento = :id_evento';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_visitatore', $id_visitatore);
            $stmt->bindValue(':id_evento', $id_evento);
            $stmt->execute();
            $stmt->closeCursor();
            return true;
        } catch (Exception $e) {
            logError($e);
            return false;
        }
    }

    // Crea una prenotazione per un dato visitatore ed evento
    public function createOne(array $booking): bool {
        $query = 'INSERT INTO prenotazioni (id_visitatore, id_evento) VALUES (:id_visitatore, :id_evento)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_visitatore', $booking['id_visitatore']);
            $stmt->bindValue(':id_evento', $booking['id_evento']);
            if(!$stmt->execute()){
                throw new Exception("Errore durante l'inserimento della prenotazione");
            }
            if($stmt->rowCount() == 0){
                throw new Exception("Nessuna riga inserita");
            }
            $stmt->closeCursor();
        } catch(Exception $e){
            $stmt->closeCursor();
            logError($e);
            return false;
        }
        return true;
    }
}

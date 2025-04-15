<?php
namespace App\Model;
require dirname(__DIR__,2). '/Functions/functions.php';
use Exception;
use PDO;

class Booking {
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    // Restituisce tutte le prenotazioni (eventualmente filtrabili per visitatore)
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

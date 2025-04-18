<?php
namespace App\Model;
require dirname(__DIR__,2). '/Functions/functions.php';
use Exception;
use PDO;

class Event {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Restituisce tutti gli eventi
    public function showAll(): array {
        $events = [];
        $query = 'SELECT * FROM eventi';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while ($event = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $events[] = $event;
            }
            $stmt->closeCursor();
        } catch(Exception $e) {
            logError($e);
        }
        return $events;
    }

    // Inserisce un nuovo evento
    public function createOne(array $event): bool {
        $query = 'INSERT INTO eventi (prezzo, min_persone, max_persone, guida) VALUES (:prezzo, :min_persone, :max_persone, :guida)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':prezzo', $event['prezzo']);
            $stmt->bindValue(':min_persone', $event['min_persone']);
            $stmt->bindValue(':max_persone', $event['max_persone']);
            $stmt->bindValue(':guida', $event['guida']);
            if(!$stmt->execute()){
                throw new Exception("Errore nell'esecuzione della query");
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

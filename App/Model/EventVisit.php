<?php
namespace App\Model;

use Exception;
use PDO;

class EventVisit {
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function showAll(): array {
        $eventVisits = [];
        $query = 'SELECT * FROM eventi_visite';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while ($ev = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $eventVisits[] = $ev;
            }
            $stmt->closeCursor();
        } catch(Exception $e){
            logError($e);
        }
        return $eventVisits;
    }

    public function createOne(array $data): bool {
        $query = 'INSERT INTO eventi_visite (id_visita, id_evento, data_visita) VALUES (:id_visita, :id_evento, :data_visita)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_visita', $data['id_visita']);
            $stmt->bindValue(':id_evento', $data['id_evento']);
            $stmt->bindValue(':data_visita', $data['data_visita']);
            if(!$stmt->execute()){
                throw new Exception("Errore durante l'inserimento dell'associazione evento-visita");
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

    //FACCIO CHE GLI EVENTI NON SI CANCELLANO PER MIA SUPPOSIZIONE DEL PROGETTO PER FARE SI CHE CHE SIA ARCHIVIATA UNA LISTA DI TUTTI GLI EVENTI FATTI
    /*public function deleteAllByEventId(int $idEvento): bool {
        try {
            $stmt = $this->db->prepare('DELETE FROM eventi_visite WHERE id_evento = :id');
            $stmt->bindValue(':id', $idEvento, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(Exception $e){
            logError($e);
            return false;
        }
    }*/


}

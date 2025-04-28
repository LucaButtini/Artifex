<?php
namespace App\Model;
//require dirname(__DIR__,2). '/Functions/functions.php';
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

    public function deleteOne(int $idVisita, int $idEvento): bool {
        try {
            $stmt = $this->db->prepare('DELETE FROM eventi_visite WHERE id_visita = :v AND id_evento = :e');
            $stmt->bindParam(':v', $idVisita, PDO::PARAM_INT);
            $stmt->bindParam(':e', $idEvento, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(Exception $e){
            logError($e);
            return false;
        }
    }
}

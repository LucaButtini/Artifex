<?php
namespace App\Model;
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
        $query = 'SELECT 
                e.*, 
                ev.data_visita, 
                v.titolo AS titolo_visita, 
                v.luogo, 
                v.durata_media,
                g.nome AS guida_nome,
                g.cognome AS guida_cognome
              FROM eventi e
              JOIN eventi_visite ev ON e.id_evento = ev.id_evento
              JOIN visite v ON ev.id_visita = v.id_visita
              JOIN guide g ON e.guida = g.id_guida
              ORDER BY ev.data_visita ASC';
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

    public function delete(int $id): bool {
        $stmt = $this->db->prepare('DELETE FROM eventi WHERE id_evento = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public function getById(int $id): ?array {
        $sql = "SELECT * FROM eventi WHERE id_evento = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $event ?: null;
    }


    public function update(array $data): bool {
        $sql = "UPDATE eventi 
            SET prezzo = :prezzo, 
                min_persone = :min_persone, 
                max_persone = :max_persone 
            WHERE id_evento = :id_evento";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':prezzo', $data['prezzo']);
            $stmt->bindValue(':min_persone', $data['min_persone']);
            $stmt->bindValue(':max_persone', $data['max_persone']);
            $stmt->bindValue(':id_evento', $data['id_evento'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            logError($e);
            return false;
        }
    }


}

<?php
namespace App\Model;

use Exception;
use PDO;

class Guide {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function showAll(): array {
        $guides = [];
        $query = 'SELECT * FROM guide';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while ($guide = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $guides[] = $guide;
            }
            $stmt->closeCursor();
        } catch(Exception $e) {
            logError($e);
        }
        return $guides;
    }

    public function createOne(array $guide): bool {
        $query = 'INSERT INTO guide (nome, cognome, data_nascita, luogo_nascita, titolo_studio) VALUES (:nome, :cognome, :data_nascita, :luogo_nascita, :titolo_studio)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $guide['nome']);
            $stmt->bindValue(':cognome', $guide['cognome']);
            $stmt->bindValue(':data_nascita', $guide['data_nascita']);
            $stmt->bindValue(':luogo_nascita', $guide['luogo_nascita']);
            $stmt->bindValue(':titolo_studio', $guide['titolo_studio']);
            if(!$stmt->execute()){
                throw new Exception("Errore nell'esecuzione della query");
            }
            if($stmt->rowCount() == 0){
                throw new Exception("Nessuna riga inserita");
            }
            $stmt->closeCursor();
        } catch(Exception $e) {
            $stmt->closeCursor();
            logError($e);
            return false;
        }
        return true;
    }

    public function deleteOne(int $id): bool {
        try {
            $stmt = $this->db->prepare('DELETE FROM guide WHERE id_guida = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch(Exception $e) {
            logError($e);
            return false;
        }
    }

    public function update(array $data): bool {
        $sql = "UPDATE guide SET nome = :nome, cognome = :cognome, data_nascita = :data_nascita, luogo_nascita = :luogo_nascita WHERE id_guida = :id_guida";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nome', $data['nome']);
            $stmt->bindValue(':cognome', $data['cognome']);
            $stmt->bindValue(':data_nascita', $data['data_nascita']);
            $stmt->bindValue(':luogo_nascita', $data['luogo_nascita']);
            $stmt->bindValue(':id_guida', $data['id_guida'], PDO::PARAM_INT);
            return $stmt->execute();
        } catch(Exception $e) {
            logError($e);
            return false;
        }
    }
}

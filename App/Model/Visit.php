<?php
namespace App\Model;
require dirname(__DIR__,2). '/Functions/functions.php';
use Exception;
use PDO;

class Visit {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Restituisce tutte le visite
    public function showAll(): array {
        $visits = [];
        $query = 'SELECT * FROM visite';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while ($visit = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $visits[] = $visit;
            }
            $stmt->closeCursor();
        } catch(Exception $e) {
            logError($e);
        }
        return $visits;
    }

    // Inserisce una nuova visita
    public function createOne(array $visit): bool {
        $query = 'INSERT INTO visite (titolo, durata_media, luogo) VALUES (:titolo, :durata_media, :luogo)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':titolo', $visit['titolo']);
            $stmt->bindValue(':durata_media', $visit['durata_media']);
            $stmt->bindValue(':luogo', $visit['luogo']);
            if (!$stmt->execute()) {
                throw new Exception("Errore durante l'esecuzione della query");
            }
            if($stmt->rowCount() == 0) {
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
}

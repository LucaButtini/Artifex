<?php
namespace App\Model;
require dirname(__DIR__,2). '/Functions/functions.php';
use Exception;
use PDO;

class Guide {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Restituisce tutte le guide
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

    // Inserisce una nuova guida
    public function createOne(array $guide): bool {
        $query = 'INSERT INTO guide (nome, cognome, data_nascita, luogo_nascita) VALUES (:nome, :cognome, :data_nascita, :luogo_nascita)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $guide['nome']);
            $stmt->bindValue(':cognome', $guide['cognome']);
            $stmt->bindValue(':data_nascita', $guide['data_nascita']);
            $stmt->bindValue(':luogo_nascita', $guide['luogo_nascita']);
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
}

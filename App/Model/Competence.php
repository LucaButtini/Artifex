<?php
namespace App\Model;
use Exception;
use PDO;

class Competence {
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    // Restituisce tutti i livelli di conoscenza
    public function showAll(): array {
        $competences = [];
        $query = 'SELECT * FROM conoscenze';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while ($comp = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $competences[] = $comp;
            }
            $stmt->closeCursor();
        } catch(Exception $e){
            logError($e);
        }
        return $competences;
    }

    // Inserisce un nuovo livello di conoscenza
    public function createOne(array $competence): bool {
        $query = 'INSERT INTO conoscenze (livello) VALUES (:livello)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':livello', $competence['livello']);
            if(!$stmt->execute()){
                throw new Exception("Errore durante l'inserimento del livello di conoscenza");
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

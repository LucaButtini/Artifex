<?php
namespace App\Model;
require dirname(__DIR__,2). '/Functions/functions.php';
use Exception;
use PDO;

class GuideLanguageCompetence {
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    // Restituisce tutte le associazioni guida-lingua-conoscenza
    public function showAll(): array {
        $associations = [];
        $query = 'SELECT * FROM avere';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $associations[] = $row;
            }
            $stmt->closeCursor();
        } catch(Exception $e){
            logError($e);
        }
        return $associations;
    }

    // Inserisce una nuova associazione
    public function createOne(array $data): bool {
        $query = 'INSERT INTO avere (id_guida, id_lingua, id_conoscenza) VALUES (:id_guida, :id_lingua, :id_conoscenza)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_guida', $data['id_guida']);
            $stmt->bindValue(':id_lingua', $data['id_lingua']);
            $stmt->bindValue(':id_conoscenza', $data['id_conoscenza']);
            if(!$stmt->execute()){
                throw new Exception("Errore nell'associazione guida-lingua-conoscenza");
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

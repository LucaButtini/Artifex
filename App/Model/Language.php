<?php
namespace App\Model;
//require dirname(__DIR__,2). '/Functions/functions.php';
use Exception;
use PDO;

class Language {
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    // Restituisce tutte le lingue
    public function showAll(): array {
        $languages = [];
        $query = 'SELECT * FROM lingue';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while ($language = $stmt->fetch()) {
                $languages[] = $language;
            }
            $stmt->closeCursor();
        } catch(Exception $e){
            logError($e);
        }
        return $languages;
    }

    // Inserisce una nuova lingua
    public function createOne(array $language): bool {
        $query = 'INSERT INTO lingue (nome) VALUES (:nome)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $language['nome']);
            if(!$stmt->execute()){
                throw new Exception("Errore durante l'inserimento della lingua");
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

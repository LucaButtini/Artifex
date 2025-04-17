<?php
namespace App\Model;
require dirname(__DIR__,2). '/Functions/functions.php';
use Exception;
use PDO;

class Visitor {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Restituisce tutti i visitatori
    public function showAll(): array {
        $visitors = [];
        $query = 'SELECT * FROM visitatori';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while($visitor = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $visitors[] = $visitor;
            }
            $stmt->closeCursor();
        } catch(Exception $e) {
            logError($e);
        }
        return $visitors;
    }

    // Inserisce un nuovo visitatore
    public function createOne(array $visitor): bool {
        $query = 'INSERT INTO visitatori (nome, email, nazionalita, telefono, lingua_base, password) VALUES (:nome, :email, :nazionalita, :telefono, :lingua_base, :password)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':nome', $visitor['nome']);
            $stmt->bindValue(':email', $visitor['email']);
            $stmt->bindValue(':nazionalita', $visitor['nazionalita']);
            $stmt->bindValue(':telefono', $visitor['telefono']);
            $stmt->bindValue(':lingua_base', $visitor['lingua_base']);
            $stmt->bindValue(':password', $visitor['password']);
            if(!$stmt->execute()) {
                throw new \Exception("Errore nell'esecuzione della query");
            }
            if($stmt->rowCount() == 0) {
                throw new \Exception("Nessuna riga inserita");
            }
            $stmt->closeCursor();
            return true;

        } catch(\Exception $e) {
            if (isset($stmt)) {
                $stmt->closeCursor();
            }
            logError($e);
            return false;
        }
    }

}

<?php
namespace App\Model;
require dirname(__DIR__,2). '/Functions/functions.php';
use Exception;
use PDO;

class Administrator {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Restituisce tutti gli amministratori
    public function showAll(): array {
        $admins = [];
        $query = 'SELECT * FROM amministratori';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while($admin = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $admins[] = $admin;
            }
            $stmt->closeCursor();
        } catch(Exception $e) {
            logError($e);
        }
        return $admins;
    }

    // Inserisce un nuovo amministratore
    public function createOne(array $admin): bool {
        $query = 'INSERT INTO amministratori (username, email, password) VALUES (:username, :email, :password)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':username', $admin['username']);
            $stmt->bindValue(':email', $admin['email']);
            $stmt->bindValue(':password', $admin['password']);
            if(!$stmt->execute()) {
                throw new Exception("Errore nell'esecuzione della query");
            }
            if ($stmt->rowCount() == 0) {
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

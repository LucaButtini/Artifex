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

    // Ottieni un visitatore per ID
    public function getById(int $id): array {
        $sql = "SELECT v.*, l.nome AS lingua_nome
            FROM visitatori v
            JOIN lingue l ON v.lingua_base = l.id_lingua
            WHERE id_visitatore = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $row ?: [];
    }

// Recupera prenotazioni future del visitatore
    /*public function getFutureBookings(int $id): array {
        $sql = "SELECT vi.data_visita, vt.titolo, vt.luogo
            FROM prenotazioni p
            JOIN eventi_visite vi ON p.id_evento = vi.id_evento
            JOIN visite vt ON vi.id_visita = vt.id_visita
            WHERE p.id_visitatore = :id AND vi.data_visita > NOW()
            ORDER BY vi.data_visita";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $bookings;
    }

// Aggiorna la password
    public function updatePassword(int $id, string $hashedPassword): bool {
        $sql = "UPDATE visitatori SET password = :pwd WHERE id_visitatore = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':pwd', $hashedPassword);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }*/


    // Restituisce tutti i visitatori
    public function showAll(): array {
        $visitors = [];
        $query = 'SELECT * FROM visitatori';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            while($visitor = $stmt->fetch()) {
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
                throw new Exception("Errore nell'esecuzione della query");
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

    // Ottieni un visitatore per email (per il login)
    public function getVisitorByEmail(string $email): ?array {
        $query = "
        SELECT v.*, l.nome AS lingua_nome
        FROM visitatori v
        LEFT JOIN lingue l ON v.lingua_base = l.id_lingua
        WHERE v.email = :email
        LIMIT 1
    ";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $visitor = $stmt->fetch();
            $stmt->closeCursor();

            return $visitor ?: null;
        } catch(Exception $e) {
            logError($e);
            return null;
        }
    }

}

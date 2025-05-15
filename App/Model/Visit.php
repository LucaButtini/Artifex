<?php
namespace App\Model;

use Exception;
use PDO;

class Visit {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Restituisce una visita specifica per ID
    public function showOne(int $id): ?array {
        $query = 'SELECT * FROM visite WHERE id = :id';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Se non ci sono risultati, restituisci null
            if ($stmt->rowCount() == 0) {
                return null;
            }

            $visit = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            return $visit;
        } catch (Exception $e) {
            logError($e);
            return null;
        }
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

    // Aggiorna una visita esistente
    public function updateOne(int $id, array $data): bool
    {
        $sql = "UPDATE visite
            SET titolo = :titolo, durata_media = :durata, luogo = :luogo
            WHERE id_visita = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':titolo', $data['titolo']);
        $stmt->bindValue(':durata', $data['durata_media']);
        $stmt->bindValue(':luogo',  $data['luogo']);
        $stmt->bindValue(':id',     $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

// Elimina una visita per ID
    public function delete(int $id): bool {
        $query = 'DELETE FROM visite WHERE id_visita = :id';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $result = $stmt->execute();
            $stmt->closeCursor();
            return $result;
        } catch (\Exception $e) {
            logError($e);
            return false;
        }
    }



}

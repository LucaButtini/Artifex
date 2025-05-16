<?php
namespace App\Model;
//require dirname(__DIR__,2). '/Functions/functions.php';
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

    public function getLanguageForGuide(int $idGuida): ?array
    {
        $query = "SELECT id_lingua FROM avere WHERE id_guida = ? LIMIT 1";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idGuida]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $result ?: null;
        } catch(Exception $e) {
            logError($e);
            return null;
        }
    }

    public function getLevelForGuide(int $idGuida): ?array
    {
        $query = "SELECT id_conoscenza FROM avere WHERE id_guida = ? LIMIT 1";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idGuida]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stmt->closeCursor();
            return $result ?: null;
        } catch(Exception $e) {
            logError($e);
            return null;
        }
    }

    public function updateForGuide(int $idGuida, array $data): bool
    {
        // Prima elimina le associazioni esistenti per questa guida
        $this->deleteForGuide($idGuida);

        // Poi crea la nuova associazione
        return $this->createOne([
            'id_guida' => $idGuida,
            'id_lingua' => $data['id_lingua'],
            'id_conoscenza' => $data['id_conoscenza']
        ]);
    }

    public function deleteForGuide(int $idGuida): bool
    {
        $query = 'DELETE FROM avere WHERE id_guida = ?';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$idGuida]);
            $stmt->closeCursor();
            return true;
        } catch(Exception $e) {
            logError($e);
            return false;
        }
    }

    public function showAllConoscenze(): array {
        $rows = [];
        $stmt = $this->db->prepare('SELECT * FROM conoscenze');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $rows;
    }

    public function showAllLanguages(): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM lingue');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            logError($e);
            return [];
        } finally {
            if(isset($stmt)) {
                $stmt->closeCursor();
            }
        }
    }

    // Inserisce una nuova associazione
    public function createOne(array $data): bool
    {
        $query = 'INSERT INTO avere (id_guida, id_lingua, id_conoscenza) 
              VALUES (:id_guida, :id_lingua, :id_conoscenza)';
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':id_guida', $data['id_guida'], PDO::PARAM_INT);
            $stmt->bindValue(':id_lingua', $data['id_lingua'], PDO::PARAM_INT);
            $stmt->bindValue(':id_conoscenza', $data['id_conoscenza'], PDO::PARAM_INT);

            if(!$stmt->execute()){
                throw new Exception("Errore nell'esecuzione della query");
            }

            return $stmt->rowCount() > 0;
        } catch(Exception $e){
            logError($e);
            return false;
        } finally {
            if(isset($stmt)) {
                $stmt->closeCursor();
            }
        }
    }
}

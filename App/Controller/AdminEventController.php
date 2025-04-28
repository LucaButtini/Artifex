<?php
// App/Controller/AdminEventController.php
namespace App\Controller;

use App\Model\Event;
use PDO;

class AdminEventController
{
    private PDO $db;
    private Event $model;

    public function __construct(PDO $db)
    {
        $this->db    = $db;
        $this->model = new Event($db);
    }

    // Lista tutti gli eventi
    public function index(): void
    {
        $events  = $this->model->showAll();
        require 'App/View/adminEvents.php';
    }

    // Mostra il form per crearne uno nuovo
    public function createForm(): void
    {
        require 'App/View/adminEventCreate.php';
    }

    // Processa la creazione
    public function create(): void
    {
        $payload = [
            'prezzo'      => $_POST['prezzo'] ?? 0,
            'min_persone' => $_POST['min_persone'] ?? 0,
            'max_persone' => $_POST['max_persone'] ?? 0,
            'guida'       => $_POST['guida'] ?? 0,
        ];
        $this->model->createOne($payload);
        header("Location: /{$this->getBaseUrl()}admin/events");
        exit;
    }

    // Elimina un evento
    public function delete(): void
    {
        $id = (int)($_POST['id_evento'] ?? 0);
        $stmt = $this->db->prepare('DELETE FROM eventi WHERE id_evento = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: /{$this->getBaseUrl()}admin/events");
        exit;
    }

    private function getBaseUrl(): string
    {
        $cfg = require dirname(__DIR__, 2) . '/appConfig.php';
        return trim($cfg['baseURL'] . $cfg['prjName'], '/').'/';
    }
}

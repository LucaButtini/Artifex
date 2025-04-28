<?php
// App/Controller/AdminScheduleController.php
namespace App\Controller;

use App\Model\EventVisit;
use PDO;

class AdminScheduleController
{
    private PDO $db;
    private EventVisit $model;

    public function __construct(PDO $db)
    {
        $this->db    = $db;
        $this->model = new EventVisit($db);
    }

    public function index(): void
    {
        $schedules = $this->model->showAll();
        require 'App/View/adminSchedules.php';
    }

    public function createForm(): void
    {
        require 'App/View/adminScheduleCreate.php';
    }

    public function create(): void
    {
        $payload = [
            'id_visita'  => $_POST['id_visita'],
            'id_evento'  => $_POST['id_evento'],
            'data_visita'=> $_POST['data_visita'],
        ];
        $this->model->createOne($payload);
        header("Location: /{$this->getBaseUrl()}admin/schedules");
        exit;
    }

    public function delete(): void
    {
        $idV = (int)($_POST['id_visita']  ?? 0);
        $idE = (int)($_POST['id_evento']  ?? 0);
        $stmt = $this->db->prepare(
            'DELETE FROM eventi_visite WHERE id_visita = :v AND id_evento = :e'
        );
        $stmt->bindParam(':v', $idV, PDO::PARAM_INT);
        $stmt->bindParam(':e', $idE, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: /{$this->getBaseUrl()}admin/schedules");
        exit;
    }

    private function getBaseUrl(): string
    {
        $cfg = require dirname(__DIR__, 2) . '/appConfig.php';
        return trim($cfg['baseURL'] . $cfg['prjName'], '/').'/';
    }
}

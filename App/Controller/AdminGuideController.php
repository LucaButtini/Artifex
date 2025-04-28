<?php
// App/Controller/AdminGuideController.php
namespace App\Controller;

use App\Model\Guide;
use PDO;

class AdminGuideController
{
    private PDO $db;
    private Guide $model;

    public function __construct(PDO $db)
    {
        $this->db    = $db;
        $this->model = new Guide($db);
    }

    public function index(): void
    {
        $guides = $this->model->showAll();
        require 'App/View/adminGuides.php';
    }

    public function createForm(): void
    {
        require 'App/View/adminGuideCreate.php';
    }

    public function create(): void
    {
        $payload = [
            'nome'         => $_POST['nome'],
            'cognome'      => $_POST['cognome'],
            'data_nascita' => $_POST['data_nascita'],
            'luogo_nascita'=> $_POST['luogo_nascita'],
        ];
        $this->model->createOne($payload);
        header("Location: /{$this->getBaseUrl()}admin/guides");
        exit;
    }

    public function delete(): void
    {
        $id = (int)($_POST['id_guida'] ?? 0);
        $stmt = $this->db->prepare('DELETE FROM guide WHERE id_guida = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header("Location: /{$this->getBaseUrl()}admin/guides");
        exit;
    }

    private function getBaseUrl(): string
    {
        $cfg = require dirname(__DIR__, 2) . '/appConfig.php';
        return trim($cfg['baseURL'] . $cfg['prjName'], '/').'/';
    }
}

<?php

namespace App\Controller;

use App\Model\Event;
use PDO;

class AdminEventController {
    private PDO $db;
    private Event $eventModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->eventModel = new Event($db);
    }

    // Mostra la lista degli eventi
    public function index(): void {
        $events = $this->eventModel->showAll();
        require 'App/View/adminEvents/index.php'; // Passa la lista degli eventi alla vista
    }

    // Crea un nuovo evento
    public function createForm(): void {
        require 'App/View/adminEvents/createForm.php'; // Mostra il form di creazione
    }

    public function create(): void {
        $eventData = $_POST; // Recupera i dati dal form
        if ($this->eventModel->createOne($eventData)) {
            header('Location: /admin/events'); // Reindirizza alla lista eventi
        } else {
            // Gestisci errore
        }
    }

    // Modifica evento
    public function editForm(int $id): void {
        $event = $this->eventModel->getEventById($id);
        require 'App/View/adminEvents/editForm.php'; // Mostra il form di modifica
    }

    public function update(): void {
        $eventData = $_POST;
        if ($this->eventModel->update($eventData)) {
            header('Location: /admin/events');
        } else {
            // Gestisci errore
        }
    }

    // Elimina evento
    public function delete(): void {
        $eventId = $_POST['event_id'];
        if ($this->eventModel->deleteOne($eventId)) {
            header('Location: /admin/events');
        } else {
            // Gestisci errore
        }
    }
}

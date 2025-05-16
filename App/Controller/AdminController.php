<?php

namespace App\Controller;

use App\Model\Administrator;
use App\Model\Event;
use App\Model\Visit;
use App\Model\Guide;
use App\Model\EventVisit;
use App\Model\GuideLanguageCompetence;
use App\Model\Language;

use PDO;

require 'App/Model/Administrator.php';
require 'App/Model/Event.php';
require 'App/Model/Visit.php';
require 'App/Model/Guide.php';
require 'App/Model/EventVisit.php';
require 'App/Model/GuideLanguageCompetence.php';
require 'App/Model/Language.php';

class AdminController
{
    private PDO $db;
    private Administrator $administrator;

    public function __construct($db)
    {
        $this->db            = $db;
        $this->administrator = new Administrator($db);

        // Qui assicuriamo che l’admin di default venga creato prima di qualsiasi azione
        $this->defaultAdmin();
    }

    /**
     * Se la tabella è vuota, crea un admin di default:
     *   username=admin, email=admin@admin.com, password=admin123
     */
    private function defaultAdmin(): void
    {
        $query = 'SELECT COUNT(*) AS cnt FROM amministratori';
        $stmt  = $this->db->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (isset($row['cnt']) && $row['cnt'] == 0) {
            $username = 'admin';
            $email    = 'admin@admin.com';
            $password = password_hash('admin123', PASSWORD_DEFAULT);

            $insert = 'INSERT INTO amministratori(username, email, password)
                       VALUES (:username, :email, :password)';
            $stmt2 = $this->db->prepare($insert);
            $stmt2->bindValue(':username', $username);
            $stmt2->bindValue(':email',    $email);
            $stmt2->bindValue(':password', $password);
            $stmt2->execute();
            $stmt2->closeCursor();
        }
    }

    // Mostra il form di login admin
    public function formLoginAdmin(): void
    {
        require 'App/View/formLoginAdmin.php';
    }

    public function infoProfilo(): void
    {
        session_start();
        $admin = $_SESSION['admin'] ?? null;
        require 'App/View/profile.php';
    }



    // Esegue il login admin

    public function loginAdmin(): void
    {
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        // Recupera l'admin per email
        $admin = $this->administrator->getAdminByEmail($email);

        if ($admin && password_verify($password, $admin['password'])) {
            session_start();
            $_SESSION['admin'] = $admin;

            $content = "Login effettuato con successo!";
            require 'App/View/confirm.php';
            exit;
        } else {
            $error = 'Credenziali non valide!';
            require 'App/View/formLoginAdmin.php';
        }
    }

    public function changePassword(): void
    {
        session_start();
        $admin = $_SESSION['admin'] ?? null;

        if (!$admin) {
            $error = 'Devi effettuare il login per cambiare la password.';
            require 'App/View/profile.php';
            return;
        }

        $oldPassword     = $_POST['old_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($newPassword !== $confirmPassword) {
            $pwdError = 'La nuova password e la conferma non corrispondono.';
            require 'App/View/profile.php';
            return;
        }

        if (!password_verify($oldPassword, $admin['password'])) {
            $pwdError = 'La vecchia password non è corretta.';
            require 'App/View/profile.php';
            return;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $username = $admin['username'];

        if ($this->administrator->updatePassword($username, $hashedPassword)) {
            $_SESSION['admin']['password'] = $hashedPassword;
            $pwdSuccess = 'La tua password è stata aggiornata con successo!';
        } else {
            $pwdError = 'Errore nell\'aggiornamento della password.';
        }

        require 'App/View/profile.php';
    }

    public function dashboard(): void
    {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header('Location: /'); exit;
        }

        // 1) Contatori
        $eventModel  = new Event($this->db);
        $visiteModel = new Visit($this->db);
        $guideModel  = new Guide($this->db);

        $totEventi = count($eventModel->showAll());
        $totVisite = count($visiteModel->showAll());
        $totGuide  = count($guideModel->showAll());


        require 'App/View/dashboard.php';
    }


    //zona dashboard

// Eventi

// App/Controller/AdminController.php

    public function createEventForm(): void
    {
        // prendi tutte le visite
        $visitModel = new Visit($this->db);
        $visits     = $visitModel->showAll();   // ora abbiamo $visits

        require 'App/View/events_create.php';
    }


    public function editEventForm(int $id): void
    {
        $eventModel = new Event($this->db);
        $event = $eventModel->getById($id);
        if (!$event) {
            http_response_code(404);
            die('Evento non trovato');
        }
        require 'App/View/events_edit.php';
    }

// Salva le modifiche
    public function updateEvent(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Artifex/admin/dashboard');
            exit;
        }

        $data = [
            'id_evento'   => $id,
            'prezzo'      => $_POST['prezzo'],
            'min_persone' => $_POST['min_persone'],
            'max_persone' => $_POST['max_persone'],
        ];

        $eventModel = new Event($this->db);
        if ($eventModel->update($data)) {
            header('Location: /Artifex/admin/dashboard');
            exit;
        }

        die('Errore durante l\'aggiornamento dell\'evento');
    }

    public function createEvent(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Artifex/admin/dashboard');
            exit;
        }

        // 1) inserisci l'evento
        $eventModel = new Event($this->db);
        $ok = $eventModel->createOne([
            'prezzo'      => $_POST['prezzo'],
            'min_persone' => $_POST['min_persone'],
            'max_persone' => $_POST['max_persone'],
            'guida'       => $_POST['guida'],
        ]);

        if (!$ok) {
            die('Errore creazione evento');
        }

        // 2) prendi l'id appena generato
        $eventId = (int)$this->db->lastInsertId();

        // 3) inserisci l'associazione evento–visita
        $evModel = new EventVisit($this->db);
        $assoc = [
            'id_visita'   => $_POST['id_visita'],
            'id_evento'   => $eventId,
            'data_visita' => $_POST['data_visita'],
        ];
        if (!$evModel->createOne($assoc)) {
            die('Errore associazione evento-visita');
        }

        header('Location: /Artifex/admin/dashboard');
        exit;
    }


    public function deleteEvent(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                $eventVisitModel = new EventVisit($this->db);
                $eventModel = new Event($this->db);

                // Elimina prima le associazioni eventi-visite
                $this->deleteAllEventVisitsForEvent((int)$id, $eventVisitModel);

                // Poi elimina l'evento vero e proprio
                $eventModel->delete((int)$id);
            }
            header('Location: /Artifex/admin/dashboard');
            exit;
        }
        header('Location: /Artifex/admin/dashboard');
    }


    public function createEventVisitForm(): void
    {
        // prendi tutte le visite e tutti gli eventi
        $visitModel = new Visit($this->db);
        $eventModel = new Event($this->db);

        $visits = $visitModel->showAll();   // array di ['id_visita', 'titolo', ...]
        $events = $eventModel->showAll();   // array di ['id_evento', 'prezzo', ...]
        require 'App/View/event_visits_create.php';
    }

    public function storeEventVisit(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Artifex/admin/dashboard');
            exit;
        }
        $evModel = new EventVisit($this->db);
        $data = [
            'id_visita'   => $_POST['id_visita'],
            'id_evento'   => $_POST['id_evento'],
            'data_visita' => $_POST['data_visita'],
        ];
        if ($evModel->createOne($data)) {
            header('Location: /Artifex/admin/dashboard');
            exit;
        }
        die('Errore durante l\'associazione evento–visita');
    }




    public function createVisitForm(): void {
        require 'App/View/visits_create.php'; // link corretto senza sottocartelle
    }


    // Signature deve accettare $id!
    public function editVisitForm(int $id): void
    {
        $visitModel = new Visit($this->db);
        $visit = $visitModel->showOne($id);
        if (!$visit) {
            http_response_code(404); die('Visita non trovata');
        }
        // passa $visit alla view
        require 'App/View/visits_edit.php';
    }

// POST
    public function editVisit(int $id): void
    {
        $newData = [
            'titolo'       => $_POST['titolo'],
            'durata_media' => $_POST['durata_media'],
            'luogo'        => $_POST['luogo']
        ];
        $visitModel = new Visit($this->db);
        if ($visitModel->updateOne($id, $newData)) {
            header('Location: /Artifex/admin/dashboard'); exit;
        }
        die('Errore durante l\'aggiornamento');
    }




    public function createVisit(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $visitModel = new Visit($this->db);
            $visitModel->createOne($_POST);
            header('Location: /Artifex/admin/dashboard');
            exit;
        }
        header('Location: /admin/visits');
    }

    public function updateVisit(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $visitModel = new Visit($this->db);
            $visitModel->update($_POST);
            header('Location: /admin/visits');
            exit;
        }
        header('Location: /admin/visits');
    }

    public function deleteVisit(int $id): void
    {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header('Location: /Artifex/form/login/admin');
            exit;
        }

        $visitModel = new Visit($this->db);
        $visit = $visitModel->showOne($id);

        if (!$visit) {
            http_response_code(404);
            die('Visita non trovata');
        }

        // Se la richiesta è GET, mostra la pagina di conferma
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require 'App/View/visits_delete.php';
            return;
        }

        // Se la richiesta è POST, procedi con l'eliminazione
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($visitModel->delete($id)) {
                header('Location: /Artifex/admin/dashboard');
                exit;
            } else {
                die('Errore durante l\'eliminazione della visita.');
            }
        }
    }


    public function createGuideForm(): void
    {
        // Recupera tutte le lingue
        $langModel = new Language($this->db);
        $lingue    = $langModel->showAll();

        // Recupera tutti i livelli di conoscenza
        $conModel  = new GuideLanguageCompetence($this->db);
        $conoscenze = $conModel->showAllConoscenze(); // vedremo sotto come

        require 'App/View/guides_create.php';
    }


    public function storeGuide(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Artifex/admin/dashboard');
            exit;
        }

        // 1. Salva i dati della guida
        $data = [
            'nome'          => $_POST['nome'],
            'cognome'       => $_POST['cognome'],
            'data_nascita'  => $_POST['data_nascita'],
            'luogo_nascita' => $_POST['luogo_nascita'],
            'titolo_studio' => $_POST['titolo_studio'],
        ];

        $guideModel = new Guide($this->db);
        $idGuida = $guideModel->createOne($data);

        if ($idGuida === false) {
            die("Errore nella creazione della guida.");
        }

        // 2. Associa lingua e livello
        $idLingua       = $_POST['id_lingua'] ?? null;
        $idConoscenza   = $_POST['id_conoscenza'] ?? null;

        if ($idLingua && $idConoscenza) {
            $glcModel = new GuideLanguageCompetence($this->db);
            $ok = $glcModel->createOne([
                'id_guida'      => $idGuida,
                'id_lingua'     => $idLingua,
                'id_conoscenza' => $idConoscenza,
            ]);

            if (!$ok) {
                die("Errore nell'associazione guida–lingua.");
            }
        }

        header('Location: /Artifex/admin/dashboard');
        exit;
    }



    public function editGuideForm(int $id): void
    {
        $guideModel = new Guide($this->db);
        $guida = $guideModel->getById($id);

        if (!$guida) {
            http_response_code(404);
            die('Guida non trovata');
        }

        // Recupera le lingue e le conoscenze
        $langModel = new Language($this->db);
        $lingue = $langModel->showAll();

        $conModel = new GuideLanguageCompetence($this->db);
        $conoscenze = $conModel->showAllConoscenze();

        // Recupera la lingua e il livello attuali della guida
        $currentLang = $conModel->getLanguageForGuide($id);
        $currentLevel = $conModel->getLevelForGuide($id);

        // Aggiungi queste informazioni all'array $guida
        $guida['id_lingua'] = $currentLang['id_lingua'] ?? null;
        $guida['id_conoscenza'] = $currentLevel['id_conoscenza'] ?? null;

        require 'App/View/guides_edit.php';
    }

    public function updateGuide(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /Artifex/admin/dashboard');
            exit;
        }

        $data = [
            'id_guida'      => $id,
            'nome'          => $_POST['nome'],
            'cognome'       => $_POST['cognome'],
            'data_nascita'  => $_POST['data_nascita'],
            'luogo_nascita' => $_POST['luogo_nascita'],
            'titolo_studio' => $_POST['titolo_studio'] ?? '',
        ];

        $guideModel = new Guide($this->db);
        if ($guideModel->update($data)) {
            header('Location: /Artifex/admin/dashboard');
            exit;
        }

        die('Errore nell\'aggiornamento della guida.');
    }


    public function deleteGuide(int $id): void
    {
        session_start();
        if (!isset($_SESSION['admin'])) {
            header('Location: /Artifex/form/login/admin');
            exit;
        }

        $guideModel = new Guide($this->db);
        $guide = $guideModel->getById($id);

        if (!$guide) {
            http_response_code(404);
            die('Guida non trovata');
        }

        // Se la richiesta è GET, mostra la pagina di conferma
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            require 'App/View/guides_delete.php';
            return;
        }

        // Se la richiesta è POST, procedi con l'eliminazione
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($guideModel->deleteOne($id)) {
                header('Location: /Artifex/admin/dashboard');
                exit;
            } else {
                die('Errore durante l\'eliminazione della guida.');
            }
        }
    }



    // Logout
    public function logoutPage(): void
    {
        require 'App/View/logout.php';
    }

}

<?php

namespace App\Controller;

use App\Model\Administrator;
use App\Model\Event;
use App\Model\Visit;
use App\Model\Guide;

use PDO;

require 'App/Model/Administrator.php';
require 'App/Model/Event.php';
require 'App/Model/Visit.php';
require 'App/Model/Guide.php';

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

        // 2) Eventi recenti: prendi gli ultimi 5 dalla tabella eventi_visite
        $sql = "
          SELECT ev.id_evento, v.titolo, ev.data_visita
          FROM eventi_visite ev
          JOIN visite v        ON ev.id_visita = v.id_visita
          ORDER BY ev.data_visita DESC
          LIMIT 5
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $recentEvents = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        require 'App/View/dashboard.php';
    }



    // Logout
    public function logoutPage(): void
    {
        require 'App/View/logout.php';
    }

}

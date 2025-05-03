<?php
namespace App\Controller;

use App\Model\Visitor;
use App\Model\Language;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PDO;

require 'App/Model/Visitor.php';
require 'App/Model/Language.php';
require 'vendor/autoload.php';

class UserController
{
    private PDO $db;
    protected Visitor $visitor;

    public function __construct(PDO $db)
    {
        $this->db      = $db;
        $this->visitor = new Visitor($db);
    }


// form di registrazione
    public function formInsertOneVisitor(): void
    {
        // lingue per select
        $languageModel = new Language($this->db);
        $lingue        = $languageModel->showAll();

        //dati per i campi
        $fields = require 'App/Attributes/visitatoriAttributes.php';

        // baseUrl per i link
        $appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
        $baseUrl   = $appConfig['baseURL'] . $appConfig['prjName'];

        require 'App/View/formRegistration.php';
    }

    public function logoutPage(): void
    {
        require 'App/View/logout.php';
    }

// App/Controller/UserController.php
    public function infoProfilo(): void
    {
        session_start();
        $visitor = $_SESSION['visitor'] ?? null;
        $admin   = $_SESSION['admin']   ?? null;  // <-- aggiungi questa riga

        require 'App/View/profile.php';
    }





    // nuovo visitatore
    public function insertOneVisitor(): void
    {
        $visitorAttributes = require 'App\Attributes\visitatoriAttributes.php';
        $visitor = [];

        foreach ($visitorAttributes as $attribute => $attributeValues) {
            $visitor[$attribute] = $_POST[$attribute] ?? '';
        }

        //hash password
        $visitor['password'] = password_hash($visitor['password'], PASSWORD_DEFAULT);

        // baseUrl per i link
        $appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
        $baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];

        if ($this->visitor->createOne($visitor)) {
            // Invio mail di benvenuto
            $this->sendWelcomeEmail($visitor['email'], $visitor['nome']);

            $success = "Registrazione completata con successo! <a href='{$baseUrl}form/login/visitor'>Clicca qui per accedere</a>.";
            require 'App/View/formRegistration.php';
        } else {
            $error = "Errore nella registrazione, riprova più tardi.";
            require 'App/View/formRegistration.php';
        }
    }

    //  form di login
    public function formLoginVisitor(): void
    {
        require 'App/View/formLoginVisitor.php';
    }

    //  login del visitatore
    public function loginVisitor(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $visitor = $this->visitor->getVisitorByEmail($email);

        if ($visitor && password_verify($password, $visitor['password'])) {
            session_start();
            $_SESSION['visitor'] = $visitor;

            $content = "Login effettuato con successo!";
            require 'App/View/confirm.php';
            exit;
        } else {
            $error = 'Credenziali non valide!';
            require 'App/View/formLoginVisitor.php';
        }
    }
    public function changePassword(): void
    {
        session_start();
        $visitor = $_SESSION['visitor'] ?? null;

        // Se non loggato
        if (! $visitor) {
            $error = 'Devi effettuare il login per cambiare la password.';
            require 'App/View/profile.php';
            return;
        }

        // Dati form
        $oldPassword     = $_POST['old_password'] ?? '';
        $newPassword     = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Verifica che nuova == conferma
        if ($newPassword !== $confirmPassword) {
            $pwdError = 'La nuova password e la conferma non corrispondono.';
            require 'App/View/profile.php';
            return;
        }

        // Verifica vecchia password
        if (!password_verify($oldPassword, $visitor['password'])) {
            $pwdError = 'La vecchia password non è corretta.';
            require 'App/View/profile.php';
            return;
        }

        // Aggiorna password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $idVisitatore   = $visitor['id_visitatore'] ?? null;

        if ($idVisitatore) {
            $this->visitor->updatePassword($idVisitatore, $hashedPassword);

            // Aggiorna password anche in sessione
            $_SESSION['visitor']['password'] = $hashedPassword;

            $pwdSuccess = 'La tua password è stata aggiornata con successo!';
            require 'App/View/profile.php';
        } else {
            $pwdError = 'Errore: ID visitatore non trovato.';
            require 'App/View/profile.php';
        }
    }


    //  mail di benvenuto
    private function sendWelcomeEmail(string $to, string $name): void
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = ''; //  tua mail
            $mail->Password   = '';    //  password app
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('no-reply@artifex.it', 'Artifex');
            $mail->addAddress($to, $name);

            $mail->Subject = 'Benvenuto su Artifex!';
            $mail->Body    = "Ciao $name,\n\n"
                . "Grazie per esserti registrato su Artifex!\n"
                . "Ora puoi scoprire le nostre visite guidate ed eventi culturali.\n\n"
                . "A presto!\nIl team di Artifex.";
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            $mail->send();
        } catch (Exception $e) {
            error_log("Errore invio mail: {$mail->ErrorInfo}");
        }
    }
}

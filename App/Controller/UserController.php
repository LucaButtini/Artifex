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
        // Avvia la sessione se non è già attiva
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->db = $db;
        $this->visitor = new Visitor($db);
    }

    // form di registrazione per un nuovo visitatore.

    public function formInsertOneVisitor(): void
    {
        // Recupera le lingue da mostrare nel menu a tendina
        $languageModel = new Language($this->db);
        $lingue = $languageModel->showAll();

        // Carica gli attributi dei visitatori (campi form)
        $fields = require 'App/Attributes/visitatoriAttributes.php';

        // URL base del progetto
        $appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
        $baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];

        // Mostra il form
        require 'App/View/formRegistration.php';
    }


     // Mostra la pagina di logout.

    public function logoutPage(): void
    {
        require 'App/View/logout.php';
    }


      //Mostra le informazioni del profilo del visitatore (o admin)
    public function infoProfilo(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $visitor = $_SESSION['visitor'] ?? null;
        $admin = $_SESSION['admin'] ?? null;

        $bookings = [];

        // Se visitatore loggato, recupera le prenotazioni pagate
        if ($visitor) {
            $bookings = $this->visitor->getPaidBookings($visitor['id_visitatore']);
        }

        require 'App/View/profile.php';
    }


    //Elabora i dati del form e registra un nuovo visitatore.

    public function insertOneVisitor(): void
    {
        // Carica gli attributi da usare come chiavi
        $visitorAttributes = require 'App\Attributes\visitatoriAttributes.php';
        $visitor = [];

        // Popola l'array $visitor con i dati POST
        foreach ($visitorAttributes as $attribute => $attributeValues) {
            $visitor[$attribute] = $_POST[$attribute] ?? '';
        }

        // Cifra la password
        $visitor['password'] = password_hash($visitor['password'], PASSWORD_DEFAULT);

        // Config per generare URL di ritorno
        $appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
        $baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];

        // Tenta la creazione nel DB
        if ($this->visitor->createOne($visitor)) {
            // Invia email di benvenuto
            $this->sendWelcomeEmail($visitor['email'], $visitor['nome']);

            $success = "Registrazione completata con successo! <a href='{$baseUrl}form/login/visitor'>Clicca qui per accedere</a>.";
            require 'App/View/formRegistration.php';
        } else {
            $error = "Errore nella registrazione, riprova più tardi.";
            require 'App/View/formRegistration.php';
        }
    }

//form login visitatori
    public function formLoginVisitor(): void
    {
        require 'App/View/formLoginVisitor.php';
    }
    //gestione login visitatore
    public function loginVisitor(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        // Recupera visitatore dal DB
        $visitor = $this->visitor->getVisitorByEmail($email);

        // Verifica password
        if ($visitor && password_verify($password, $visitor['password'])) {
            $_SESSION['visitor'] = $visitor;

            $content = "Login effettuato con successo!";
            require 'App/View/confirm.php';
            exit;
        } else {
            $error = 'Credenziali non valide!';
            require 'App/View/formLoginVisitor.php';
        }
    }

    // cambia la password  profilo.

    public function changePassword(): void
    {
        $visitor = $_SESSION['visitor'] ?? null;

        // Verifica login
        if (!$visitor) {
            $error = 'Devi effettuare il login per cambiare la password.';
            require 'App/View/profile.php';
            return;
        }

        // Legge i dati dal form
        $oldPassword = $_POST['old_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Verifica corrispondenza tra nuova e conferma
        if ($newPassword !== $confirmPassword) {
            $pwdError = 'La nuova password e la conferma non corrispondono.';
            require 'App/View/profile.php';
            return;
        }

        // Verifica che la vecchia password sia corretta
        if (!password_verify($oldPassword, $visitor['password'])) {
            $pwdError = 'La vecchia password non è corretta.';
            require 'App/View/profile.php';
            return;
        }

        // Hash della nuova password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $idVisitatore = $visitor['id_visitatore'] ?? null;

        // Aggiorna nel DB
        if ($this->visitor->updatePassword($idVisitatore, $hashedPassword)) {
            $_SESSION['visitor']['password'] = $hashedPassword;
            $pwdSuccess = 'La tua password è stata aggiornata con successo!';
        } else {
            $pwdError = 'Errore: ID visitatore non trovato.';
        }

        require 'App/View/profile.php';
    }

//invia mail dopo registrazione
    private function sendWelcomeEmail(string $to, string $name): void
    {
        $mail = new PHPMailer(true);

        try {
            // Configurazione SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = ''; // Inserire la propria email
            $mail->Password = ''; // Inserire la password dell'app (non quella normale)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Mittente e destinatario
            $mail->setFrom('no-reply@artifex.it', 'Artifex');
            $mail->addAddress($to, $name);

            // Contenuto del messaggio
            $mail->Subject = 'Benvenuto su Artifex!';
            $mail->Body = "Ciao $name,\n\n"
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

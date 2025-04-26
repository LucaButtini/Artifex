<?php
namespace App\Controller;

use App\Model\Visitor;
use App\Model\Language;             // ← aggiunto
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PDO;

require 'App/Model/Visitor.php';
require 'App/Model/Language.php';    // ← aggiunto
require 'vendor/autoload.php';

class UserController
{
    private PDO $db;                   // ← aggiunto
    protected Visitor $visitor;

    public function __construct(PDO $db)
    {
        $this->db      = $db;         // ← aggiunto
        $this->visitor = new Visitor($db);
    }


// form di registrazione
    public function formInsertOneVisitor(): void
    {
        // Carica tutte le lingue per il select
        $languageModel = new Language($this->db);
        $lingue        = $languageModel->showAll();

        // Carica i metadati dei campi
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

    public function infoProfilo(): void
    {
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
            $_SESSION['visitor'] = $visitor['nome'];

            $content = "Login effettuato con successo!";
            require 'App/View/confirm.php';
            exit;
        } else {
            $error = 'Credenziali non valide!';
            require 'App/View/formLoginVisitor.php';
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

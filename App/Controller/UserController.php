<?php

namespace App\Controller;
require 'App\Model\Visitor.php';
use App\Model\Visitor;

class UserController
{
    protected Visitor $visitor;

    public function __construct($db)
    {
        $this->visitor = new Visitor($db);
    }

    // Mostra il form di registrazione
    public function formInsertOneVisitor(): void
    {
        require 'App/View/formRegistration.php';
    }

    public function logoutPage():void{
        require 'App/View/logout.php';
    }

    // Inserisce un nuovo visitatore
    public function insertOneVisitor(): void
    {
        $visitorAttributes = require 'App\Attributes\visitatoriAttributes.php';
        $visitor = [];

        foreach ($visitorAttributes as $attribute => $attributeValues) {
            $visitor[$attribute] = $_POST[$attribute] ?? '';
        }

        // Hash della password prima del salvataggio
        $visitor['password'] = password_hash($visitor['password'], PASSWORD_DEFAULT);

        // Definisci baseUrl qui
        $appConfig = require dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'appConfig.php';
        $baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];

        if ($this->visitor->createOne($visitor)) {
            $success = "Registrazione completata con successo! <a href='{$baseUrl}form/login/visitor'>Clicca qui per accedere</a>.";
            // Passa successo e baseUrl alla vista
            require 'App/View/formRegistration.php';
        } else {
            $error = "Errore nella registrazione, riprova più tardi.";
            // Passa errore alla vista
            require 'App/View/formRegistration.php';
        }
    }



    // Mostra il form di login per il visitatore
    public function formLoginVisitor(): void
    {
        require 'App/View/formLoginVisitor.php'; // Vista del form di login
    }

    // Gestisce il login del visitatore
    // Gestisce il login del visitatore
    public function loginVisitor(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $visitor = $this->visitor->getVisitorByEmail($email);

        if ($visitor && password_verify($password, $visitor['password'])) {
            session_start();
            $_SESSION['visitor'] = $visitor['nome']; // Salviamo il nome del visitatore nella sessione

            // Dopo il login, redirect alla pagina di conferma
            $content = "Login effettuato con successo!";
            require 'App/View/confirm.php'; // Mostriamo la pagina di conferma
            exit;
        } else {
            $error = 'Credenziali non valide!';
            require 'App/View/formLoginVisitor.php'; // Mostra il form di login con l'errore
        }
    }

}

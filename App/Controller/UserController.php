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

        if ($this->visitor->createOne($visitor)) {
            $content = "Registrazione completata!";
            require 'App/View/confirm.html';
        } else {
            $content = "Errore nella registrazione";
            require 'App/View/error.html';
        }
    }

    // Metodo di prova come show1 del product
    public function show1(): void
    {
        $content = 'Ciao sono show1 nella classe UserController';
        require 'App/View/content.php';
    }
}

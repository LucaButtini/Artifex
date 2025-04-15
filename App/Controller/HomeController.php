<?php

namespace App\Controller;

class HomeController
{
    public function __construct($db) {

    }

    public function presentationHome(): void {
        require 'App/View/home.php';
    }
}

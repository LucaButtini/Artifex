<?php
namespace App\Controller;

use App\Model\Visit;
use App\Model\Event;

// Include il file giusto
require_once 'App/Model/Visit.php';
require_once 'App/Model/Event.php';

class ServiceController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function listVisits()
    {
        $visitModel = new Visit($this->db);
        $visite = $visitModel->showAll();

        require 'App/View/listVisits.php';
    }

    public function listEvents()
    {
        $eventModel = new Event($this->db);
        $eventi = $eventModel->showAll();
        require 'App/View/listEvents.php';
    }
    
}

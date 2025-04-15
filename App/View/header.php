<?php
// Percorso coerente con header.php (siamo nella root del progetto)
$appConfig = require __DIR__ . '/appConfig.php';

$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Pulizia URL per routing
$url = strtolower($url);
$url = trim(str_replace($appConfig['prjName'], '', $url), '/');

/* Connessione al database */
require __DIR__ . "/Database/DBconn.php";
$dataBaseConfig = require __DIR__ . "/Database/databaseConfig.php";
$db = Database\DBconn::getDB($dataBaseConfig);

/* Router */
require __DIR__ . '/Router/Router.php';
$routerClass = new \Router\Router();

// Rotte del progetto Artifex
$routerClass->addRoute('GET', 'visite/index', 'VisitaController', 'listaVisite');
$routerClass->addRoute('GET', 'eventi/index', 'EventoController', 'listaEventi');
$routerClass->addRoute('GET', 'eventi/dettaglio', 'EventoController', 'dettaglioEvento');
$routerClass->addRoute('POST', 'prenotazioni/aggiungi', 'PrenotazioneController', 'aggiungiAlCarrello');
$routerClass->addRoute('GET', 'admin/dashboard', 'AdminController', 'dashboard');
$routerClass->addRoute('GET', 'home/index', 'HomeController', 'presentationHome'); // Homepage

// Matching route
$reValue = $routerClass->match($url, $method);
if (empty($reValue)) {
    http_response_code(404);
    die('Pagina non trovata');
}

// Dispatch controller e action
$controller = 'App\Controller\\' . $reValue['controller'];
$action = $reValue['action'];

require __DIR__ . '/' . $controller . '.php';
$controllerObj = new $controller($db);
$controllerObj->$action();

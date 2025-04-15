<?php
$appConfig = require 'appConfig.php';
$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];
$url = strtolower($url);
$url = trim(str_replace($appConfig['prjName'], '', $url), '/');

/* Connessione al database */
require "Database/DBconn.php";
$dataBaseConfig = require "Database/databaseConfig.php";
$db = Database\DBconn::getDB($dataBaseConfig);

require 'Router/Router.php';
$routerClass = new \Router\Router();

// Nuove rotte per il progetto Artifex
$routerClass->addRoute('GET', 'visite/index', 'VisitaController', 'listaVisite');
$routerClass->addRoute('GET', 'eventi/index', 'EventoController', 'listaEventi');
$routerClass->addRoute('GET', 'eventi/dettaglio', 'EventoController', 'dettaglioEvento');
$routerClass->addRoute('POST', 'prenotazioni/aggiungi', 'PrenotazioneController', 'aggiungiAlCarrello');
$routerClass->addRoute('GET', 'admin/dashboard', 'AdminController', 'dashboard');
$routerClass->addRoute('GET', 'home/homePage', 'HomeController', 'presentationHome');


$reValue = $routerClass->match($url, $method);
if(empty($reValue)){
    http_response_code(404);
    die('Pagina non trovata');
}
$controller = 'App\Controller\\' . $reValue['controller'];
$action = $reValue['action'];

require $controller . '.php';
$controllerObj = new $controller($db);
$controllerObj->$action();
?>

<?php

// Carica la configurazione dell'app
$appConfig = require 'appConfig.php';

/*
    VERSIONE COMMENTATA ORIGINALE
    --------------------------------
    Questo blocco legge la URI dalla richiesta, rimuove il prefisso del progetto
    e la normalizza in minuscolo. È stato sostituito da una versione più chiara sotto.

    $url = $_SERVER['REQUEST_URI'];
    $method = $_SERVER['REQUEST_METHOD'];
    $url = strtolower($url);
    $prefix = '/' . strtolower($appConfig['prjName']);
    if (strpos($url, $prefix) === 0) {
        $url = substr($url, strlen($prefix));
    }
    $url = trim($url, '/');
*/

// VERSIONE ATTUALE — Estrae il path puro dalla URI e rimuove il prefisso
$fullUri = $_SERVER['REQUEST_URI'];
$path = parse_url($fullUri, PHP_URL_PATH);     // es. "/Artifex/home/book-events"
$url = strtolower($path);
$method = $_SERVER['REQUEST_METHOD'];

// Rimuove il prefisso del progetto (es. "/artifex")
$prefix = '/' . strtolower($appConfig['prjName']);
if (strpos($url, $prefix) === 0) {
    $url = substr($url, strlen($prefix));     // es. "/home/book-events"
}
$url = trim($url, '/');                        // es. "home/book-events"

// Connessione al database
require 'Database/DBconn.php';
$dataBaseConfig = require 'Database/databaseConfig.php';
$db = Database\DBconn::getDB($dataBaseConfig);

// Carica il gestore delle rotte
require 'Router/Router.php';
$routerClass = new \Router\Router();



// DEFINIZIONE DELLE ROTTE


//ROTTE GET
$routerClass->addRoute('GET', '', 'HomeController', 'presentationHome');
$routerClass->addRoute('GET', 'home/services', 'ServiceController', 'presentation3');
$routerClass->addRoute('GET', 'form/insert/visitor', 'UserController', 'formInsertOneVisitor');
$routerClass->addRoute('GET', 'error/errorpage', 'HomeController', 'showErrorPage');
$routerClass->addRoute('GET', 'form/login/admin', 'AdminController', 'formLoginAdmin');
$routerClass->addRoute('GET', 'form/login/visitor', 'UserController', 'formLoginVisitor');
$routerClass->addRoute('GET', 'logout', 'UserController', 'logoutPage');
$routerClass->addRoute('GET', 'info', 'UserController', 'infoProfilo');
$routerClass->addRoute('GET', 'info', 'AdminController', 'infoProfilo');
$routerClass->addRoute('GET', 'admin/dashboard', 'AdminController', 'dashboard');
$routerClass->addRoute('GET', 'visits', 'ServiceController', 'listVisits');
$routerClass->addRoute('GET', 'events', 'ServiceController', 'listEvents');

// Schedules (Programmazioni eventi/visite)
$routerClass->addRoute('GET', 'admin/schedules', 'AdminScheduleController', 'index');
$routerClass->addRoute('GET', 'admin/schedules/create', 'AdminScheduleController', 'createForm');

// ROTTE POST
$routerClass->addRoute('POST', 'insert/onevisitor', 'UserController', 'insertOneVisitor');
$routerClass->addRoute('POST', 'login/admin', 'AdminController', 'loginAdmin');
$routerClass->addRoute('POST', 'login/visitor', 'UserController', 'loginVisitor');
$routerClass->addRoute('POST', 'home/index', 'HomeController', 'presentation11');
$routerClass->addRoute('POST', 'home/services', 'ServiceController', 'presentation33');

// Cambio password (visitatori e admin)
$routerClass->addRoute('POST', 'visitor/changepwd', 'UserController', 'changePassword');
$routerClass->addRoute('POST', 'admin/changepwd', 'AdminController', 'changePassword');

// Prenotazione eventi (POST)
$routerClass->addRoute('POST', 'book-events', 'ServiceController', 'bookEventSubmit');

// Carrello
$routerClass->addRoute('GET', 'cart', 'CartController', 'index');
$routerClass->addRoute('POST', 'cart/remove', 'CartController', 'remove');
$routerClass->addRoute('GET',  'cart/checkout',  'CartController', 'checkoutForm');
$routerClass->addRoute('POST', 'cart/checkout',  'CartController', 'checkoutSubmit');
$routerClass->addRoute('GET',  'cart/generateTicket/{id}', 'CartController', 'generateTicket');
$routerClass->addRoute('POST', 'cart/checkout/pdf', 'CartController', 'checkoutAndGeneratePDF');
$routerClass->addRoute('POST', 'cart/pdf-preview', 'CartController', 'previewPDF');

// EVENTI (amministratore)
$routerClass->addRoute('GET', 'events_create', 'AdminController', 'createEventForm');
$routerClass->addRoute('POST', 'events_create', 'AdminController', 'createEvent');
$routerClass->addRoute('GET', 'events_edit/{id}', 'AdminController', 'editEventForm');
$routerClass->addRoute('POST', 'events_edit/{id}', 'AdminController', 'updateEvent');

// Associazione evento-visita
$routerClass->addRoute('GET',  'admin/event_visits/create', 'AdminController', 'createEventVisitForm');
$routerClass->addRoute('POST', 'admin/event_visits',        'AdminController', 'storeEventVisit');

// VISITE
$routerClass->addRoute('GET',  'visits_create', 'AdminController', 'createVisitForm');
$routerClass->addRoute('POST', 'visits_create', 'AdminController', 'createVisit');
$routerClass->addRoute('GET',  'visits_edit/{id}', 'AdminController', 'editVisitForm');
$routerClass->addRoute('POST', 'visits_edit/{id}', 'AdminController', 'editVisit');
$routerClass->addRoute('POST', 'visits_update', 'AdminController', 'updateVisit');
$routerClass->addRoute('GET',  'visits_delete/{id}', 'AdminController', 'deleteVisit');   // conferma
$routerClass->addRoute('POST', 'visits_delete/{id}', 'AdminController', 'deleteVisit');   // esecuzione

// GUIDE
$routerClass->addRoute('GET',  'guides', 'AdminController', 'guides');
$routerClass->addRoute('GET',  'guides_create', 'AdminController', 'createGuideForm');
$routerClass->addRoute('POST', 'guides_create', 'AdminController', 'storeGuide');
$routerClass->addRoute('GET',  'guides_edit/{id}', 'AdminController', 'editGuideForm');
$routerClass->addRoute('POST', 'guides_update/{id}', 'AdminController', 'updateGuide');
$routerClass->addRoute('GET',  'guides_delete/{id}', 'AdminController', 'deleteGuide');  // conferma
$routerClass->addRoute('POST', 'guides_delete/{id}', 'AdminController', 'deleteGuide');  // esecuzione




// ESECUZIONE DELLA ROTTA MATCHATA


// Tenta di trovare una rotta che corrisponda a URL + metodo HTTP
$reValue = $routerClass->match($url, $method);

if (empty($reValue)) {
    // Se nessuna rotta corrisponde, mostra errore 404
    http_response_code(404);
    die('Pagina non trovata');
}

// Costruisce il nome completo del controller (namespace incluso)
$controllerName = 'App\\Controller\\' . $reValue['controller'];
$action = $reValue['action'];
$params = $reValue['params'] ?? []; // Parametri dinamici dell'URL (es. {id})

// Carica il file del controller richiesto
require $controllerName . '.php';

// Crea una nuova istanza del controller, passando la connessione al DB
$controllerObj = new $controllerName($db);

// Esegue il metodo del controller con eventuali parametri dinamici
call_user_func_array([$controllerObj, $action], $params);

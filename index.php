<?php

$appConfig = require 'appConfig.php';
/*
$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$url = strtolower($url);

// Rimozione del prefisso tipo /artifex_buttini
$prefix = '/' . strtolower($appConfig['prjName']);
if (strpos($url, $prefix) === 0) {
    $url = substr($url, strlen($prefix));
}
$url = trim($url, '/');
*/

// all'inizio di index.php, subito dopo aver letto REQUEST_URI
$fullUri = $_SERVER['REQUEST_URI'];
$path = parse_url($fullUri, PHP_URL_PATH);     // "/Artifex/home/book-events"
$url = strtolower($path);
$method = $_SERVER['REQUEST_METHOD'];

// Rimozione del prefisso tipo /artifex
$prefix = '/' . strtolower($appConfig['prjName']);
if (strpos($url, $prefix) === 0) {
    $url = substr($url, strlen($prefix));     // "/home/book-events"
}
$url = trim($url, '/');                        // "home/book-events"


require 'Database/DBconn.php';
$dataBaseConfig = require 'Database/databaseConfig.php';
$db = Database\DBconn::getDB($dataBaseConfig);

require 'Router/Router.php';
$routerClass = new \Router\Router();



// ROTTE GET
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

// EVENTI
$routerClass->addRoute('GET', 'admin/events', 'AdminEventController', 'index');
$routerClass->addRoute('GET', 'admin/events/create', 'AdminEventController', 'createForm');
$routerClass->addRoute('GET', 'admin/events/edit/{id}', 'AdminEventController', 'editForm');

// PROGRAMMAZIONI
$routerClass->addRoute('GET', 'admin/schedules', 'AdminScheduleController', 'index');
$routerClass->addRoute('GET', 'admin/schedules/create', 'AdminScheduleController', 'createForm');

// GUIDE
$routerClass->addRoute('GET', 'admin/guides', 'AdminGuideController', 'index');
$routerClass->addRoute('GET', 'admin/guides/create', 'AdminGuideController', 'createForm');
$routerClass->addRoute('GET', 'admin/guides/edit/{id}', 'AdminGuideController', 'editForm');


// ROTTE POST
$routerClass->addRoute('POST', 'insert/onevisitor', 'UserController', 'insertOneVisitor');
$routerClass->addRoute('POST', 'login/admin', 'AdminController', 'loginAdmin');
$routerClass->addRoute('POST', 'login/visitor', 'UserController', 'loginVisitor');
$routerClass->addRoute('POST', 'home/index', 'HomeController', 'presentation11');
$routerClass->addRoute('POST', 'home/services', 'ServiceController', 'presentation33');
$routerClass->addRoute('POST', 'changepwd', 'UserController', 'changePassword');
$routerClass->addRoute('POST', 'changepwd', 'AdminController', 'changePassword');

// EVENTI
$routerClass->addRoute('POST', 'admin/events/create', 'AdminEventController', 'create');
$routerClass->addRoute('POST', 'admin/events/delete', 'AdminEventController', 'delete');
$routerClass->addRoute('POST', 'admin/events/update', 'AdminEventController', 'update');

// PROGRAMMAZIONI
$routerClass->addRoute('POST', 'admin/schedules/create', 'AdminScheduleController', 'create');
$routerClass->addRoute('POST', 'admin/schedules/delete', 'AdminScheduleController', 'delete');

// GUIDE
$routerClass->addRoute('POST', 'admin/guides/create', 'AdminGuideController', 'create');
$routerClass->addRoute('POST', 'admin/guides/delete', 'AdminGuideController', 'delete');
$routerClass->addRoute('POST', 'admin/guides/update', 'AdminGuideController', 'update');

//$routerClass->addRoute('GET',  'home/book-events',   'ServiceController', 'bookEventForm');
$routerClass->addRoute('POST', 'book-events',   'ServiceController', 'bookEventSubmit');
$routerClass->addRoute('GET', 'cart', 'CartController', 'index');
$routerClass->addRoute('POST', 'cart/remove', 'CartController', 'remove');

// Cart checkout
$routerClass->addRoute('GET',  'cart/checkout',  'CartController', 'checkoutForm');
$routerClass->addRoute('POST', 'cart/checkout',  'CartController', 'checkoutSubmit');







// MATCH E CHIAMATA DEL CONTROLLER/AZIONE
$reValue = $routerClass->match($url, $method);

if (empty($reValue)) {
    http_response_code(404);
    die('Pagina non trovata');
}

$controller = 'App\Controller\\' . $reValue['controller'];
$action = $reValue['action'];

require $controller . '.php';
$controllerObj = new $controller($db);
$controllerObj->$action();

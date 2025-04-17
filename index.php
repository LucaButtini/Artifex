<?php

$appConfig = require 'appConfig.php';

$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$url = strtolower($url);

// Rimozione del prefisso tipo /artifex_buttini
$prefix = '/' . strtolower($appConfig['prjName']);
if (strpos($url, $prefix) === 0) {
    $url = substr($url, strlen($prefix));
}
$url = trim($url, '/');

require 'Database/DBconn.php';
$dataBaseConfig = require 'Database/databaseConfig.php';
$db = Database\DBconn::getDB($dataBaseConfig);

require 'Router/Router.php';
$routerClass = new \Router\Router();

// ROTTE GET
$routerClass->addRoute('GET', '', 'HomeController', 'presentationHome');
$routerClass->addRoute('GET', 'home/services', 'ServiceController', 'presentation3');
$routerClass->addRoute('GET', 'form/insert/visitor', 'UserController', 'formInsertOneVisitor'); // FORM REGISTRAZIONE
$routerClass->addRoute('GET', 'error/errorpage', 'HomeController', 'showErrorPage');
$routerClass->addRoute('GET', 'form/login/admin', 'AdminController', 'formLoginAdmin'); // FORM LOGIN AMMINISTRATORE
$routerClass->addRoute('GET', 'form/login/visitor', 'UserController', 'formLoginVisitor'); // FORM LOGIN VISITATORE

// ROTTE POST
$routerClass->addRoute('POST', 'insert/onevisitor', 'UserController', 'insertOneVisitor'); // INSERIMENTO VISITATORE
$routerClass->addRoute('POST', 'login/admin', 'AdminController', 'loginAdmin'); // LOGIN AMMINISTRATORE
$routerClass->addRoute('POST', 'login/visitor', 'UserController', 'loginVisitor'); // LOGIN VISITATORE
$routerClass->addRoute('POST', 'home/index', 'HomeController', 'presentation11');
$routerClass->addRoute('POST', 'home/services', 'ServiceController', 'presentation33');

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

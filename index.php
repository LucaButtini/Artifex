<?php
/*$appConfig= require 'appConfig.php';
$url = $_SERVER['REQUEST_URI'];
$method =$_SERVER['REQUEST_METHOD'];
$url=strtolower($url);
$url=trim(str_replace($appConfig['prjName'],'',$url),'/');*/
$appConfig = require 'appConfig.php';

$url = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$url = strtolower($url);

// Rimozione robusta del prefisso
$prefix = '/' . strtolower($appConfig['prjName']);
if (strpos($url, $prefix) === 0) {
    $url = substr($url, strlen($prefix));
}
$url = trim($url, '/');

//echo "<pre>URL ricevuto: '$url'</pre>";

require "Database\DBconn.php";
$dataBaseConfig= require "Database/databaseConfig.php";
$db =Database\DBconn::getDB($dataBaseConfig);
//echo "<pre>URL ricevuto: '$url'</pre>";

require 'Router/Router.php';
$routerClass = new \Router\Router();
$routerClass->addRoute('GET','','HomeController','presentationHome');
$routerClass->addRoute('GET','home/products','ProductController','show1');
$routerClass->addRoute('GET','home/services','ServiceController','presentation3');
$routerClass->addRoute('GET','show/tablet','ProductController','showAllTablet');  /*PER VISULIZZARE TUTTI I TABLET*/
$routerClass->addRoute('GET','form/insert/tablet','ProductController','formInsertOneTablet'); /*PER VISULIZZARE IL FORM DI INSERIMENTO TABLET*/
$routerClass->addRoute('GET','error/errorpage','HomeController','showErrorPage');  /*PAGINA DI ERRORE PERSONALIZZABILE CON $CONTENT*/


$routerClass->addRoute('POST','insert/onetablet','ProductController','insertOneTablet'); /*PER INSERIRE IL TABLET NEL DB (ACTION DEL FORM-INSERT-ONE-TABLET)*/
$routerClass->addRoute('POST','home/index','HomeController','presentation11');
$routerClass->addRoute('POST','home/products','ProductController','show11');
$routerClass->addRoute('POST','home/services','ServiceController','presentation33');

$reValue=$routerClass->match($url,$method);
if(empty($reValue)) {
    http_response_code(404);
    die('Pagina non trova');
}
$controller= 'App\Controller\\'.$reValue['controller'];
$action = $reValue['action'];

require $controller.'.php';
$controllerObj = new $controller($db);
$controllerObj->$action();
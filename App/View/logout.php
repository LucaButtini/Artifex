<?php
session_start();
$appConfig = require dirname(__DIR__, 2) . '/appConfig.php';
$baseUrl = $appConfig['baseURL'] . $appConfig['prjName'];
$_SESSION=[];
session_destroy();
setcookie(session_name(),'', time()-3600,'/','', false, false);
header("Location: $baseUrl");
exit();
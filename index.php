<?php
session_start();
    
$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);

if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}
    
    require_once 'router/Bramus/Router/Router.php';

    $router = new \Bramus\Router\Router();

    $router->set404(function () {
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
        include_once "pages/404.php";
    });

    // Before Router Middleware
    $router->before('GET', '/.*', function () {
        header('Techno Soluciones SAS');
    });
    
    // Static route: / (homepage)
    $router->get('/', function () {
       $_SESSION["client_id"]="";
       include_once "modulos/main/main.php";
    });

    // Dynamic route: /client/client_id/*
    $router->get('/client/(.*)', function ($url) {        
        $url= htmlentities($url);
        $arrayUrl= explode("/", $url);
        $client_id=$arrayUrl[0];
        $_SESSION["client_id"]=$client_id;        
        include_once "modulos/main/main.php";
    });

    $router->run();



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
    /*
    // Static route: / (homepage)
    $router->get('/', function () {
       
       include_once "modulos/main/main.php";
    });
     * 
     */

    // Dynamic route: /client/client_id/*
    $router->get('/(.*)', function ($url) {  
        
        $url= htmlentities($url);
        $arrayUrl= explode("/", $url);
        foreach ($arrayUrl as $key => $value) {
            if($value=="local" and isset($arrayUrl[$key+1])){                
                $_REQUEST["local_id"]=$arrayUrl[$key+1];
            }            
            if($value=="product" and isset($arrayUrl[$key+1])){
                $_REQUEST["product_id"]=$arrayUrl[$key+1];
            }
            if($value=="page_id" and isset($arrayUrl[$key+1])){
                $_REQUEST["page_id"]=$arrayUrl[$key+1];
            }
        }
        $_REQUEST["actionPagesDraw"]=1; //Dibuja la pagina principal  
        include_once "modules/main/views/pages.draw.php";
        
    });
     
    $router->post('/views', function () {       
        include_once "modules/main/views/pages.draw.php";
     });
             
    $router->run();



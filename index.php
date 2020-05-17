<?php
session_start();
require_once 'constructores/paginas_constructor.php';   

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

    
    $router->get('/searchMunicipalities', function () {       
        include_once "general/buscadores/catalogo_municipios.search.php";
     });
     
     $router->post('/views', function () {       
        include_once "modules/main/views/pages.draw.php";
     });
     
    $router->get('/(.*)', function ($url) {  
        
        $url= htmlentities($url);
        $arrayUrl= explode("/", $url);
        $page_id=0;
        $local_id=1;
        $product_id="";
        foreach ($arrayUrl as $key => $value) {
            if($value=="local" and isset($arrayUrl[$key+1])){                
               $local_id=$arrayUrl[$key+1];
            }            
            if($value=="product" and isset($arrayUrl[$key+1])){
                $product_id=$arrayUrl[$key+1];
            }
            if($value=="page_id" and isset($arrayUrl[$key+1])){
               $page_id=$arrayUrl[$key+1];
            }
        }
        $_REQUEST["actionPagesDraw"]=1; //Dibuja la pagina principal  
        
        $css =  new PageConstruct($local_id,$page_id);
        include_once "modules/main/views/pages.draw.php";
        print($css->get_JSGeneral());
        
        $css->Cbody();
        $css->Chtml();
    });
     
    
     
     $router->post('/processShopping', function () {       
        include_once "modules/main/process/shopping.process.php";
     });
     
     
             
    $router->run();



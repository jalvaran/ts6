<?php
session_start();
require_once 'constructores/paginas_constructor.php'; 
require_once 'modelo/php_conexion.php';

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
     
     $router->post('/viewsAdmin', function () {       
        include_once "modules/admin/views/admin.draw.php";
     });
     
     $router->post('/processShopping', function () {       
        include_once "modules/main/process/shopping.process.php";
     });
     
     $router->post('/processMigrations', function () {       
        include_once "modules/admin/process/migrations.process.php";
     });
     
     $router->post('/processAdminShop', function () {       
        include_once "modules/admin/process/admin.process.php";
     });
          
    $router->get('/(.*)', function ($url) {  
        $obCon=new conexion(1);
        $url= htmlentities($url);
        $arrayUrl= explode("/", $url);
        
        $page_id=0;
        $local_id=1;
        $product_id="";
        $admin=0;
        if(isset($arrayUrl[0])){
            $local_url=strtolower($obCon->normalizar($arrayUrl[0]));
            $sql="SELECT ID FROM locales WHERE urlLocal='$local_url' AND urlLocal<>''";
            $dataQuery=$obCon->FetchAssoc($obCon->Query($sql));
            if($dataQuery["ID"]<>''){
                $local_id=$dataQuery["ID"];
            }
        }
        
        foreach ($arrayUrl as $key => $value) {            
            if($value=="local" and isset($arrayUrl[$key+1])){                
               $local_id=$obCon->normalizar($arrayUrl[$key+1]);
            }            
            if($value=="product" and isset($arrayUrl[$key+1])){
                $product_id=$obCon->normalizar($arrayUrl[$key+1]);
            }
            if($value=="page_id" and isset($arrayUrl[$key+1])){
               $page_id=$obCon->normalizar($arrayUrl[$key+1]);
            }
            if($value=="admin"){
               $admin=1;
            }
        }
        
        
        
        $_REQUEST["actionPagesDraw"]=1; //Dibuja la pagina principal  
        if($admin==0){
            $css =  new PageConstruct($local_id,$page_id);        
            include_once "modules/main/views/pages.draw.php";
            print($css->get_JSGeneral());
        }
        
        if($admin==1){
            $_REQUEST["actionPagesDrawAdmin"]=1; //Dibuja la pagina principal        
                 
            include_once "modules/admin/views/admin.draw.php";
            
        }
        
        $css->Cbody();
        $css->Chtml();
    });
     
                 
    $router->run();



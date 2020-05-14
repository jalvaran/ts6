<?php

if(!empty($_REQUEST["actionPagesDraw"])){// se verifica si el indice accion es diferente a vacio 
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    
    include_once("constructores/paginas_constructor.php");
    include_once("modelo/php_conexion.php");
    $obCon = new conexion(""); //Conexion a la base de datos
    
    if(isset($_REQUEST["local_id"])){
        $local_id=$obCon->normalizar($_REQUEST["local_id"]);
    }else{
        $local_id=1;
    }
    $client_id=$local_id; 
    $Domain=$_SERVER['HTTP_HOST'];
    $ipUser=$_SERVER['REMOTE_ADDR'];
    $user_id="";
    if(isset($_REQUEST["idClientUser"])){
        $user_id=$obCon->normalizar($_REQUEST["idClientUser"]);
        $sql="SELECT ID from client_user WHERE Betado=1 AND ID='$user_id'";
        $ConsultaBeto=$obCon->FetchAssoc($obCon->Query($sql));
        if($ConsultaBeto["ID"]<>''){
            exit("<h1>Fuera de Linea</h1>");
        }
    
    }
   
    switch($_REQUEST["actionPagesDraw"]){
       
        case 1://Dibuja una pagina
            if(isset($_REQUEST["page_id"])){
                $page_id=$obCon->normalizar($_REQUEST["page_id"]);
            }else{
                $page_id=0;
            }
            
            $css =  new PageConstruct($local_id,$page_id);
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            print($css->get_page());
            print("<script>local_id=$client_id;<script>");
            $css->Cbody();
            $css->Chtml();
            
        break;//Fin caso 1
        
        case 2://Dibuje los sliders y secciones de una pagina
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $page_id=$obCon->normalizar($_REQUEST["page_id"]); 
            $local_id=$obCon->normalizar($_REQUEST["local_id"]);      
            $css =  new PageConstruct($local_id,$page_id,$path);            
            print($css->get_contentPage());
            if($page_id==3){//si se solicita la tienda virtual
                print($css->get_virtual_shop());
            }
            
        break;//Fin caso 2 
        
        
          
        
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>
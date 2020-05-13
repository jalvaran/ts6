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
                $page_id=1;
            }
            
            $css =  new PageConstruct($local_id,$page_id);
            if($css->dataClient["id"]==''){
                include_once "pages/404.php";
                exit();
            }
            print($css->get_page());
            print("<script>local_id=$client_id;<script>");
            $css->Cbody();
            $css->Chtml();
            
        break;//Fin caso 1
        
        case 2://Dibuje las secciones de la pagina
            
            $css =  new PageConstruct($client_id);
            $client_db=$css->dataClient["db"];
            $page_id=$obCon->normalizar($_REQUEST["page_id"]);            
            $sql="SELECT * FROM clients_has_sections WHERE client_id='$client_id' AND page_id='$page_id' AND status_section=1 ORDER BY order_section,id ASC";
            $query=$obCon->Query($sql);
            while($dataSection=$obCon->FetchAssoc($query)){
                $section_id=$dataSection["pages_sections_id"];
                $sql="SELECT text_content FROM web_sections_content WHERE section_id='$section_id'";
                $dataHtml=$obCon->FetchAssoc($obCon->QueryExterno($sql, HOST, USER, PW, $client_db, ""));
                print(utf8_encode($dataHtml["text_content"]));
            }
        break;//Fin caso 2 
          
        
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>
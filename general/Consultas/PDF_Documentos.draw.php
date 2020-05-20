<?php 
if(isset($_REQUEST["idDocumento"])){
    $myPage="PDF_Documentos.draw.php";
    include_once("modelo/php_conexion.php");
    
    include_once("general/class/ClasesPDFDocumentos.class.php");
    
    $idUser=1;
    $obCon = new conexion($idUser);
    
    $obDoc = new Documento(DB);
    $idDocumento=$obCon->normalizar($_REQUEST["idDocumento"]);
    
    
    switch ($idDocumento){
        case 1: //PDF para un pedido
            if(isset($_REQUEST["idPedido"])){
                $idPedido=$obCon->normalizar($_REQUEST["idPedido"]);
            }else{
                $idPedido=$obCon->normalizar($_REQUEST["ID"]);
            }
            
            $obDoc->PedidoDomiPDF($idPedido,"");            
        break;//Fin caso 1
       
    }
}else{
    print("No se recibió parametro de documento");
}

?>
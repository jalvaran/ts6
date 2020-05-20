<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once("modules/admin/class/admin.class.php");
if( !empty($_REQUEST["action"]) ){
    
    $obCon=new Admin(1);
    
    switch ($_REQUEST["action"]) {
        
         
        case 1://Cambia de estado un pedido
            
            $Estado=$obCon->normalizar($_REQUEST["Estado"]);
            $idPedido=$obCon->normalizar($_REQUEST["item_id"]);
            if($idPedido==''){
                exit("E1;No se recibió el id del pedido");
            }
            if($Estado==''){
                exit("E1;Seleccione una opción");
            }
            $sql="UPDATE pedidos SET Estado='$Estado' WHERE ID='$idPedido'";
            $obCon->Query($sql);
            $DatosEstados=$obCon->DevuelveValores("pedidos_estados", "ID", $Estado);
            print("OK;El estado del pedido fué actualizado;".$DatosEstados["EstadoPedido"]);
            
        break;//fin caso 1    
        
    }
          
}else{
    print("No se enviaron parametros");
}
?>
<?php

include_once("modelo/php_conexion.php");

class Shopping extends conexion{
    
    public function CrearUsuarioCliente($user_id,$Nombre,$Direccion,$Telefono) {
        $Validacion= $this->DevuelveValores("client_user", "ID", $user_id);
        if($Validacion['ID']<>''){
            return;
        }
        $tab="client_user";
        
        $Datos["ID"]=$user_id;	
        $Datos["Nombre"]=$Nombre;	
        $Datos["Direccion"]=$Direccion;           
        $Datos["Telefono"]=$Telefono;		
        $Datos["Created"]=date("Y-m-d H:i:s");	
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
    }
    
    public function CrearPedido($user_id,$local_id,$Total,$Observaciones,$Estado) {
        $sql="SELECT ID FROM pedidos WHERE cliente_id='$user_id' AND local_id='$local_id' AND Estado=1;";
        $Validacion= $this->FetchAssoc($this->Query($sql));        
        if($Validacion['ID']<>''){
            return($Validacion['ID']);
        }
        $ID=$this->getUniqId($local_id);
        $tab="pedidos";        
        $Datos["ID"]=$ID;	
        $Datos["cliente_id"]=$user_id;	
        $Datos["local_id"]=$local_id;           
        $Datos["Total"]=$Total;
        $Datos["Observaciones"]=$Observaciones;
        $Datos["Estado"]=$Estado;
        $Datos["Created"]=date("Y-m-d H:i:s");	
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
        return($ID);
    }
    
    public function AgregarItemAPedido($db,$pedido_id,$product_id,$Cantidad,$ValorUnitario,$Observaciones) {
        
        $tab="pedidos_items";   
        $sql="SELECT ID,Cantidad FROM $tab WHERE pedido_id='$pedido_id' AND product_id='$product_id';";
        $Consulta= $this->Query2($sql, HOST, USER, PW, $db, ""); 
        $DatosItem= $this->FetchAssoc($Consulta);
        
        if($DatosItem['ID']<>''){      
            $ID=$DatosItem['ID'];
            $Cantidad=$DatosItem['Cantidad']+1;
            $Total=round($ValorUnitario*$Cantidad,2);
            $Datos["Cantidad"]=$Cantidad;
            $Datos["Total"]=$Total;
            $Datos["Observaciones"]=$Observaciones;
            $Datos["ValorUnitario"]=$ValorUnitario;
            $sql=$this->getSQLUpdate($tab, $Datos);
            $sql.=" WHERE ID='$ID'";            
        }else{
            $ID=$this->getUniqId("p_");
                 
            $Datos["ID"]=$ID;	
            $Datos["pedido_id"]=$pedido_id;	
            $Datos["product_id"]=$product_id;           
            $Datos["Observaciones"]=$Observaciones;
            $Datos["Cantidad"]=$Cantidad;
            $Datos["ValorUnitario"]=$ValorUnitario;
            $Datos["Total"]=$ValorUnitario;
            $Datos["Estado"]=1;
            $Datos["Created"]=date("Y-m-d H:i:s");	
            $sql=$this->getSQLInsert($tab, $Datos);
        }
        
        $this->Query2($sql, HOST, USER, PW, $db, ""); 
        return($ID);
    }
    
    
    public function ActualizarValoresPedido($idLocal,$idPedido) {
        $dbLocal=$this->getDataBaseLocal($idLocal);
        $sql="SELECT SUM(Cantidad) as Items, SUM(Total) AS Total FROM pedidos_items WHERE pedido_id='$idPedido' ";
        
        $DatosItems= $this->FetchAssoc($this->Query2($sql, HOST, USER, PW, $dbLocal, ""));
        
        $Cantidad=$DatosItems["Items"];
        
        $Total=$DatosItems["Total"];
        $sql="UPDATE pedidos SET CantidadItems='$Cantidad', Total='$Total' WHERE ID='$idPedido'";
        $this->Query($sql);
        
        
    }
    
    function ActualiceDatosCliente($idCliente,$Nombre,$Direccion,$Telefono) {
        $Datos["Nombre"]=$Nombre;
        $Datos["Direccion"]=$Direccion;
        $Datos["Telefono"]=$Telefono;
        
        $sql=$this->getSQLUpdate("client_user", $Datos);
        $sql.=" WHERE ID='$idCliente'";
        $this->Query($sql);
    }
    
    function ActualiceDatosClienteAcceso($idCliente,$Email,$Password,$Token) {
        $Datos["Email"]=$Email;
        $Datos["Password"]=$Password;
        $Datos["user_token"]=$Token;
        
        $sql=$this->getSQLUpdate("client_user", $Datos);
        $sql.=" WHERE ID='$idCliente'";
        $this->Query($sql);
    }
    
   
    /**
     * Fin Clase
     */
}

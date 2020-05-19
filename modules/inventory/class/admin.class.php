<?php
if(file_exists("../../../modelo/php_conexion.php")){
    include_once("../../../modelo/php_conexion.php");
}
class Admin extends conexion{
    
            
    public function RegistreImagenProducto($DataBase,$idProducto,$destino,$Tamano, $NombreArchivo, $Extension, $idUser) {
        
        $tab="productos_servicios_imagenes";
        
        $Datos["idProducto"]=$idProducto;
        
        $Datos["Ruta"]=$destino;    
        $Datos["NombreArchivo"]=$NombreArchivo;    
        $Datos["Extension"]=$Extension;    
        $Datos["Tamano"]=$Tamano; 
        $Datos["idUser"]=$idUser;		
        $Datos["Created"]=date("Y-m-d H:i:s");	
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->QueryExterno($sql, HOST, USER, PW, $DataBase, "");
    }
    
    public function RegistreFondoLocal($idLocal,$destino,$Tamano, $NombreArchivo, $Extension, $idUser) {
        
        $tab="locales_imagenes";
        
        $Datos["idLocal"]=$idLocal;
        
        $Datos["Ruta"]=$destino;    
        $Datos["NombreArchivo"]=$NombreArchivo;    
        $Datos["Extension"]=$Extension;    
        $Datos["Tamano"]=$Tamano; 
        $Datos["idUser"]=$idUser;		
        $Datos["Created"]=date("Y-m-d H:i:s");	
        $sql=$this->getSQLInsert($tab, $Datos);
        $this->Query($sql);
        
    }
    
    /**
     * Fin Clase
     */
}

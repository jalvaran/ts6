<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once("modules/admin/class/admin.class.php");
if( !empty($_REQUEST["action"]) ){
    
    $obCon=new Admin(1);
    
    switch ($_REQUEST["action"]) {
        
         
        case 1://Guardar o editar clasificacion
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $Datos["Estado"]=$obCon->normalizar($_REQUEST["Estado"]);
            $Datos["Clasificacion"]=$obCon->normalizar($_REQUEST["Clasificacion"]);
            foreach ($Datos as $key => $value) {
                if($value==""){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            $Token=$obCon->normalizar($_REQUEST["Token_user"]);
            $DatosSesion=$obCon->VerificaSesion($Token);
            if($DatosSesion["Estado"]=="E1"){               
                exit($DatosSesion["Estado"].";".$DatosSesion["Mensaje"]);
            }
            $idLocal=$_SESSION["idLocal"];
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);
            $Tabla="inventarios_clasificacion";
            if($idItem==''){
                $sql=$obCon->getSQLInsert($Tabla, $Datos);
            }else{
                $sql=$obCon->getSQLUpdate($Tabla, $Datos);
                $sql.=" WHERE ID='$idItem'";
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            
            print("OK;Registro Guardado");
        break;//Fin caso 1
        
        case 2://Guardar o editar producto
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $Datos["ID"]=$obCon->normalizar($_REQUEST["ID"]);
            $Datos["Estado"]=$obCon->normalizar($_REQUEST["Estado"]);
            $Datos["idClasificacion"]=$obCon->normalizar($_REQUEST["idClasificacion"]);
            $Datos["Referencia"]=$obCon->normalizar($_REQUEST["Referencia"]);
            $Datos["Nombre"]=$obCon->normalizar($_REQUEST["Nombre"]);
            $Datos["PrecioVenta"]=$obCon->normalizar($_REQUEST["PrecioVenta"]);
            $Datos["DescripcionCorta"]=$obCon->normalizar($_REQUEST["DescripcionCorta"]);
            $Datos["DescripcionLarga"]=$obCon->normalizar($_REQUEST["DescripcionLarga"]);
            $Datos["Orden"]=$obCon->normalizar($_REQUEST["Orden"]);
            foreach ($Datos as $key => $value) {
                if($value=="" AND $key<>'Orden' AND $key<>'DescripcionCorta' AND $key<>'DescripcionLarga'){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            if(!is_numeric($Datos["Orden"]) or $Datos["Orden"]<0){
                exit("E1;El campo Orden Debe ser un numero mayor o igual a cero;Orden");
            }
            if(!is_numeric($Datos["PrecioVenta"]) or $Datos["PrecioVenta"]<0){
                exit("E1;El campo Orden Debe ser un numero mayor o igual a cero;PrecioVenta");
            }
            $Token=$obCon->normalizar($_REQUEST["Token_user"]);
            $DatosSesion=$obCon->VerificaSesion($Token);
            if($DatosSesion["Estado"]=="E1"){               
                exit($DatosSesion["Estado"].";".$DatosSesion["Mensaje"]);
            }
            
            $idLocal=$_SESSION["idLocal"];
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);
            $Tabla="productos_servicios";
            
            if($idItem==''){
                
                $idProducto=$Datos["ID"];
                $Datos["Created"]=date("Y-m-d H:i:s");
                $sql=$obCon->getSQLInsert($Tabla, $Datos);
            }else{
                $sql=$obCon->getSQLUpdate($Tabla, $Datos);
                $sql.=" WHERE ID='$idItem'";
                $idProducto=$idItem;
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            
            print("OK;Producto Creado");
        break;//Fin caso 2
    
        case 3://Guarda la imagen de un producto
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            
            $idProducto=$obCon->normalizar($_REQUEST["product_id"]);
            $idItem=$idProducto;
            $idLocal=$_SESSION["idLocal"];
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);
            $Tabla="productos_servicios";
                
            
            $Extension="";
            if(!empty($_FILES['ImagenProducto']['name'])){
                
                $info = new SplFileInfo($_FILES['ImagenProducto']['name']);
                $Extension=($info->getExtension()); 
                if($Extension<>'jpg' and $Extension<>'png' and $Extension<>'jpeg'){
                    //exit("E1;Solo se permiten imagenes;ImagenProducto");
                }
                $Tamano=filesize($_FILES['ImagenProducto']['tmp_name']);
                $DatosConfiguracion=$obCon->DevuelveValores("configuracion_general", "ID", 2001);
                
                $carpeta=$DatosConfiguracion["Valor"];
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta=$DatosConfiguracion["Valor"].$idLocal."/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta=$DatosConfiguracion["Valor"].$idLocal."/".$idProducto."/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                
                opendir($carpeta);
                $idAdjunto=uniqid(true);
                $destino=$carpeta.$idAdjunto.".".$Extension;
                
                move_uploaded_file($_FILES['ImagenProducto']['tmp_name'],$destino);
                $obCon->RegistreImagenProducto($DatosLocal["db"],$idProducto, $destino, $Tamano, $_FILES['ImagenProducto']['name'], $Extension, 1);
                exit("OK;Imagen Archivada");
            }else{
                exit("E1;no se recibió el archivo");
            }
            
        break;//Fin caso 3
        
        case 7://Guardar la foto de un producto
            $Token=$obCon->normalizar($_REQUEST["Token_user"]);
            $DatosSesion=$obCon->VerificaSesion($Token);
            if($DatosSesion["Estado"]=="E1"){               
                exit($DatosSesion["Estado"].";".$DatosSesion["Mensaje"]);
            }
            $idLocal=$obCon->normalizar($_SESSION["idLocal"]);
            $idProducto=$obCon->normalizar($_REQUEST["idProducto"]);
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);
            $Extension="";
            if(!empty($_FILES['imgProducto']['name'])){
                
                $info = new SplFileInfo($_FILES['imgProducto']['name']);
                $Extension=($info->getExtension()); 
                if($Extension<>'jpg' and $Extension<>'png' and $Extension<>'jpeg'){
                    exit("E1;Solo se permiten imagenes;imgProducto");
                }
                $Tamano=filesize($_FILES['imgProducto']['tmp_name']);
                $DatosConfiguracion=$obCon->DevuelveValores("configuracion_general", "ID", 2001);
                
                $carpeta=$DatosConfiguracion["Valor"];
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta=$DatosConfiguracion["Valor"].$idLocal."/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta=$DatosConfiguracion["Valor"].$idLocal."/".$idProducto."/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                
                opendir($carpeta);
                $idAdjunto=uniqid(true);
                $destino=$carpeta.$idAdjunto.".".$Extension;
                
                move_uploaded_file($_FILES['imgProducto']['tmp_name'],$destino);
                $obCon->RegistreImagenProducto($DatosLocal["db"],$idProducto, $destino, $Tamano, $_FILES['imgProducto']['name'], $Extension, 1);
            }else{
                exit("E1;No se recibió la imagen;imgProducto");
            }
            print("OK;Imagen agregada");
        break;//fin caso 7   
        
        case 8://Elimina una foto de un producto
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $idEditar=$idItem;
            
            $Token=$obCon->normalizar($_REQUEST["Token_user"]);
            $DatosSesion=$obCon->VerificaSesion($Token);
            if($DatosSesion["Estado"]=="E1"){               
                exit($DatosSesion["Estado"].";".$DatosSesion["Mensaje"]);
            }
            
                        
            $idLocal=$_SESSION["idLocal"];
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);
            $DatosServidor["IP"]=HOST;
            $DatosServidor["Usuario"]=USER;
            $DatosServidor["Password"]=PW;
            $DatosServidor["DataBase"]=$DatosLocal["db"];
            
            
            $sql="SELECT Ruta FROM productos_servicios_imagenes WHERE ID='$idItem' LIMIT 1";
            $Consulta=$obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
            $DatosValidacion=$obCon->FetchAssoc($Consulta);
            if (file_exists($DatosValidacion["Ruta"])) {
                unlink($DatosValidacion["Ruta"]);
            }
            $sql="DELETE FROM productos_servicios_imagenes WHERE ID='$idItem'";
            $obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
                
            print("OK;Registro Guardado Correctamente;$idEditar");
            
        break;//Fin caso 8
        
        case 9://Recibe la creacion de un producto en formato rapido y con multiples imagenes
            
            $idItem='';
            $Datos["Estado"]=1;
            $Datos["idClasificacion"]=$obCon->normalizar($_REQUEST["idClasificacion"]);
            $Datos["Referencia"]=$obCon->getUniqId();
            $Datos["Nombre"]=$obCon->normalizar($_REQUEST["Nombre"]);
            $Datos["PrecioVenta"]=$obCon->normalizar($_REQUEST["PrecioVenta"]);            
            $Datos["Orden"]=1;
            foreach ($Datos as $key => $value) {
                if($value==""){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            
            if(!is_numeric($Datos["PrecioVenta"]) or $Datos["PrecioVenta"]<0){
                exit("E1;El campo Orden Debe ser un numero mayor o igual a cero;PrecioVenta");
            }
            $Token=$obCon->normalizar($_REQUEST["Token_user"]);
            $DatosSesion=$obCon->VerificaSesion($Token);
            if($DatosSesion["Estado"]=="E1"){               
                exit($DatosSesion["Estado"].";".$DatosSesion["Mensaje"]);
            }
            if(!isset($_FILES['ImagenProducto']['name'])){
                exit("E1;Debe Adjuntar una Imagen para el Producto;imgsProducto");
            }
            foreach ($_FILES['ImagenProducto']['name'] as $key => $NombreArchivo) {
                if(empty($NombreArchivo)){
                    exit("E1;Debe Adjuntar una Imagen para el Producto;imgsProducto");
                }else{
                    $info = new SplFileInfo($NombreArchivo);
                    $Extension=($info->getExtension());  
                    if($Extension<>'jpg' and $Extension<>'png' and $Extension<>'jpeg' and $Extension<>'webp'){
                        exit("E1;Solo se permiten imagenes;imgsProducto");
                    }
                } 
            }
                            
            
            $idLocal=$_SESSION["idLocal"];
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);
            $Tabla="productos_servicios";
            
            $Datos["ID"]=$obCon->getUniqId();
            $idProducto=$Datos["ID"];
            $Datos["Created"]=date("Y-m-d H:i:s");
            $sql=$obCon->getSQLInsert($Tabla, $Datos);            
            $obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            
            foreach ($_FILES['ImagenProducto']["name"] as $key => $NombreArchivo) {
                
                $Extension="";
                if(!empty($NombreArchivo)){
                    
                    $info = new SplFileInfo($_FILES['ImagenProducto']["name"][$key]);
                    $Extension=($info->getExtension()); 
                    
                    $Tamano=filesize($_FILES['ImagenProducto']['tmp_name'][$key]);
                    $DatosConfiguracion=$obCon->DevuelveValores("configuracion_general", "ID", 2001);

                    $carpeta=$DatosConfiguracion["Valor"];
                    if (!file_exists($carpeta)) {
                        mkdir($carpeta, 0777);
                    }
                    $carpeta=$DatosConfiguracion["Valor"].$idLocal."/";
                    if (!file_exists($carpeta)) {
                        mkdir($carpeta, 0777);
                    }
                    $carpeta=$DatosConfiguracion["Valor"].$idLocal."/".$idProducto."/";
                    if (!file_exists($carpeta)) {
                        mkdir($carpeta, 0777);
                    }

                    opendir($carpeta);
                    $idAdjunto=uniqid(true);
                    $destino=$carpeta.$idAdjunto.".".$Extension;

                    move_uploaded_file($_FILES['ImagenProducto']['tmp_name'][$key],$destino);
                    $obCon->RegistreImagenProducto($DatosLocal["db"],$idProducto, $destino, $Tamano, $_FILES['ImagenProducto']['name'][$key], $Extension, 1);
                }
            }
            print("OK;Registro Guardado");
            
        break;//Fin caso 9  
        
        case 10://Recibir el logo y foto de un local
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $typeImage=$obCon->normalizar($_REQUEST["typeImage"]);
            $form_identify=$obCon->normalizar($_REQUEST["form_identify"]);
            $dir_subida = 'tmp/';
            if (!file_exists($dir_subida)) {
                mkdir($dir_subida, 0777);
            }
            $dir_subida.=$form_identify."/";
            if (!file_exists($dir_subida)) {
                mkdir($dir_subida, 0777);
            }
            if($typeImage==0){
                $name="logo-header.png";
            }else if($typeImage==1){
                $name="local-foto.png";
            }else{
                exit("E1;No se recibió el tipo de logo");
            }
            
            $fichero_subido = $dir_subida . basename($name);
            
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $fichero_subido)) {
                print("OK;El archivo fué subido con éxito");
            } else {
                print("E1;Error al mover el archivo");
            }
           
        break;//Fin caso 10
        
    }
          
}else{
    print("No se enviaron parametros");
}
?>
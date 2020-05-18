<?php
if(session_status() !== PHP_SESSION_ACTIVE) session_start();
include_once("modules/admin/class/admin.class.php");

if( !empty($_REQUEST["actionAdmin"]) ){
    
    $obCon=new Admin(1);
    
    switch ($_REQUEST["actionAdmin"]) {
        
        case 1: //Validar inicio de sesion y setearla
            $user_domi= str_replace(" ", "", $obCon->normalizar($_REQUEST["user_domi"]));
            $pw_domi=str_replace(" ", "",$obCon->normalizar($_REQUEST["pw_domi"]));
            $sql="SELECT ID,Nombre FROM locales WHERE Email LIKE '$user_domi' AND Password='$pw_domi'";
            $DatosValidacion=$obCon->FetchAssoc($obCon->Query($sql));
            if($DatosValidacion["ID"]==''){
                exit("E1;Usuario o Contraseña incorrectos");
            }else{
                $_SESSION['idLocal'] = $DatosValidacion["ID"];
                $_SESSION['Token'] = $_REQUEST["Token_user"];
                exit("OK;Bienvenid@ ".$DatosValidacion["Nombre"]);
                
            }
        break;//Fin caso 1
        
        case 2://Destruir sesion
            session_destroy();
            print("OK;Sesion terminada");
        break;//Fin caso 2   
        
        case 3://Cambiar pedido de estado
            $Estado=$obCon->normalizar($_REQUEST["Estado"]);
            $idPedido=$obCon->normalizar($_REQUEST["idPedido"]);
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
        break;//Fin caso 4    
        
        case 4://Guardar o editar clasificacion
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
        break;//Fin caso 4
        
        case 5://Guardar o editar producto
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $Datos["Estado"]=$obCon->normalizar($_REQUEST["Estado"]);
            $Datos["idClasificacion"]=$obCon->normalizar($_REQUEST["idClasificacion"]);
            $Datos["Referencia"]=$obCon->normalizar($_REQUEST["Referencia"]);
            $Datos["Nombre"]=$obCon->normalizar($_REQUEST["Nombre"]);
            $Datos["PrecioVenta"]=$obCon->normalizar($_REQUEST["PrecioVenta"]);
            $Datos["DescripcionCorta"]=$obCon->normalizar($_REQUEST["DescripcionCorta"]);
            $Datos["DescripcionLarga"]=$obCon->normalizar($_REQUEST["DescripcionLarga"]);
            $Datos["Orden"]=$obCon->normalizar($_REQUEST["Orden"]);
            foreach ($Datos as $key => $value) {
                if($value=="" AND $key<>'Orden'){
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
            
            if($idItem==''){
                if(empty($_FILES['ImagenProducto']['name'])){

                    exit("E1;Debe Adjuntar una Imagen para el Producto;ImagenProducto");
                }else{
                    $info = new SplFileInfo($_FILES['ImagenProducto']['name']);
                    $Extension=($info->getExtension());  
                    if($Extension<>'jpg' and $Extension<>'png' and $Extension<>'jpeg'){
                        exit("E1;Solo se permiten imagenes;ImagenProducto");
                    }
                } 
            }
            
            $idLocal=$_SESSION["idLocal"];
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);
            $Tabla="productos_servicios";
            if($idItem==''){
                $Datos["ID"]=$obCon->getUniqId();
                $idProducto=$Datos["ID"];
                $Datos["Created"]=date("Y-m-d H:i:s");
                $sql=$obCon->getSQLInsert($Tabla, $Datos);
            }else{
                $sql=$obCon->getSQLUpdate($Tabla, $Datos);
                $sql.=" WHERE ID='$idItem'";
                $idProducto=$idItem;
            }
            $obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            
            
            $Extension="";
            if(!empty($_FILES['ImagenProducto']['name'])){
                
                $info = new SplFileInfo($_FILES['ImagenProducto']['name']);
                $Extension=($info->getExtension()); 
                if($Extension<>'jpg' and $Extension<>'png' and $Extension<>'jpeg'){
                    exit("E1;Solo se permiten imagenes;ImagenProducto");
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
                
                
                if($idItem<>''){
                    $sql="SELECT ID,Ruta FROM productos_servicios_imagenes WHERE idProducto='$idItem' LIMIT 1";
                    $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
                    $DatosValidacion=$obCon->FetchAssoc($Consulta);
                    $idImagen=$DatosValidacion["ID"];
                    if (file_exists($DatosValidacion["Ruta"])) {
                        unlink($DatosValidacion["Ruta"]);
                    }
                    $sql="DELETE FROM productos_servicios_imagenes WHERE ID='$idImagen'";
                    $obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
                    $idProducto=$idItem;
                }
                move_uploaded_file($_FILES['ImagenProducto']['tmp_name'],$destino);
                $obCon->RegistreImagenProducto($DatosLocal["db"],$idProducto, $destino, $Tamano, $_FILES['ImagenProducto']['name'], $Extension, 1);
            }
            
            print("OK;Registro Guardado");
        break;//Fin caso 5
    
        case 6://Guarda el formulario del local
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            $idEditar=$idItem;
            $Datos["idCategoria"]=$obCon->normalizar($_REQUEST["idCategoria"]);
            $Datos["Nombre"]=$obCon->normalizar($_REQUEST["Nombre"]);
            $Datos["Direccion"]=$obCon->normalizar($_REQUEST["Direccion"]);
            $Datos["Telefono"]=$obCon->normalizar($_REQUEST["Telefono"]);
            $Datos["Propietario"]=$obCon->normalizar($_REQUEST["Propietario"]);
            $Datos["Tarifa"]=$obCon->normalizar($_REQUEST["Tarifa"]);
            $Datos["Email"]=$obCon->normalizar($_REQUEST["Email"]);
            $Datos["Password"]=$obCon->normalizar($_REQUEST["Password"]);
            $Datos["Descripcion"]=$obCon->normalizar($_REQUEST["Descripcion"]);
            $Datos["Orden"]=$obCon->normalizar($_REQUEST["Orden"]);
            $Datos["Estado"]=$obCon->normalizar($_REQUEST["Estado"]);
            foreach ($Datos as $key => $value) {
                if($value=="" AND $key<>'Orden'){
                    exit("E1;El campo $key no puede estar vacío;$key");
                }
            }
            if(!is_numeric($Datos["Orden"]) or $Datos["Orden"]<0){
                exit("E1;El campo Orden Debe ser un numero mayor o igual a cero;Orden");
            }
            if(!filter_var($Datos["Email"], FILTER_VALIDATE_EMAIL)){
                exit("E1;El campo Email No contiene un Correo válido;Email");
            }
            $Token=$obCon->normalizar($_REQUEST["Token_user"]);
            $DatosSesion=$obCon->VerificaSesion($Token);
            if($DatosSesion["Estado"]=="E1"){               
                exit($DatosSesion["Estado"].";".$DatosSesion["Mensaje"]);
            }
            
            if($idItem==''){
                if(empty($_FILES['Fondo']['name'])){

                    exit("E1;Debe Adjuntar una Imagen para el Local;Fondo");
                }else{
                    $info = new SplFileInfo($_FILES['Fondo']['name']);
                    $Extension=($info->getExtension());  
                    if($Extension<>'jpg' and $Extension<>'png' and $Extension<>'jpeg'){
                        exit("E1;Solo se permiten imagenes;ImagenProducto");
                    }
                } 
            }
            
            //$idLocal=$_SESSION["idLocal"];
            $DatosServidor["IP"]=HOST;
            $DatosServidor["Usuario"]=USER;
            $DatosServidor["Password"]=PW;
            $DatosServidor["DataBase"]=DB;
            $Tabla="locales";
            if($idItem==''){
                $sql="SELECT MAX(Orden) as Orden FROM locales";
                $Consulta=$obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
                $DatosLocal=$obCon->FetchAssoc($Consulta);
                $idCategoria=$Datos["idCategoria"];
                $sql="SELECT Icono,ColorIcono FROM catalogo_categorias WHERE id='$idCategoria'";
                $Consulta=$obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
                $DatosCategorias=$obCon->FetchAssoc($Consulta);
                $Datos["Icono"]=$DatosCategorias["Icono"];
                $Datos["ColorIcono"]=$DatosCategorias["ColorIcono"];
                $Datos["Orden"]=$DatosLocal["Orden"]+1;
                $Datos["Created"]=date("Y-m-d H:i:s");
                $Datos["idUser"]=1;
                $Datos["Estado"]=1;
                $sql=$obCon->getSQLInsert($Tabla, $Datos);
                $obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
                $sql="SELECT MAX(ID) as ID FROM locales";
                $Consulta=$obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
                $DatosLocal=$obCon->FetchAssoc($Consulta);
                $idLocal=$DatosLocal["ID"];
                $db="ts_domi_$idLocal";
                $sql="UPDATE locales set db='$db' WHERE ID='$idLocal'";
                $obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
            }else{
                $sql=$obCon->getSQLUpdate($Tabla, $Datos);
                $sql.=" WHERE ID='$idEditar'";
                $obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
                $idLocal=$idEditar;
            }
            $Extension="";
            if(!empty($_FILES['Fondo']['name'])){
                
                $info = new SplFileInfo($_FILES['Fondo']['name']);
                $Extension=($info->getExtension());  
                $Tamano=filesize($_FILES['Fondo']['tmp_name']);
                $DatosConfiguracion=$obCon->DevuelveValores("configuracion_general", "ID", 2000);
                
                $carpeta=$DatosConfiguracion["Valor"];
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                $carpeta=$DatosConfiguracion["Valor"].$idLocal."/";
                if (!file_exists($carpeta)) {
                    mkdir($carpeta, 0777);
                }
                
                opendir($carpeta);
                $idAdjunto=uniqid(true);
                $destino=$carpeta.$idAdjunto.".".$Extension;
                
                
                if($idItem<>''){
                    $sql="SELECT Ruta FROM locales_imagenes WHERE idLocal='$idLocal' LIMIT 1";
                    $Consulta=$obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
                    $DatosValidacion=$obCon->FetchAssoc($Consulta);
                    if (file_exists($DatosValidacion["Ruta"])) {
                        unlink($DatosValidacion["Ruta"]);
                    }
                    $sql="DELETE FROM locales_imagenes WHERE idLocal='$idLocal'";
                    $obCon->QueryExterno($sql, $DatosServidor["IP"], $DatosServidor["Usuario"], $DatosServidor["Password"], $DatosServidor["DataBase"], "");
                }
                move_uploaded_file($_FILES['Fondo']['tmp_name'],$destino);
                $obCon->RegistreFondoLocal($idLocal, $destino, $Tamano, $_FILES['Fondo']['name'], $Extension, 1);
            }
            
            print("OK;Registro Guardado Correctamente;$idEditar");
            
        break;//Fin caso 6  
        
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
        
    }
          
}else{
    print("No se enviaron parametros");
}
?>
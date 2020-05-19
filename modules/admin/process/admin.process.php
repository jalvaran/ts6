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
            $form_identify=$obCon->normalizar($_REQUEST["form_identify"]);
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
            
            $Datos["Indicativo"]=$obCon->normalizar($_REQUEST["Indicativo"]);
            $Datos["Whatsapp"]=$obCon->normalizar($_REQUEST["Whatsapp"]);
            $Datos["idTelegram"]=$obCon->normalizar($_REQUEST["idTelegram"]);
            $Datos["idCiudad"]=$obCon->normalizar($_REQUEST["idCiudad"]);
            $Datos["theme_id"]=$obCon->normalizar($_REQUEST["theme_id"]);
            $Datos["page_initial"]=$obCon->normalizar($_REQUEST["page_initial"]);
            $Datos["header_class"]=$obCon->normalizar($_REQUEST["header_class"]);
            $Datos["slider_class"]=$obCon->normalizar($_REQUEST["slider_class"]);
            $Datos["keywords"]=$obCon->normalizar($_REQUEST["keywords"]);
            $Datos["virtual_shop"]=$obCon->normalizar($_REQUEST["virtual_shop"]);
            $Datos["UrlLocal"]=$obCon->normalizar($_REQUEST["UrlLocal"]);
            $Datos["title_page"]=$obCon->normalizar($_REQUEST["title_page"]);
            $Datos["Alcance"]=$obCon->normalizar($_REQUEST["Alcance"]);
            
            foreach ($Datos as $key => $value) {
                if($value=="" AND $key<>'Orden' AND $key<>'keywords' AND $key<>'UrlLocal'){
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
            $Logo="tmp/".$form_identify."/logo-header.png";
            $FondoLocal="tmp/".$form_identify."/local-foto.png";
            if($idItem==''){
                if(!is_file($FondoLocal)){

                    exit("E1;Debe Adjuntar una Imagen para el Local");
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
            if(is_file($FondoLocal)){
                
                $info = new SplFileInfo($FondoLocal);
                $Extension=($info->getExtension());  
                $Tamano=filesize($FondoLocal);
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
                
                rename($FondoLocal, $destino);
                if(is_file($Logo)){
                    rename($Logo, $carpeta."logo-header.png");
                }
                //unlink("tmp/$form_identify");
                $obCon->RegistreFondoLocal($idLocal, $destino, $Tamano, $FondoLocal, $Extension, 1);
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
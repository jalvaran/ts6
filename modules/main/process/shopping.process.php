<?php


if( !empty($_REQUEST["ActionShopping"]) ){
    include_once("modules/main/class/shopping.class.php");
    include_once("general/class/telegram.class.php");
    include_once("general/class/mail.class.php");

    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    
    $obCon=new Shopping();
    $obMail=new TS_Mail(1);
    switch ($_REQUEST["ActionShopping"]) {
        
        case 1: //Agregar Item a un pedido
            
            $user_id=$obCon->normalizar($_REQUEST["user_id"]);
            $local_id=$obCon->normalizar($_REQUEST["local_id"]);
            $product_id=$obCon->normalizar($_REQUEST["product_id"]);            
            $Observaciones=$obCon->normalizar($_REQUEST["Observaciones"]);
            $Cantidad=$obCon->normalizar($_REQUEST["Cantidad"]);
            
            if($user_id==''){
                exit("E1;No se recibió el id del usuario");
            }
            if($local_id==''){
                exit("E1;No se recibió el id del local");
            }
            if($product_id==''){
                exit("E1;No se recibió el id del producto");
            }
            if(!is_numeric($Cantidad) or $Cantidad<=0 ){
                exit("E1;Debe digitar un valor numerico mayor a cero");
            }
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $local_id);
            $local_db=$DatosLocal["db"];     
            
            $obCon->CrearUsuarioCliente($user_id, "", "", "");//Creo el cliente usuario si no existe            
            $pedido_id=$obCon->CrearPedido($user_id, $local_id, 0, "", 1); //Creo el pedido si no existe y si existe devuelvo el id
            
            //Verifico el valor unitario del producto
           
            $sql="SELECT ID from client_user WHERE Betado=1 AND ID='$user_id'";
            $ConsultaBeto=$obCon->FetchAssoc($obCon->Query($sql));
            if($ConsultaBeto["ID"]<>''){
                exit("<h1>No puedes agregar items</h1>");
            }
            
            $sql="SELECT PrecioVenta FROM productos_servicios WHERE ID='$product_id';";
            $Consulta= $obCon->Query2($sql, HOST, USER, PW, $local_db, ""); 
            $DatosProducto= $obCon->FetchAssoc($Consulta);
            
            $ValorUnitario=$DatosProducto["PrecioVenta"];
            
            //agrego o edito el item al pedido
            $obCon->AgregarItemAPedido($local_db, $pedido_id, $product_id, $Cantidad, $ValorUnitario, $Observaciones);
            
            //Actualizo la cantidad de items y el total en el pedido general
            $sql="UPDATE pedidos SET CantidadItems=CantidadItems+'$Cantidad', Total=Total+$ValorUnitario 
                         WHERE ID='$pedido_id'";
            $obCon->Query($sql);
            //obtengo la cantidad de items y el total del pedido
            $sql="SELECT SUM(CantidadItems) AS CantidadItems,SUM(Total) AS Total FROM pedidos WHERE cliente_id='$user_id' AND Estado=1";
            $DatosPedido=$obCon->FetchAssoc($obCon->Query($sql));
            $Items=$DatosPedido["CantidadItems"];
            $Total=$DatosPedido["Total"];
            $NumberTotal= number_format($DatosPedido["Total"]);
            print("OK;Producto Agregado;$Items;$Total;$NumberTotal");
            
        break;//Fin caso 1
        
        case 2://Obtenga la cantidad de items en pedidos en curso por un usuario
            $user_id=$obCon->normalizar($_REQUEST["user_id"]);
            if($user_id==''){
                exit("E1;No se recibió el id del usuario");
            }
            $sql="SELECT SUM(CantidadItems) AS CantidadItems,SUM(Total) AS Total FROM pedidos WHERE cliente_id='$user_id' AND Estado=1";
            $DatosPedido=$obCon->FetchAssoc($obCon->Query($sql));
            $Items=$DatosPedido["CantidadItems"];
            $Total=$DatosPedido["Total"];
            $NumberTotal= number_format($DatosPedido["Total"]);
            print("OK;$Items;$Total;$NumberTotal");
        break;//fin caso 2    
        
        case 3://Editar un campo de una tabla
            $Tab=$obCon->normalizar($_REQUEST["Tab"]);
            $idLocalEdit=$obCon->normalizar($_REQUEST["idLocalEdit"]);
            $Field=$obCon->normalizar($_REQUEST["Field"]);
            $idEdit=$obCon->normalizar($_REQUEST["idEdit"]);
            $FieldValue=$obCon->normalizar($_REQUEST["FieldValue"]);
            
            if($Tab==''){
                exit("E1;No se recibió la tabla");
            }
            if($idLocalEdit==''){
                exit("E1;No se recibió el local");
            }
            if($Field==''){
                exit("E1;No se recibió el campo");
            }
            if($idEdit==''){
                exit("E1;No se recibió el id");
            }
            
            if($Tab==1){
                
                $dbLocal=$obCon->getDataBaseLocal($idLocalEdit);                
                $sql="UPDATE pedidos_items SET $Field='$FieldValue', Total=Cantidad*ValorUnitario 
                        WHERE ID='$idEdit'";
                $obCon->Query2($sql, HOST, USER, PW, $dbLocal, "");
                if($Field=="Cantidad"){
                    $sql="SELECT pedido_id FROM pedidos_items 
                        WHERE ID='$idEdit'";
                    $DatosPedido=$obCon->FetchAssoc($obCon->Query2($sql, HOST, USER, PW, $dbLocal, ""));
                    $obCon->ActualizarValoresPedido($idLocalEdit, $DatosPedido["pedido_id"]);
                }
                
                
            }
            
            print("OK;Registro Actualizado");
        break;//Fin caso 3    
        
        case 4://Eliminar item
            
            $idLocalEdit=$obCon->normalizar($_REQUEST["idLocalEdit"]);            
            $idItem=$obCon->normalizar($_REQUEST["idItem"]);
            
            if($idLocalEdit==''){
                exit("E1;No se recibió el local");
            }
            
            if($idItem==''){
                exit("E1;No se recibió el id");
            }
            
            
                
            $dbLocal=$obCon->getDataBaseLocal($idLocalEdit);                
            
            $sql="SELECT pedido_id FROM pedidos_items 
                WHERE ID='$idItem'";
            $DatosPedido=$obCon->FetchAssoc($obCon->Query2($sql, HOST, USER, PW, $dbLocal, ""));
            
            $sql="DELETE FROM pedidos_items 
                WHERE ID='$idItem'";
            $obCon->Query2($sql, HOST, USER, PW, $dbLocal, "");
            
            $obCon->ActualizarValoresPedido($idLocalEdit, $DatosPedido["pedido_id"]);
            
            print("OK;Item Eliminado");
        break;//Fin caso 4
        
        case 5://Solicitar pedido
            
            $token = $_POST['token'];
            $action = $_POST['action'];      
            
            $respuestaToken=$obCon->validaTokenGoogle($token, $action,RECAPTCHA_V3_SECRET_KEY);
            if($respuestaToken["success"]<>1 or $respuestaToken["action"]<>$action){
                exit("E1;El token no coincide");
            }
            $idUserClient=$obCon->normalizar($_REQUEST["idUserClient"]);  
            $NombreCliente=$obCon->normalizar($_REQUEST["NombreCliente"]);  
            $DireccionCliente=$obCon->normalizar($_REQUEST["DireccionCliente"]);  
            $Telefono=$obCon->normalizar($_REQUEST["Telefono"]); 
            $ObservacionesPedido=$obCon->normalizar($_REQUEST["ObservacionesPedido"]); 
            
            if($idUserClient==""){
                exit("E1;No se recibió el id del cliente");
            }
            if($NombreCliente==""){
                exit("E1;Debes escribir tu Nombre;NombreCliente");
            }
            if($DireccionCliente==""){
                exit("E1;Debes escribir una dirección para poder enviarte el pedido;DireccionCliente");
            }
            if($Telefono==""){
                exit("E1;Debes escribir un Número Telefónico;Telefono");
            }
            $sql="SELECT ID from client_user WHERE Betado=1 AND ID='$idUserClient'";
            $ConsultaBeto=$obCon->FetchAssoc($obCon->Query($sql));
            if($ConsultaBeto["ID"]<>''){
                exit("<h1>No puedes realizar pedidos</h1>");
            }
            $ValidacionMetodoEnvio=$obCon->DevuelveValores("configuracion_general", "ID", 25);//Determina el metodo de envio
            if(isset($_REQUEST["chRegistrarse"])){
                $chRegistrarse=$obCon->normalizar($_REQUEST["chRegistrarse"]);  
                $Email=strtolower($obCon->normalizar($_REQUEST["Email"]));  
                $Password=$obCon->normalizar($_REQUEST["Password"]);  
                $PasswordConfirm=$obCon->normalizar($_REQUEST["PasswordConfirm"]); 
                //exit("che $chRegistrarse");
                if($chRegistrarse=="true"){
                    if($Email==""){
                        exit("E1;Debes escribir una dirección de correo electronico válida;Email");
                    }
                    if(!filter_var($Email, FILTER_VALIDATE_EMAIL)){
                        exit("E1;El campo Email No contiene un Correo válido;Email");
                    }
                    if($Password==""){
                        exit("E1;Debes escribir una contraseña;Password");
                    }
                    if($PasswordConfirm==""){
                        exit("E1;Debes confirmar la contraseña;PasswordConfirm");
                    }
                    if($Password<>$PasswordConfirm){
                        exit("E1;El password digitado no coincide;PasswordConfirm");
                    }
                    
                    $sql="SELECT ID FROM client_user WHERE Email='$Email'";
                    $DatosConsultaMail=$obCon->FetchAssoc($obCon->Query($sql));
                    if($DatosConsultaMail["ID"]<>''){
                        exit("E1;El Email $Email ya existe;Email");
                        
                    }else{
                        $TokenUserAccess=$obCon->getUniqId("u_");
                        $obCon->ActualiceDatosClienteAcceso($idUserClient, $Email, $Password, $TokenUserAccess);
                        $MensajeActivacion="Te haz registrado en la plataforma Domi!, Verifica tu cuenta aqui: ";
                        $Ruta='www.domibuga.com/domi/modulos/main/procesadores/main.process.php?Accion=9&idTokenUserClient='.$TokenUserAccess;
                        $Link='<a href="'.$Ruta.'" target="_blank">Verificar Cuenta</a>';
                        $MensajeActivacion.=$Link;
                        //if($ValidacionMetodoEnvio["Valor"]==1){                           
                        $obMail->EnviarMailXPHPMailer($Email, "technosoluciones.domi@gmail.com", "PLATAFORMA DOMI", "Activacion de Cuenta", $MensajeActivacion);
                        
                    }
                }
            }
            $obCon->ActualiceDatosCliente($idUserClient, $NombreCliente, $DireccionCliente, $Telefono);
            $obTel=new TS_Telegram($idUser);
            
            $Cliente=" $NombreCliente, $DireccionCliente, $Telefono ";
            
            $sql="SELECT t1.Created,t1.ID,t1.local_id,t2.Email,t2.Nombre,t2.idTelegram,t2.UsaDomicilioDomi FROM pedidos t1 INNER JOIN locales t2 ON t1.local_id=t2.ID
                     WHERE cliente_id='$idUserClient' AND t1.Estado=1";
            $Consulta=$obCon->Query($sql);
            $MailReport[]="";
            $htmlMensaje="";
            $DatosConfig=$obCon->DevuelveValores("configuracion_general", "ID", 2005);//Token api telegram
            $TelegramToken=$DatosConfig["Valor"];
            $Ruta=$obCon->DevuelveValores("configuracion_general", "ID", 2006);//Ruta del pdf para ver el pedido
            
            $htmlMensaje='<div class="table-responsive"><h2><strong>Tienes nuevos Pedidos de la Plataforma DoMi!</strong></h2></div>';
            $htmlMensaje.='<table class="table"><tr><th><strong>LISTA DE PEDIDOS:</strong></th></tr>';
            $DatosLocalAdministrador=$obCon->DevuelveValores("locales", "ID", 1);
            $idDomicilioDomi=$DatosLocalAdministrador["idTelegramCanal"];
            $idTelegranAdmin=$DatosLocalAdministrador["idTelegram"];
            $i=0;
            $Validacion=$obCon->DevuelveValores("configuracion_general", "ID", 2002);//Determina si se envia correo de notificacion
            
            while($DatosConsulta=$obCon->FetchAssoc($Consulta)){
                $Link=$Ruta["Valor"].$DatosConsulta["ID"];
                if($DatosConsulta["Email"]<>''){
                    $MailReport["Email"][$i]=$DatosConsulta["Email"];
                    $MailReport["Asunto"][$i]="PEDIDO DOMI ".$DatosConsulta["ID"];
                    
                    $htmlReport='<div class="table-responsive"><h2><strong>Tienes nuevos Pedidos de la Plataforma DoMi!</strong></h2></div>';
                    $htmlReport.='<table class="table"><tr><th><strong>LISTA DE PEDIDOS:</strong></th></tr>';
                    $htmlReport.='<tr>
                                        <th><strong>ID</strong></th>
                                        <th><strong>Fecha</strong></th>
                                        <th><strong>Local</strong></th>
                                        <th><strong>PDF</strong></th>
                                    </tr>';
                    $htmlReport.='<tr>
                                <th>'.$DatosConsulta["ID"].'</th>
                                <th>'.$DatosConsulta["Created"].'</th>
                                <th>'.$DatosConsulta["Nombre"].'</th>
                                <th><a href="'.$Link.'" target="_blank">VER PDF</th>
                            </tr>';
                    $htmlReport.="</table>";
                    
                    $MailReport["Html"][$i]=$htmlReport;
                }
                
                $htmlMensaje.='<tr>
                                
                                <th><a href="'.$Link.'" target="_blank">VER PDF</a></th>
                            </tr>';
                
                $i=$i+1;
                $Enlace='<a href="'.$Link.'" target="_blank">VER PDF</a>';
                $msg="Tienes un Nuevo pedido en la plataforma Domi, para $Cliente, $Enlace";
                if($DatosConsulta["idTelegram"]<>''){                    
                    $obTel->EnviarMensajeTelegram($DatosConsulta["idTelegram"], $msg,$TelegramToken);                    
                }
                if($DatosConsulta["UsaDomicilioDomi"]==1){
                    $obTel->EnviarMensajeTelegram($idDomicilioDomi, $msg,$TelegramToken);
                }
                
                $obTel->EnviarMensajeTelegram($idTelegranAdmin, $msg,$TelegramToken);
            }
            $htmlMensaje.="</table>";
            $sql="UPDATE pedidos SET Estado=2,Observaciones='$ObservacionesPedido' WHERE cliente_id='$idUserClient' AND Estado=1";
            $obCon->Query($sql);
            
            
            if($Validacion["Valor"]==1){
                
                if($ValidacionMetodoEnvio["Valor"]==1){
                    if(isset($MailReport["Email"])){
                        foreach ($MailReport["Email"] as $key => $value) {
                            //$obMail->EnviarMailXPHPNativo($value, "technosoluciones.domi@gmail.com", "PLATAFORMA DOMI", $MailReport["Asunto"][$key], $MailReport["Html"][$key]);
                        }
                    }
                    
                }else{
                    if(isset($MailReport["Email"])){
                        foreach ($MailReport["Email"] as $key => $value) {
                            //$obMail->EnviarMailXPHPMailer($value, "technosoluciones.domi@gmail.com", "PLATAFORMA DOMI", $MailReport["Asunto"][$key], $MailReport["Html"][$key]);
                        }
                    }
                    
                }
                
                
            }
            print("OK;$htmlMensaje");
        break;//Fin caso 5   
        
        case 6://Descartar pedido
            $idUserClient=$obCon->normalizar($_REQUEST["idUserClient"]);  
            
            if($idUserClient==""){
                exit("E1;No se recibió el id del cliente");
            }
            
            $sql="UPDATE pedidos SET Estado=20 WHERE cliente_id='$idUserClient' AND Estado=1";
            $obCon->Query($sql);
            
            print("OK;Tu Pedido fué descartado");
        break;//Fin caso 6
        
        case 7://Autocomplementar datos cliente
            $idUserClient=$obCon->normalizar($_REQUEST["idUserClient"]);  
            
            if($idUserClient==""){
                exit("E1;No se recibió el id del cliente");
            }
            
           
            $sql="SELECT Nombre,Telefono,Direccion FROM client_user WHERE ID='$idUserClient'";
            $Consulta=$obCon->Query($sql);
            $DatosCliente=$obCon->FetchAssoc($Consulta);
            if($DatosCliente["Nombre"]==""){
                exit("E1");
            }
            print("OK;".$DatosCliente["Nombre"].";".$DatosCliente["Telefono"].";".$DatosCliente["Direccion"]);
        break;//Fin caso 7
        
        case 8://verifica inicio de sesion
            
            $idUserClient=$obCon->normalizar($_REQUEST["idUserClient"]);  
            
            if($idUserClient==""){
                exit("E1;No se recibió el id del cliente");
            }
            
            if(isset($_SESSION["user_id"])){
                return("OK;".$_SESSION["user_id"]);
            }else{
                return("E1;No se ha iniciado sesion");
            }
           
           
        break;//Fin caso 8
        
        case 9://Valida la creacion de una cuenta
            
            $idToken=$obCon->normalizar($_REQUEST["idTokenUserClient"]); 
            $sql="SELECT ID FROM client_user WHERE user_token='$idToken' AND Verificado='0' AND Habilitado='0'";
            $DatosValidacion=$obCon->FetchAssoc($obCon->Query($sql));
            if($DatosValidacion["ID"]==''){
                exit("<h2>La cuenta ya fué activada o no existe</h2>");
            }
            
            $sql="UPDATE client_user SET Verificado=1, Habilitado=1 WHERE user_token='$idToken' LIMIT 1";
            $obCon->Query($sql);
            exit("<h2>La cuenta ha sido activada por favor entra a Domi e inicia sesion</h2>");
        break;//Fin caso 9
        
        case 10://Iniciar sesion de un usuario comprador
            $token = $_POST['token'];
            $action = $_POST['action'];  
            $respuestaToken=$obCon->validaTokenGoogle($token, $action,RECAPTCHA_V3_SECRET_KEY);
            if($respuestaToken["success"]<>1 or $respuestaToken["action"]<>$action){
                exit("E1;El token googel no coincide");
            }
            $emailLogin= strtolower($obCon->normalizar($_REQUEST["emailLogin"]));
            $passLogin=$obCon->normalizar($_REQUEST["passLogin"]);
            
            $sql="SELECT ID FROM client_user WHERE Email='$emailLogin' AND Password='$passLogin'";
            $DatosLogin=$obCon->FetchAssoc($obCon->Query($sql));
            if($DatosLogin["ID"]==''){
                exit("E1;Usuario o Password incorrectos");
            }else{
                $_SESSION["user_id"]=$DatosLogin["ID"];
                exit("OK;Inicio de sesion correcto;".$DatosLogin["ID"]);
            }
        break;//Fin caso 10    
        
    }
          
}else{
    print("No se enviaron parametros");
}
?>
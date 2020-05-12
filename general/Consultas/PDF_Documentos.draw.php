<?php 
if(isset($_REQUEST["idDocumento"])){
    $myPage="PDF_Documentos.draw.php";
    include_once("../../modelo/php_conexion.php");
    include_once("../../modelo/PrintPos.php");
    include_once("../clases/ClasesPDFDocumentos.class.php");
    session_start();
    $idUser=$_SESSION["idUser"];
    $obCon = new conexion($idUser);
    $obPrint=new PrintPos($idUser);
    $obDoc = new Documento($db);
    $idDocumento=$obCon->normalizar($_REQUEST["idDocumento"]);
    
    
    switch ($idDocumento){
        case 1://Genera el PDF de una cotizacion
            
            $idCotizacion=$obCon->normalizar($_REQUEST["ID"]);
            
            $obDoc->PDF_Cotizacion($idCotizacion, "");
    
            $DatosImpresora=$obCon->DevuelveValores("config_puertos", "ID", 1);
            if($DatosImpresora["Habilitado"]=="SI"){
                $obPrint->ImprimeCotizacionPOS($idCotizacion,$DatosImpresora["Puerto"],1);
            }
        break;
        case 2://Genera el PDF de una Factura de venta
            
            $idFactura=$obCon->normalizar($_REQUEST["ID"]);
            $TipoFactura="ORIGINAL";
            $Guardar=0;
            if(isset($_REQUEST["TipoFactura"])){
                $TipoFactura=$obCon->normalizar($_REQUEST["TipoFactura"]);
            }
            if(isset($_REQUEST["Guardar"])){
                $Guardar=$obCon->normalizar($_REQUEST["Guardar"]);
            }
            
            $obDoc->PDF_Factura($idFactura,$TipoFactura, "",$Guardar);

            $DatosImpresora=$obCon->DevuelveValores("config_puertos", "ID", 1);
            if($DatosImpresora["Habilitado"]=="SI"){
                $obPrint->ImprimeFacturaPOS($idFactura,$DatosImpresora["Puerto"],1,0);
            }
        break;
        case 4: //Comprobante de ingreso
            $idIngreso=$obCon->normalizar($_REQUEST["idIngreso"]);
            $obDoc->PDF_CompIngreso($idIngreso);
            $obPrint->ComprobanteIngresoPOS($idIngreso, $DatosImpresora["Puerto"], 1);
            break;
        case 5: //Orden de Compra
            $idOC=$obCon->normalizar($_REQUEST["ID"]);
            $obDoc->OrdenCompraPDF($idOC);
            
            break;
        
        case 23: //Factura de Compra
            $idOC=$obCon->normalizar($_REQUEST["ID"]);
            $obDoc->PDF_FacturaCompra($idOC);
            
            break;
        case 25: //Comprobante de altas y bajas
            $idComprobante=$obCon->normalizar($_REQUEST["idComprobante"]);
            $obDoc->PDF_CompBajasAltas($idComprobante);    
            //print("Entra");
            $obPrint->ImprimeComprobanteBajaAlta2($idComprobante, "", 1, "");
            break;
        case 30: //Cuenta de cobro para un tercero
            $idCuenta=$obCon->normalizar($_REQUEST["idCuenta"]);
            $obDoc->CuentaCobroTercero($idCuenta,"");            
            break;
        case 31: //PDF de una nota de devolucion
            $idNota=$obCon->normalizar($_REQUEST["idNotaDevolucion"]);
            $obDoc->PDF_NotaDevolucion($idNota,"");            
            break;
        case 32: //PDF de un documento contable
            $idDocumento=$obCon->normalizar($_REQUEST["idDocumentoContable"]);
            $obDoc->PDF_DocumentoContable($idDocumento,"");            
            break;
        case 33: //PDF de un documento equivalente a factura para nomina
            $idDocumento=$obCon->normalizar($_REQUEST["idDocEqui"]);
            $obDoc->NominaPDFDocumentoEquivalente($idDocumento,"");            
            break;
        case 34: //PDF de un certificado de retenciones
            $FechaInicial=$obCon->normalizar($_REQUEST["TxtFechaInicial"]);
            $TxtFechaFinal=$obCon->normalizar($_REQUEST["TxtFechaFinal"]);
            $CmbCentroCosto=$obCon->normalizar($_REQUEST["CmbCentroCosto"]);
            $CmbEmpresa=$obCon->normalizar($_REQUEST["CmbEmpresa"]);
            $CmbTercero=$obCon->normalizar($_REQUEST["CmbTercero"]);
            $CmbCiudadRetencion=$obCon->normalizar($_REQUEST["CmbCiudadRetencion"]);
            $CmbCiudadPago="";
            $CmbCiudadPago=$obCon->normalizar($_REQUEST["CmbCiudadPago"]);
            $obDoc->PDF_Certificado_Retenciones($FechaInicial, $TxtFechaFinal, $CmbCentroCosto, $CmbEmpresa, $CmbTercero, $CmbCiudadRetencion, $CmbCiudadPago, "");         
            break;//Fi caso 34
        
        case 35: //PDF de un comprobante de prestamo
            $idPrestamo=$obCon->normalizar($_REQUEST["ID"]);
            $obDoc->ComprobantePrestamoPDF($idPrestamo,"");            
            break;//Fin caso 35
        
        case 36: //PDF de un informe de cierre general
            $idCierre=$obCon->normalizar($_REQUEST["ID"]);
            $obDoc->InformeCierreRestaurante($idCierre,"");            
            break;//Fin caso 35
        case 37: //PDF de un acuerdo de pago
            $idAcuerdo=$obCon->normalizar($_REQUEST["idAcuerdo"]);
            $EstadoGeneral=$obCon->normalizar($_REQUEST["EstadoGeneral"]);
            include_once '../../modulos/comercial/clases/AcuerdoPago.class.php';
            $obAcuerdo=new AcuerdoPago($idUser);      
            $obAcuerdo->ActualiceEstadosProyeccionPagos($idAcuerdo);            
            $obDoc->AcuerdoPagoPDF($idAcuerdo,$EstadoGeneral,"");            
        break;//Fin caso 37
        case 38://Envia una factura y un acuerdo de pago si existe por email
            include_once '../clases/mail.class.php';
            $obMail= new TS_Mail($idUser);
            $idFactura=$obCon->normalizar($_REQUEST["idFactura"]);
            $DatosFactura=$obCon->DevuelveValores("facturas", "idFacturas", $idFactura);
            $DatosCliente=$obCon->DevuelveValores("clientes", "idClientes", $DatosFactura["Clientes_idClientes"]);
            if(!filter_var($DatosCliente["Email"], FILTER_VALIDATE_EMAIL) or strtolower($DatosCliente["Email"])=="no@no.com"){
                exit("<h3>El cliente no tiene un mail válido</h3>");
            }
            $DatosAcuerdo=$obCon->DevuelveValores("acuerdo_pago", "idFactura", $idFactura);
            $TipoFactura="ORIGINAL";                        
            $obDoc->PDF_Factura($idFactura,$TipoFactura, "",1);
            $DatosConfiguracion=$obCon->DevuelveValores("configuracion_general", "ID", 25);
            $mensajeHTML="<h3>Cordial Saludo <strong>".$DatosCliente["RazonSocial"]."</strong></h3><br><br><br>";
            $mensajeHTML.="En adjunto encontrarás tu Factura de Venta Numero: ".$DatosFactura["NumeroFactura"];
            $mensajeHTML.="<br><br><br>Mil gracias por tu compra y que tengas un feliz dia!";
            $CodigoFactura=$DatosFactura["Prefijo"]." - ".$DatosFactura["NumeroFactura"];
            $Adjuntos[0]="../../tmp/Factura_$CodigoFactura".".pdf";
            if($DatosAcuerdo["ID"]<>''){
                $idAcuerdo=$DatosAcuerdo["idAcuerdoPago"];
                include_once '../../modulos/comercial/clases/AcuerdoPago.class.php';
                $obAcuerdo=new AcuerdoPago($idUser);      
                
                $EstadoAcuerdo=$obAcuerdo->ObtengaEstadoGeneralAcuerdo($idAcuerdo);
                $EstadoGeneral="AL DIA";
                if($EstadoAcuerdo==4){
                    $EstadoGeneral="EN MORA";
                }
                $obDoc->AcuerdoPagoPDF($idAcuerdo, $EstadoAcuerdo, 1,"../../tmp/");
                $Adjuntos[1]="../../tmp/Acuerdo_Pago_$idAcuerdo".".pdf";
            }
            if($DatosConfiguracion["Valor"]==1){
                $obMail->EnviarMailXPHPNativo(($DatosCliente["Email"]),"technosoluciones_fe@gmail.com", "TS5", "Factura TS5 ".$DatosFactura["NumeroFactura"], $mensajeHTML, $Adjuntos);
                
            }else{
                $obMail->EnviarMailXPHPMailer(($DatosCliente["Email"]),"technosoluciones_fe@gmail.com", "TS5", "Factura TS5 ".$DatosFactura["NumeroFactura"], $mensajeHTML, $Adjuntos);
            }
            /*  Activar si desea limpiar el temporal
            foreach ($Adjuntos as $value){
                unlink($value);
            }
             * 
             */
            
            print("OK;Factura Enviada");
        break;//Fin caso 38
        
        case 39://Envia un acuerdo de pago por email
            include_once '../clases/mail.class.php';
            $obMail= new TS_Mail($idUser);
            $idAcuerdoPago=$obCon->normalizar($_REQUEST["idAcuerdoPago"]);
            $DatosAcuerdo=$obCon->DevuelveValores("acuerdo_pago", "idAcuerdoPago", $idAcuerdoPago);
            $DatosCliente=$obCon->DevuelveValores("clientes", "Num_Identificacion", $DatosAcuerdo["Tercero"]);
            if(!filter_var($DatosCliente["Email"], FILTER_VALIDATE_EMAIL) or strtolower($DatosCliente["Email"])=="no@no.com"){
                exit("<h3>El cliente no tiene un mail válido</h3>");
            }
            
            
            $DatosConfiguracion=$obCon->DevuelveValores("configuracion_general", "ID", 25);
            $mensajeHTML="<h3>Cordial Saludo <strong>".$DatosCliente["RazonSocial"]."</strong></h3><br><br><br>";
            $mensajeHTML.="En adjunto encontrarás tu Acuerdo de pago";
            $mensajeHTML.="<br><br><br>Mil gracias por tu compra y que tengas un feliz dia!";
            
            if($DatosAcuerdo["ID"]<>''){
                $idAcuerdo=$DatosAcuerdo["idAcuerdoPago"];
                include_once '../../modulos/comercial/clases/AcuerdoPago.class.php';
                $obAcuerdo=new AcuerdoPago($idUser);      
                
                $EstadoAcuerdo=$obAcuerdo->ObtengaEstadoGeneralAcuerdo($idAcuerdo);
                $EstadoGeneral="AL DIA";
                if($EstadoAcuerdo==4){
                    $EstadoGeneral="EN MORA";
                }
                $obDoc->AcuerdoPagoPDF($idAcuerdo, $EstadoAcuerdo, 1,"../../tmp/");
                $Adjuntos[0]="../../tmp/Acuerdo_Pago_$idAcuerdo".".pdf";
            }
            if($DatosConfiguracion["Valor"]==1){
                $obMail->EnviarMailXPHPNativo(($DatosCliente["Email"]),"technosoluciones_fe@gmail.com", "TS5", "Acuerdo de Pago TS5 ".$DatosAcuerdo["ID"], $mensajeHTML, $Adjuntos);
                
            }else{
                $obMail->EnviarMailXPHPMailer(($DatosCliente["Email"]),"technosoluciones_fe@gmail.com", "TS5", "Acuerdo de Pago TS5 ".$DatosAcuerdo["ID"], $mensajeHTML, $Adjuntos);
            }
            /*  Activar si desea limpiar el temporal
            foreach ($Adjuntos as $value){
                unlink($value);
            }
             * 
             */
            
            print("OK;Acuerdo de Pago Enviado");
        break;//Fin caso 39
    }
}else{
    print("No se recibió parametro de documento");
}

?>
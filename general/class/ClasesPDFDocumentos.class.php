<?php
/* 
 * Clase donde se realizaran la generacion de informes.
 * Julian Alvaran
 * Techno Soluciones SAS
 */
//include_once '../../modelo/php_tablas.php';
class Documento{
    /**
     * Constructor 
     * @param type $db
     */
    function __construct($db){
        $this->DataBase=$db;
        $this->obCon=new conexion(1);
        
    }
    
    /**
     * Inicia la creacion de un pdf
     * @param type $TituloFormato
     * @param type $FontSize
     * @param type $VectorPDF
     * @param type $Margenes
     */
    public function PDF_Ini($TituloFormato,$FontSize,$VectorPDF,$Margenes=1,$Patch="") {
        
        //require_once('../../librerias/tcpdf/examples/config/tcpdf_config_alt.php');
        $tcpdf_include_dirs = array(realpath($Patch.'librerias/tcpdf/tcpdf.php'), '/usr/share/php/tcpdf/tcpdf.php', '/usr/share/tcpdf/tcpdf.php', '/usr/share/php-tcpdf/tcpdf.php', '/var/www/tcpdf/tcpdf.php', '/var/www/html/tcpdf/tcpdf.php', '/usr/local/apache2/htdocs/tcpdf/tcpdf.php');
        foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
                if (@file_exists($tcpdf_include_path)) {
                        require_once($tcpdf_include_path);
                        break;
                }
        }
        // create new PDF document
        $this->PDF = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'ISO 8859-1', false);
        // set document information
        $this->PDF->SetCreator(PDF_CREATOR);
        $this->PDF->SetAuthor('Techno Soluciones');
        $this->PDF->SetTitle($TituloFormato);
        $this->PDF->SetSubject($TituloFormato);
        $this->PDF->SetKeywords('Techno Soluciones, PDF, '.$TituloFormato.' , CCTV, Alarmas, Computadores, Software');
        // set default header data
        //$pdf->SetHeaderData(PDF_HEADER_LOGO, 60, PDF_HEADER_TITLE.'', "");
        // set header and footer fonts
        $this->PDF->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $this->PDF->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        // set default monospaced font
        $this->PDF->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        if($Margenes==1){
            $this->PDF->SetMargins(10, 10, PDF_MARGIN_RIGHT);
            $this->PDF->SetHeaderMargin(PDF_MARGIN_HEADER);
            $this->PDF->SetFooterMargin(10);
        }
        
        // set auto page breaks
        $this->PDF->SetAutoPageBreak(TRUE, 10);
        // set image scale factor
        $this->PDF->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
                require_once(dirname(__FILE__).'/lang/spa.php');
                $this->PDF->setLanguageArray($l);
        }
        
        // ---------------------------------------------------------
        // set font
        //$pdf->SetFont('helvetica', 'B', 6);
        // add a page
        $this->PDF->AddPage();
        $this->PDF->SetFont('helvetica', '', $FontSize);
        
    }
    /**
     * Encabezado del PDF
     * @param type $Fecha
     * @param type $idEmpresa
     * @param type $idFormatoCalidad
     * @param type $VectorEncabezado
     * @param type $NumeracionDocumento
     */
    public function PDF_Encabezado($Fecha,$idEmpresa,$idFormatoCalidad,$VectorEncabezado,$NumeracionDocumento="",$DatosLocal) {
        
        $DatosFormatoCalidad=$this->obCon->DevuelveValores("formatos_calidad", "ID", $idFormatoCalidad);
        $dataCity=$this->obCon->DevuelveValores("ciudades", "ID", $DatosLocal["idCiudad"]);
        $DatosLocal["idCiudad"]=$dataCity["Nombre"];
        //$DatosEmpresa=$this->obCon->DevuelveValores("info_general_plataforma", "ID", 1);
        $RutaLogo="images/domismall.png";
///////////////////////////////////////////////////////
//////////////encabezado//////////////////
////////////////////////////////////////////////////////
//////
//////
$tbl = <<<EOD
<table cellspacing="0" cellpadding="1" border="1">
    <tr border="1">
        <td rowspan="3" border="1" style="text-align: center;"><img src="$RutaLogo" style="width:110px;height:60px;"></td>
        
        <td rowspan="3" width="290px" style="text-align: center; vertical-align: center;"><h2><br>$DatosFormatoCalidad[Nombre]</h2></td>
        <td width="70px" style="text-align: center;">Versión<br></td>
        <td width="130px"> $DatosFormatoCalidad[Version]</td>
    </tr>
    <tr>
    	
    	<td style="text-align: center;" >Código<br></td>
        <td> $DatosFormatoCalidad[Codigo]</td>
        
    </tr>
    <tr>
       <td style="text-align: center;" >Fecha<br></td>
       <td style="font-size:6px;"> $DatosFormatoCalidad[Fecha]</td> 
    </tr>
</table>
EOD;
$this->PDF->writeHTML($tbl, true, false, false, false, '');
$this->PDF->SetFillColor(255, 255, 255);
$txt=$DatosLocal["Nombre"];
$this->PDF->MultiCell(62, 5, $txt, 0, 'L', 1, 0, '', '', true,0, true, true, 10, 'M');
$txt=$DatosLocal["Direccion"]."<br>".$DatosLocal["Telefono"]."<br>".$DatosLocal["idCiudad"];
$this->PDF->MultiCell(62, 5, $txt, 0, 'C', 1, 0, '', '', true,0, true, true, 10, 'M');
$Documento="<strong>$NumeracionDocumento</strong><br><h5>Impreso por TS5, Techno Soluciones SAS <BR>NIT 900.833.180 3177740609</h5><br>";
$this->PDF->MultiCell(62, 5, $Documento, 0, 'R', 1, 0, '', '', true,0, true ,true, 10, 'M');
$this->PDF->writeHTML("<br>", true, false, false, false, '');
//Close and output PDF document
    }
//Crear el documento PDF
    public function PDF_Write($html) {
        $this->PDF->writeHTML($html, true, false, false, false, '');
    } 
//Agregar pagina en PDF
    public function PDF_Add() {
        $this->PDF->AddPage();
    }     
//Crear el documento PDF
    public function PDF_Output($NombreArchivo) {
        $this->PDF->Output("$NombreArchivo".".pdf", 'I');
    } 
    
    public function PedidoDomiPDF($idPedido) {
        
        $obCon=new conexion(1);
        $idFormato=1;
        $DatosFormato=$obCon->DevuelveValores("formatos_calidad", "ID", $idFormato);
        $Documento=$DatosFormato["Nombre"]." $idPedido";
        $this->PDF_Ini($Documento, 8, "");
        $DatosPedido=$this->obCon->DevuelveValores("pedidos", "ID", $idPedido);
        $DatosCliente=$obCon->DevuelveValores("client_user", "ID", $DatosPedido["cliente_id"]);
        $DatosLocal=$obCon->DevuelveValores("locales", "ID", $DatosPedido["local_id"]);
        $this->PDF_Encabezado($DatosPedido["Created"],1, $idFormato, "",$Documento,$DatosLocal);
        $html=$this->EncabezadoPedido($DatosCliente,$DatosPedido);
        $this->PDF_Write("<br>".$html);
        
        $html=$this->ItemsPedido($idPedido,$DatosLocal["db"]);
        $this->PDF_Write("<br>".$html);
        $this->PDF_Output("DoMi_$idPedido");
         
    }
    
    public function ItemsPedido($idPedido,$db) {
        $obCon=new conexion(1);
        $sql="SELECT t2.Referencia,t1.Cantidad,t1.ValorUnitario,t1.Total,t1.Observaciones, t2.Nombre,t2.DescripcionCorta 
                 FROM pedidos_items t1 INNER JOIN productos_servicios t2 ON t1.product_id=t2.ID 
                 WHERE pedido_id='$idPedido'";
        $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $db, "");
        $tbl='<table cellspacing="0" cellpadding="2" border="0">';
            
            $tbl.='<tr>
                    
                        <th><strong>Referencia</strong></th>
                        <th><strong>Nombre</strong></th>
                        <th><strong>Observaciones</strong></th>
                        <th><strong>Valor Unitario</strong></th>
                        <th><strong>Cantidad</strong></th>
                        <th><strong>Total</strong></th>
                        
                    
                    </tr>';
            $z=0;
            $Total=0;
            while($DatosItems=$obCon->FetchAssoc($Consulta)){
                $Total=$Total+$DatosItems["Total"];
                if($z==1){
                    $z=0;
                    $Color="white";
                }else{
                    $z=1;
                    $Color="#e1fffc";
                }
                $tbl.='<tr>
                    
                        <td style="background-color:'.$Color.'">'.$DatosItems["Referencia"].'</td>
                        <td style="background-color:'.$Color.'">'.($DatosItems["Nombre"]).'</td>
                        <td style="text-align:left;background-color:'.$Color.'">'. ($DatosItems["Observaciones"]).'</td>
                    
                        <td style="text-align:right;background-color:'.$Color.'">'.number_format($DatosItems["ValorUnitario"]).'</td>
                        <td style="text-align:right;background-color:'.$Color.'">'.$DatosItems["Cantidad"].'</td>
                        <td style="text-align:right;background-color:'.$Color.'">'.number_format($DatosItems["Total"]).'</td>
                        
                    </tr>';
            }
            
            $tbl.='<tr>
                    
                        <th colspan="5" style="text-align:right"><strong>TOTAL:</strong></th>                        
                        <th style="text-align:right"><strong>'.number_format($Total).'</strong></th>
                    
                    </tr>';
            
        $tbl.="</table>";
        return($tbl);
        
    }
    
    
    public function EncabezadoPedido($DatosCliente,$DatosPedido) {
        $obCon=new conexion(1);
        
        $tbl = '
        <table cellspacing="0" cellpadding="2" border="1">
            <tr>
                <td><strong>Fecha y Hora:</strong></td>
                <td colspan="3">'.utf8_encode($DatosPedido["Created"]).'</td>

            </tr>
            <tr>
                <td><strong>Cliente:</strong></td>
                <td colspan="3">'.utf8_encode($DatosCliente["Nombre"]).'</td>

            </tr>
            
            <tr>
                <td ><strong>Dirección:</strong></td>
                
                <td colspan="3">'.utf8_encode($DatosCliente["Direccion"]).'</td>
            </tr>
            <tr>
                <td><strong>Teléfono:</strong></td>
                
                <td colspan="3">'.utf8_encode($DatosCliente["Telefono"]).'</td>
            </tr>
            <tr>
                <td><strong>Observaciones:</strong></td>
                
                <td colspan="3">'.utf8_encode($DatosPedido["Observaciones"]).'</td>
            </tr>
            
        </table>

        ';

        return($tbl);
    
    }
    
   
   //Fin Clases
}
    
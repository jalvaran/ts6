<?php
/**
 * Pagina principal del ts6
 * 2020-05-11, Julian Alvaran Techno Soluciones SAS
 *
 */

include_once("constructores/paginas_constructor.php");
$obCon = new conexion(""); //Conexion a la base de datos
$client_id=$obCon->normalizar($_SESSION["client_id"]);
if($client_id==''){
    $client_id="1";
}
$css =  new PageConstruct($client_id); //objeto con las funciones del html

$css->PageInit();
    
$css->PageFin();

$css->Cbody();
$css->Chtml();

?>
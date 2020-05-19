<?php

if(!empty($_REQUEST["action"])){// se verifica si el indice accion es diferente a vacio 
    
    include_once("modules/admin/constructors/constructor.class.php");
    include_once("modelo/php_conexion.php");
    $obCon = new conexion(""); //Conexion a la base de datos
    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION["idLocal"])){
        include_once "pages/403.php";
        exit();
    }
    $local_id=$obCon->normalizar($_SESSION["idLocal"]);
   
    switch($_REQUEST["action"]){
       
        case 1://Dibuja el dashboard del admin
            $css =  new AdminConstruct($local_id,1000);    
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            $html=$css->get_dashboard_init();
            
                //Contenido que se verá
            
            $html.=$css->get_dashboard_end(); 
            
            $html.='</body>';
            $html.='</html>';
            print($html);
            
        break;//Fin caso 1
        
        case 2://Dibuje el contenido del administrador de locales
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $submenu_id=$obCon->normalizar($_REQUEST["submenu_id"]); 
            if($submenu_id==1 and $local_id<>1){                
                include_once "pages/partials/403.php";
                exit();
            }
            $css =  new AdminConstruct($local_id,0,$path);
            
            $Limit=20;
                       
            $DatosLocal=$css->dataClient; 
            if(isset($_REQUEST["page"])){
                $Page=$obCon->normalizar($_REQUEST["page"]);
            }else{
                $Page=1;
            }
            if(isset($_REQUEST["Busqueda"])){
                $Busqueda=$obCon->normalizar($_REQUEST["Busqueda"]);
            }else{
                $Busqueda="";
            }
            
            if($Page==''){
                $Page=1;
                
            }
            $Condicion=" WHERE ID>0 ";
            
            if($Busqueda<>''){
                $Condicion.=" AND ( ID='$Busqueda' or Nombre like '%$Busqueda%' or Telefono like '%$Busqueda%'  )";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(t1.ID) as Items,SUM(Tarifa) as TotalTarifa  
                   FROM locales t1 $Condicion;";
            
            $Consulta2=$obCon->QueryExterno($sql, HOST, USER, PW, DB, "");
            $totales = $obCon->FetchAssoc($Consulta2);
            $ResultadosTotales = $totales['Items'];
            $TotalTarifa = $totales['TotalTarifa'];
            $html="<div class='panel'>";
            $html.='<div class="panel-head">
                        <h5 class="panel-title">Lista de Locales</h5> 
                        <div class="text-right"><strong>EJECUTAR MIGRACIONES</strong> <button id="btnMigrates" class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored button-error m-1" data-upgraded=",MaterialButton"><i class="fa fa-cogs"></i></button></div>
                    </div>';
            $html.='<div class="panel-body">';
            
            //if($ResultadosTotales>$Limit){
                $TotalPaginas= ceil($ResultadosTotales/$Limit);
                $disabled="disabled";
                if($Page>1){
                    $disabled="";                   
                }
                $Pagego=$Page-1;
                $htmlNavMinus='<button '.$disabled.' id="btnPageDown" data-route_view="viewsAdmin" class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored button-secondary m-1 ts_paginator" data-page="'.$Pagego.'" data-submenu_name="Administrar locales" data-submenu_id="1" data-folder="admin" data-action_view="2" ><li class="far fa-arrow-alt-circle-left" ></li></button>';
                $disabled="disabled";
                if($ResultadosTotales>($PuntoInicio+$Limit)){
                    
                    $disabled="";  
                }
                $Pagego=$Page+1;
                $htmlNavMore='<button '.$disabled.' id="btnPageUp" data-route_view="viewsAdmin" class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored button-secondary m-1 ts_paginator" data-page="'.$Pagego.'" data-submenu_name="Administrar locales" data-submenu_id="1" data-folder="admin" data-action_view="2" ><li class="far fa-arrow-alt-circle-right" ></li></button>';
                $htmlNavMore.='<br><strong class="text-muted">Página '.$Page.' de '.$TotalPaginas.'</strong>';
                
            //}
            
            $sql="SELECT ID, Nombre,Direccion,Telefono,Email,Password, Estado FROM locales $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, DB, "");
            $i=0;
            $Filas[]="";
            while($DatosClasificacion=$obCon->FetchAssoc($Consulta)){
                $Filas[$i]=$DatosClasificacion;
                $i=$i+1;
            }
            $z=0;
            $data_table='data-item_id="" data-form_id="1"';
            $Columnas[$z++]="<strong>Editar</strong>";
            $Columnas[$z++]="<strong>ID</strong>";
            $Columnas[$z++]="<strong>Nombre</strong>";
            $Columnas[$z++]="<strong>Direccion</strong>";
            $Columnas[$z++]="<strong>Telefono</strong>";
            $Columnas[$z++]="<strong>Email</strong>";
            $Columnas[$z++]="<strong>Password</strong>";
            $Columnas[$z++]="<strong>Estado</strong>";
            $Acciones["ID"]["js"]='data-item_id="@value" data-form_id="1"';
            $Acciones["ID"]["icon"]="fa fa-edit ts_form_table";
            $Acciones["ID"]["style"]="style=font-size:20px;color:blue;cursor:pointer";
            $htmlHeaderTable='<div class="row"><div class="col-md-3"><span class="fa fa-plus-circle ts_form_table" style="font-size:40px;color:green;cursor:pointer" '.$data_table.'></span> <strong>AGREGAR</strong></div>' ;
            $htmlHeaderTable.='<div class="col-md-3 text-center">
                                    <div class="panel panel-default">
                                        <div class="widget-5">
                                            <div class="tbl-cell icon bg-primary"><i class="fa fa-building"></i></div>
                                            <div class="tbl-cell">
                                                <div class="content">
                                                    <h4>'.number_format($ResultadosTotales).'</h4>
                                                    <h4>Locales</h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';  
            $htmlHeaderTable.='<div class="col-md-3 text-center">
                                    <div class="panel panel-default">
                                        <div class="widget-5">
                                            <div class="tbl-cell icon bg-success"><i class="icon-wallet"></i></div>
                                            <div class="tbl-cell">
                                                <div class="content">
                                                    <h4>$'.number_format($TotalTarifa).'</h4>
                                                    <h4>Total Tarifa</h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>';  
            $htmlHeaderTable.='<div class="col-md-3 text-right">'.$htmlNavMinus.' '.$htmlNavMore.'</div></div>';
            $htmlTabla=$css->getHtmlTable($htmlHeaderTable, $Columnas, $Filas,$Acciones);
            $html.=$htmlTabla;
            $html.="</div>";
            $html.="</div>";
            print($html);
                        
            
        break;//Fin caso 2 
        
        case 3://Dibuje el formulario para editar o agregar un local
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $item_id=$obCon->normalizar($_REQUEST["item_id"]);
            $css =  new AdminConstruct($local_id,1000);    
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            print($css->get_form_locals($item_id,"viewsAdmin"));
            
        break;//Fin caso 3
    
        
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>
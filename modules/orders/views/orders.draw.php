<?php

if(!empty($_REQUEST["action"])){// se verifica si el indice accion es diferente a vacio 
    
    include_once("modules/orders/constructors/orders.construct.php");
    include_once("modelo/php_conexion.php");
    $obCon = new conexion(""); //Conexion a la base de datos
    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION["idLocal"])){
        include_once "pages/403.php";
        exit();
    }
    $local_id=$obCon->normalizar($_SESSION["idLocal"]);
   
    switch($_REQUEST["action"]){
       
        case 1://Dibuja el listado de las ordenes de pedido
            
            $submenu_id=$obCon->normalizar($_REQUEST["submenu_id"]);
            $dataSubmenu=$obCon->DevuelveValores("ts6_modules_menu", "id", $submenu_id);
            
            $css =  new InventoryConstruct($local_id,1000);    
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            
            
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $submenu_id=$obCon->normalizar($_REQUEST["submenu_id"]); 
            if(isset($_REQUEST["status_filter"])){
                $FiltroEstado=$obCon->normalizar($_REQUEST["status_filter"]);
            }else{
                $FiltroEstado="";
            }
                
            
            
            $css =  new InventoryConstruct($local_id,0,$path);
            
            $Limit=5;
            $idLocal=$obCon->normalizar($_SESSION["idLocal"]);            
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
            $Condicion=" WHERE local_id='$idLocal' ";
            
            if($Busqueda<>''){
                $Condicion.=" AND (t1.ID='$Busqueda' or t2.Nombre like '%$Busqueda%' or t2.Telefono like '$Busqueda%')";
            }
            if($FiltroEstado<>''){
                $Condicion.=" AND t1.Estado='$FiltroEstado'";
            }
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(t1.ID) as Items,SUM(Total) as TotalPedidos  
                   FROM pedidos t1 INNER JOIN client_user t2 ON t2.ID=t1.cliente_id
                   $Condicion;";
            
            $Consulta2=$obCon->Query($sql);
            $totales = $obCon->FetchAssoc($Consulta2);
            $ResultadosTotales = $totales['Items'];
            $TotalPedidos= $totales['TotalPedidos'];
            $TotalPaginas= ceil($ResultadosTotales/$Limit);
            
            $html="<div class='panel'>";
            $html.='<div class="panel-head">
                        <h5 class="panel-title">Listado de pedidos</h5> 
                        
                        <div class="text-right">';
            
                $html.='<select id="filter_status_order" class="form-control" style="width:200px;align:right" data-page="1" data-scripts_menu="" data-route_view="'.$dataSubmenu["route_view"].'" data-submenu_id="'.$dataSubmenu["id"].'" data-folder="'.$dataSubmenu["folder"].'" data-action_view="'.$dataSubmenu["action_view"].'"  >';
            
                $html.='<option value="">Todos</option>';
                
                $sql="SELECT * FROM pedidos_estados";
                $query=$obCon->Query($sql);
                while ($dataTypeOrder=$obCon->FetchAssoc($query)){
                    $sel="";
                    if($FiltroEstado==$dataTypeOrder["ID"]){
                        $sel="selected";
                    }
                    $html.='<option '.$sel.' value="'.$dataTypeOrder["ID"].'">'.$dataTypeOrder["EstadoPedido"].'</option>';
                }
            
            $html.='</select>';
            $html.='</div>
                    </div>';
            $html.='<div class="panel-body">';
            
            $disabled="disabled";
            if($Page>1){
                $disabled="";                   
            }
            $Pagego=$Page-1;
            $htmlNavMinus='<button '.$disabled.' id="btnPageDown" class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored button-secondary m-1 ts_paginator" data-page="'.$Pagego.'" data-submenu_name="'.$dataSubmenu["name_menu"].'" data-submenu_id="'.$dataSubmenu["id"].'" data-folder="'.$dataSubmenu["folder"].'" data-action_view="'.$dataSubmenu["action_view"].'" data-route_view="'.$dataSubmenu["route_view"].'" ><li class="far fa-arrow-alt-circle-left" ></li></button>';
            $disabled="disabled";
            if($ResultadosTotales>($PuntoInicio+$Limit)){

                $disabled="";  
            }
            $Pagego=$Page+1;
            $htmlNavMore='<button '.$disabled.' id="btnPageUp" class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored button-secondary m-1 ts_paginator" data-page="'.$Pagego.'" data-submenu_name="'.$dataSubmenu["name_menu"].'" data-submenu_id="'.$dataSubmenu["id"].'" data-folder="'.$dataSubmenu["folder"].'" data-action_view="'.$dataSubmenu["action_view"].'"  data-route_view="'.$dataSubmenu["route_view"].'" ><li class="far fa-arrow-alt-circle-right" ></li></button>';
            $htmlNavMore.='<br><strong class="text-muted">Página '.$Page.' de '.$TotalPaginas.'</strong>';
                
            
            $sql="SELECT * FROM pedidos_estados ORDER BY ID ASC";
            $Consulta=$obCon->Query($sql);
            $es=1;
            while($DatosEstados=$obCon->FetchAssoc($Consulta)){
                $valuesEstados["values"][$es]=$DatosEstados["ID"];
                $valuesEstados["text"][$es]=$DatosEstados["EstadoPedido"];
                $es=$es+1;
            }
            
            
            $sql="SELECT t1.ID,t1.Created, t2.Nombre,t2.Direccion,t2.Telefono,t1.Total,t1.Estado,
                    (SELECT EstadoPedido FROM pedidos_estados t3 WHERE t3.ID=t1.Estado LIMIT 1) as NombreEstado 
                     FROM pedidos t1 INNER JOIN client_user t2 ON t2.ID=t1.cliente_id
                     $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->Query($sql);
            
            
            
            $i=0;
            $TablaFilas="";
            $TotalPedidos=0;
            $Items=0;
            $server=$_SERVER['SERVER_NAME'];
            $url=$_SERVER['REQUEST_URI'];
            $arrayUrl= explode("/", $url);
            $RutaPDF="http://".$server."/".$arrayUrl[1]."/pdf_order/";
            while($DatosPedidos=$obCon->FetchAssoc($Consulta)){
                $TotalPedidos=$DatosPedidos["Total"];
                $Items=$Items+1;
                $TablaFilas.=$css->FilaTabla(16);
                $id=$DatosPedidos["ID"];                
                $Ruta=$RutaPDF.$id;
                $LinkPDF='<a href="'.$Ruta.'" target="_blank"><span class="fa fa-file-pdf" style="font-size:30px;color:red;cursor:pointer"></span></a>';
                $TablaFilas.=$css->ColTabla($LinkPDF, 1, "L");
                
                $idSel=0;
                foreach ($valuesEstados["values"] as $key => $value) {
                    if($DatosPedidos["Estado"]==$value){
                        $idSel=$key;
                        //print("$key || $value");
                    }
                }
                $valuesEstados["sel"][$idSel]=1;
                
                $htmlSelect=$css->getHtmlSelectBootstrap("cmbEstado_".$id, "cmbEstado_".$id, $valuesEstados, "", 'data-item_id="'.$id.'"', "style=width:180px;","form-control ts_status_order");
                unset($valuesEstados["sel"][$idSel]);
                
                
                foreach ($DatosPedidos as $key => $value) {
                    $Align="L";
                    if($key=="Estado"){
                        continue;
                    }if($key=="NombreEstado"){
                        continue;
                    }
                    if($key=="Total"){
                        $value= number_format($value);
                        $Align="R";
                    }
                    
                    if($key=="ID"){
                        $TablaFilas.=$css->ColTabla($value."<br>".$htmlSelect, 1, $Align);
                        continue;
                    }
                    
                    $TablaFilas.=$css->ColTabla($value, 1, $Align);
                      
                }
                                
                $TablaFilas.=$css->CierraFilaTabla();  
                
                
            }
            
            $z=0;
            //$Titulo="MOSTRANDO <strong>".number_format($Items)."</strong> PEDIDOS PARA UN TOTAL DE: <strong>$". number_format($TotalPedidos)."</strong>";
            
            $Titulo='<div class="row"><div class="col-md-3"></div><div class="col-md-6 text-center">
                                    
                                        <div class="dashboard-stat color-primary">
                                            <div class="content"><h4>'.$ResultadosTotales.'</h4> <span>Pedidos</span></div>
                                            <div class="icon"><i class="icon-layers"></i></div>
                                        </div>
                                    
                                </div>';  
            /*
            $Titulo.='<div class="col-md-3 text-center">
                                    
                                        <div class="dashboard-stat color-success">
                                            <div class="content"><h4>$'.number_format($TotalPedidos).'</h4> <span>Valor de los Pedidos</span></div>
                                            <div class="icon"><i class="icon-briefcase"></i></div>
                                        </div>
                                    
                                </div>';  
             * 
             */
            
            $Titulo.='<div class="col-md-3 text-right">'.$htmlNavMinus.' '.$htmlNavMore.'</div></div>';
            
            $TablaTitulo=$css->FilaTabla(18);
                $TablaTitulo.=$css->ColTabla($Titulo, 8, "C","",1);
            $TablaTitulo.=$css->CierraFilaTabla();
            $js="onclick=FormularioAgregarEditar(`2`)";
            $Columnas[$z++]="<strong>PDF</strong>";
            $Columnas[$z++]="<strong>ID</strong>";
            $Columnas[$z++]="<strong>Fecha</strong>";
            $Columnas[$z++]="<strong>Nombre</strong>";
            $Columnas[$z++]="<strong>Direccion</strong>";
            $Columnas[$z++]="<strong>Telefono</strong>";
            $Columnas[$z++]="<strong>Total</strong>";
            //$Columnas[$z++]="<strong>Estado</strong>";
            $TablaColumnas=$css->FilaTabla(18);
            foreach ($Columnas as $value) {
                $TablaColumnas.=$css->ColTabla($value, 1, "C","",1);
            }   
            $TablaColumnas.=$css->CierraFilaTabla();
            $TablaApertura=$css->CrearTabla("TablaPedidos", 2);
            $TablaCierre=$css->CerrarTabla();
            $headTable=$css->HeadTable();
            $cHeadTable=$css->CheadTable();
            
            
            
            $htmlTabla=$TablaApertura.$headTable.$TablaTitulo.$TablaColumnas.$cHeadTable.$TablaFilas.$TablaCierre;
                  
            $html.=$htmlTabla;
            $html.="</div>";
            $html.="</div>";
            print($html);
            
        break;//Fin caso 1
        
          
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>
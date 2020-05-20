<?php

if(!empty($_REQUEST["action"])){// se verifica si el indice accion es diferente a vacio 
    
    include_once("modules/inventory/constructors/inventory.construct.php");
    include_once("modelo/php_conexion.php");
    $obCon = new conexion(""); //Conexion a la base de datos
    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION["idLocal"])){
        include_once "pages/403.php";
        exit();
    }
    $local_id=$obCon->normalizar($_SESSION["idLocal"]);
   
    switch($_REQUEST["action"]){
       
        case 1://Clasificaciones
            
            $submenu_id=$obCon->normalizar($_REQUEST["submenu_id"]);
            $dataSubmenu=$obCon->DevuelveValores("ts6_modules_menu", "id", $submenu_id);
            
            $css =  new InventoryConstruct($local_id,1000);    
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            
            
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $submenu_id=$obCon->normalizar($_REQUEST["submenu_id"]); 
            
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
            $Condicion=" WHERE ID>0 ";
            
            if($Busqueda<>''){
                $Condicion.=" AND Clasificacion like '%$Busqueda%'";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(t1.ID) as Items 
                   FROM inventarios_clasificacion t1 $Condicion;";
            
            $Consulta2=$obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            $totales = $obCon->FetchAssoc($Consulta2);
            $ResultadosTotales = $totales['Items'];
            
            $TotalPaginas= ceil($ResultadosTotales/$Limit);
            
            $html="<div class='panel'>";
            $html.='<div class="panel-head">
                        <h5 class="panel-title">Clasificación del inventario</h5> 
                        
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
                
            
            $sql="SELECT ID, Clasificacion, Estado FROM inventarios_clasificacion $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            $i=0;
            $Filas[]="";
            while($DatosClasificacion=$obCon->FetchAssoc($Consulta)){
                $Filas[$i]=$DatosClasificacion;
                $i=$i+1;
            }
            $data_table='data-item_id="" data-form_id="1"';
            $Columnas[0]="<strong>Editar</strong>";
            $Columnas[1]="<strong>ID</strong>";
            $Columnas[2]="<strong>Clasificación</strong>";
            $Columnas[3]="<strong>Estado</strong>";
            $Acciones["ID"]["js"]='data-item_id="@value" data-form_id="1"';
            $Acciones["ID"]["icon"]="fa fa-edit ts_form_table_inventory";
            $Acciones["ID"]["style"]="style=font-size:20px;color:blue;cursor:pointer";
            
            $htmlHeaderTable='<div class="row"><div class="col-md-3"><span class="fa fa-plus-circle ts_form_table_inventory" style="font-size:40px;color:green;cursor:pointer" '.$data_table.'></span> <strong>AGREGAR</strong></div>' ;
            $htmlHeaderTable.='<div class="col-md-6 text-center">
                                    <div class="panel panel-default">
                                        <div class="widget-5">
                                            <div class="tbl-cell icon bg-primary"><i class="fa fa-list-alt"></i></div>
                                            <div class="tbl-cell">
                                                <div class="content">
                                                    <h4>'.number_format($ResultadosTotales).'</h4>
                                                    <h4>Clasificaciones</h4>

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
            
        break;//Fin caso 1
        
        case 2://Dibuje el listado de la clasificacion de los inventarios
            
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $item_id=$obCon->normalizar($_REQUEST["item_id"]);
            $css =  new InventoryConstruct($local_id,1000);    
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            print($css->get_form_classifications($item_id,"viewsInventory")); 
            
        break;//Fin caso 2 
        
        case 3: //listado de productos y servicios
            
            $submenu_id=$obCon->normalizar($_REQUEST["submenu_id"]);
            $dataSubmenu=$obCon->DevuelveValores("ts6_modules_menu", "id", $submenu_id);
            
            $css =  new InventoryConstruct($local_id,1000);    
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            
            $Limit=20;
            $idLocal=$obCon->normalizar($_SESSION["idLocal"]);            
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);            
            $Page=$obCon->normalizar($_REQUEST["page"]);
            $Busqueda=$obCon->normalizar($_REQUEST["Busqueda"]);
            
            
            if($Page==''){
                $Page=1;
                
            }
            $Condicion=" WHERE ID<>'' ";
            
            if($Busqueda<>''){
                $Condicion.=" AND (ID='$Busqueda' OR Nombre like '%$Busqueda%' or Referencia like '$Busqueda%')";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(t1.ID) as Items, SUM(PrecioVenta) as TotalPrecioVenta  
                   FROM productos_servicios t1 $Condicion;";
            
            $Consulta2=$obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            $totales = $obCon->FetchAssoc($Consulta2);
            $ResultadosTotales = $totales['Items'];
            $TotalPrecioVenta = $totales['TotalPrecioVenta'];
            
            
            $TotalPaginas= ceil($ResultadosTotales/$Limit);
            
            $html="<div class='panel'>";
            $html.='<div class="panel-head">
                        <h5 class="panel-title">Clasificación del inventario</h5> 
                        
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
                
            
            $sql="SELECT ID, Nombre,PrecioVenta, DescripcionCorta,
                    (SELECT Clasificacion FROM inventarios_clasificacion WHERE  inventarios_clasificacion.ID=productos_servicios.idClasificacion) as Clasificacion  FROM productos_servicios $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            $i=0;
            $Filas[]="";
            while($DatosClasificacion=$obCon->FetchAssoc($Consulta)){
                $Filas[$i]=$DatosClasificacion;
                $i=$i+1;
            }
            $Titulo="PRODUCTOS";
            
            $Columnas[0]="<strong>Editar</strong>";
            $Columnas[1]="<strong>ID</strong>";
            $Columnas[2]="<strong>Nombre</strong>";
            $Columnas[3]="<strong>Precio de Venta</strong>";
            $Columnas[4]="<strong>Descripcion</strong>";
            $Columnas[5]="<strong>Clasificacion</strong>";
                       
            $data_table='data-item_id="" data-form_id="2"';
            $Acciones["ID"]["js"]='data-item_id="@value" data-form_id="2"';
            $Acciones["ID"]["icon"]="fa fa-edit ts_form_table_inventory";
            $Acciones["ID"]["style"]="style=font-size:20px;color:blue;cursor:pointer";
            
            $htmlHeaderTable='<div class="row"><div class="col-md-3"><span class="fa fa-plus-circle ts_form_table_inventory" style="font-size:40px;color:green;cursor:pointer" '.$data_table.'></span> <strong>AGREGAR</strong></div>' ;
            $htmlHeaderTable.='<div class="col-md-3 text-center">
                                    
                                        <div class="dashboard-stat color-warning">
                                            <div class="content"><h4>'.$ResultadosTotales.'</h4> <span>Productos o Servicios</span></div>
                                            <div class="icon"><i class="icon-layers"></i></div>
                                        </div>
                                    
                                </div>';  
            
            $htmlHeaderTable.='<div class="col-md-3 text-center">
                                    
                                        <div class="dashboard-stat color-success">
                                            <div class="content"><h4>$'.number_format($TotalPrecioVenta).'</h4> <span>Total Precios</span></div>
                                            <div class="icon"><i class="icon-briefcase"></i></div>
                                        </div>
                                    
                                </div>';  
            
            $htmlHeaderTable.='<div class="col-md-3 text-right">'.$htmlNavMinus.' '.$htmlNavMore.'</div></div>';
            $htmlTabla=$css->getHtmlTable($htmlHeaderTable, $Columnas, $Filas,$Acciones);
            $html.=$htmlTabla;
            $html.="</div>";
            $html.="</div>";
            print($html);
            
        break;//Fin caso 3    
        
        case 4://dibujo el formulario para crear un producto
            
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $item_id=$obCon->normalizar($_REQUEST["item_id"]);
            $css =  new InventoryConstruct($local_id,1000);    
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            print($css->get_form_inventory($item_id,"viewsInventory")); 
            
        break;//Fin caso 4
        
        case 5://lista de las imagenes de los productos
            
            $Limit=20;
            $idLocal=$obCon->normalizar($_SESSION["idLocal"]);
            $myPath=$obCon->normalizar($_REQUEST["myPath"]);
            $idProducto=$obCon->normalizar($_REQUEST["product_id"]);            
            $DatosLocal=$obCon->DevuelveValores("locales", "ID", $idLocal);
            
            $Page=1;
            $Busqueda='';
            $css =  new InventoryConstruct($local_id,1000); 
            if($Page==''){
                $Page=1;
                
            }
            if($idProducto==''){
                exit("No se recibió el id del producto");                
            }
            $Condicion=" WHERE idProducto='$idProducto' ";
            
            if($Busqueda<>''){
                $Condicion.=" AND (NombreArchivo like '%$Busqueda%' )";
            }
            
            $PuntoInicio = ($Page * $Limit) - $Limit;
            
            $sql = "SELECT COUNT(t1.ID) as Items 
                   FROM productos_servicios_imagenes t1 $Condicion;";
            
            $Consulta2=$obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            $totales = $obCon->FetchAssoc($Consulta2);
            $ResultadosTotales = $totales['Items'];
                        
            $sql="SELECT ID,Ruta, NombreArchivo,Tamano FROM productos_servicios_imagenes $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, $DatosLocal["db"], "");
            $i=0;
            $Filas[]="";
            $imgTotals=0;
            while($DatosClasificacion=$obCon->FetchAssoc($Consulta)){
                $imgTotals=$imgTotals+1;
                $DatosClasificacion["Ruta"]= str_replace("../", "", $DatosClasificacion["Ruta"]);
                $DatosClasificacion["Ruta"]=$myPath.$DatosClasificacion["Ruta"];
                $Filas[$i]=$DatosClasificacion;
                $i=$i+1;
            }
            if($imgTotals==0){
                exit('<div class="alert alert-danger mt-3"><h4 class="alert-heading"></h4><p>Aún no hay Imágenes para este producto.</p><hr><p class="mb-0"></p></div>');
            }
            $z=0;
            $Titulo="IMAGENES";
            $js="onclick=FormularioAgregarEditar(`2`)";
            $Columnas[$z++]="<strong>Eliminar</strong>";
          
            $Columnas[$z++]="<strong>Imagen</strong>";
            $Columnas[$z++]="<strong>Nombre</strong>";
            $Columnas[$z++]="<strong>Tamaño</strong>";
            
            $Acciones["ID"]["Visible"]=0;
            $Acciones["Ruta"]["Visible"]=0;
                        
            $Acciones["ID"]["html"]='<span data-img_id="@value" class="far fa-times-circle ts_remove_img_product" style="font-size:40px;color:red;cursor:pointer"></span>';
            $Acciones["Ruta"]["html"]='<img src="@value" style="width:200px;"></a>';
            $htmlTabla=$css->getHtmlTable("<strong>$Titulo</strong>", $Columnas, $Filas,$Acciones);
            print($htmlTabla);
            
        break;//fin caso 5    
          
        
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>
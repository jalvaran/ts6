<?php

if(!empty($_REQUEST["actionPagesDrawAdmin"])){// se verifica si el indice accion es diferente a vacio 
    
    include_once("modules/admin/constructors/constructor.class.php");
    include_once("modelo/php_conexion.php");
    $obCon = new conexion(""); //Conexion a la base de datos
    
    if(session_status() !== PHP_SESSION_ACTIVE) session_start();
    if(!isset($_SESSION["idLocal"])){
        include_once "pages/403.php";
        exit();
    }
    $local_id=$obCon->normalizar($_SESSION["idLocal"]);
   
    switch($_REQUEST["actionPagesDrawAdmin"]){
       
        case 1://Dibuja el dashboard del admin
            $css =  new AdminConstruct($local_id,1000);    
            if($css->dataClient["ID"]==''){
                include_once "pages/404.php";
                exit();
            }
            $html=$css->get_dashboard_init();
            
                //Contenido que se verá
            
            $html.=$css->get_dashboard_end();            
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
            $html="<div class='panel'>";
            $html.='<div class="panel-head">
                        <h5 class="panel-title">Lista de Locales</h5> 
                        <div class="text-right"><strong>EJECUTAR MIGRACIONES</strong> <button id="btnMigrates" class="mdl-button mdl-js-button mdl-button--fab mdl-button--colored button-error m-1" data-upgraded=",MaterialButton"><i class="fa fa-cogs"></i></button></div>
                    </div>';
            $html.='<div class="panel-body">';
            $Limit=20;
                       
            $DatosLocal=$css->dataClient; 
            if(isset($_REQUEST["Page"])){
                $Page=$obCon->normalizar($_REQUEST["Page"]);
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
            
            $sql = "SELECT COUNT(t1.ID) as Items 
                   FROM locales t1 $Condicion;";
            
            $Consulta2=$obCon->QueryExterno($sql, HOST, USER, PW, DB, "");
            $totales = $obCon->FetchAssoc($Consulta2);
            $ResultadosTotales = $totales['Items'];
            
            if($ResultadosTotales>$Limit){
                $TotalPaginas= ceil($ResultadosTotales/$Limit);
                if($Page>1){
                    $js="onclick=pageMinusAdmin();";
                    //$css->botonNavegacion($js, "green", "pageNav-pageBack-icon mdi mdi-arrow-left-bold", "PageMinus");
                }
                if($ResultadosTotales>($PuntoInicio+$Limit)){
                    $js="onclick=pageAddAdmin();";
                    //$css->botonNavegacion($js, "green", "pageNav-pageForward-icon mdi mdi-arrow-right-bold", "PageAdd");
                }
            }
            
            $sql="SELECT ID, Nombre,Direccion,Telefono,Email,Password, Estado FROM locales $Condicion ORDER BY ID DESC LIMIT $PuntoInicio,$Limit;";
            $Consulta=$obCon->QueryExterno($sql, HOST, USER, PW, DB, "");
            $i=0;
            $Filas[]="";
            while($DatosClasificacion=$obCon->FetchAssoc($Consulta)){
                $Filas[$i]=$DatosClasificacion;
                $i=$i+1;
            }
            $z=0;
            $js='data-item_id="" data-form_id="1"';
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
            $htmlTabla=$css->getHtmlTable("<span class='fa fa-plus-circle ts_form_table' style='font-size:40px;color:green;cursor:pointer' $js></span> <strong>AGREGAR</strong> ", $Columnas, $Filas,$Acciones);
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
            print($css->get_form_locals($item_id));
            
        break;//Fin caso 3
    
        case 4://Dibuja el slider de un producto
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $dataProduct=$obCon->normalizar($_REQUEST["dataProduct"]);
            
            $local_id=$obCon->normalizar($_REQUEST["local_id"]);            
            $css =  new PageConstruct($local_id,3,$path);           
            $dataProduct= get_object_vars(json_decode(base64_decode($dataProduct)));
            
            print($css->get_slider_product($dataProduct));
        break;//Fin caso 4
    
        case 5://Dibuja una orden de pedido
            $path=$obCon->normalizar($_REQUEST["myPath"]);
            $idClientUser=$obCon->normalizar($_REQUEST["idClientUser"]);            
            $local_id=$obCon->normalizar($_REQUEST["local_id"]); 
            
            $css =  new PageConstruct($local_id,3,$path);  
            print($css->get_shop_order($idClientUser));
        break;//Fin caso 5
        
        
          
        
 }
    
          
}else{
    print("No se enviaron parametros");
}
?>
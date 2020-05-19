<?php

include_once("constructores/paginas_constructor.php");

class AdminConstruct extends PageConstruct{
    
    public $obCon;
    
    public function get_dashboard_init() {
        $this->obCon=new conexion($this->dataClient["ID"]);
        $html=$this->get_html_init("es");
        $keywords=$this->dataClient["keywords"];
        $favicon="clients/".$this->dataClient["ID"].'/images/favicon.png';
        if(!file_exists($favicon)){
            $favicon="images/favicon.png";
        }
        $favicon=$this->path.$favicon;
        $html.= $this->get_heads_admin($this->dataClient["title_page"],$keywords,$favicon);
        $html.=$this->get_body_admin();
        $html.=$this->get_modal("modalAdmin", "Administrador", "divModal", 2, 1, "");
        $html.='<div class="wrapper">';
        $html.='<!-- Main Container -->
                <div id="main-wrapper" class="menu-fixed page-hdr-fixed">';
        
        $html.= $this->get_menu_admin();
        $html.= $this->get_header_admin();
        
        $html.='<!-- Main Page Wrapper -->
            <div class="page-wrapper">
                <!-- Page Title -->
                <div class="page-title">
                    <div class="row align-items-center">
                        <div class="col-sm-6">
                            <h2 id="titleModule" class="page-title-text">Administrador TS6</h2>
                        </div>
                        <div class="col-sm-6 text-right">
                            
                        </div>
                    </div>
                </div>
                <!-- Page Body -->
                <div class="page-body">
                <div id="divContentModule">
                
                                        ';
        
        
        
        return($html);
    }
    
    public function get_dashboard_end() {
        
        
        $html='   
                    </div>

                </div>
            </div>
            <!-- Page Footer -->
            <div class="page-ftr">
                <div>© 2020. Techno Soluciones SAS</div>
            </div>
        </div>';
        
        $html.='</div>';//Fin main Container
        $html.='</div>';//Fin wrapper
        $html.= $this->get_js_admin();
        
        $html.='</body>';
        $html.='</html>';
        return($html);
    }
    
    public function get_header_admin() {
        $html='<!-- Page header -->
            <div class="page-hdr">
                <div class="row align-items-center">
                    <div class="col-4 col-md-7 page-hdr-left">
                        <!-- Logo Container -->
                        <div id="logo">
                            <div class="tbl-cell logo-icon">
                                <a href="#"><img src="'.$this->path.'admin/images/icon.png" alt=""></a>
                            </div>
                            <div class="tbl-cell logo">
                                <a href="#"><img src="'.$this->path.'admin/images/logo.png"></a>
                            </div>
                        </div>
                        <div class="page-menu menu-icon">
                            <a class="animated menu-close"><i class="far fa-hand-point-left"></i></a>
                        </div>
                        <div class="page-menu page-fullscreen">
                            <a><i class="fas fa-expand"></i></a>
                        </div>
                        <div class="page-search">
                            <input type="text" placeholder="Buscar....">
                        </div>
                    </div>
                    <div class="col-8 col-md-5 page-hdr-right">
                        <div class="page-hdr-desktop">
                            <div class="page-menu menu-dropdown-wrapper menu-user">
                                <a class="user-link">
                                    <span class="tbl-cell user-name pr-3">Hola <span class="pl-2">'.$this->dataClient["Nombre"].'</span></span>
                                    <span class="tbl-cell avatar"><i class="fa fa-user-circle"></i><img ></span>
                                </a>
                                <div class="menu-dropdown menu-dropdown-right menu-dropdown-push-right">
                                    <div class="arrow arrow-right"></div> 
                                    <div class="menu-dropdown-inner">
                                        <div class="menu-dropdown-head pb-3">
                                            <div class="tbl-cell">
                                                <img class="" alt="">
                                                <i class="fa fa-user-circle"></i>
                                            </div>
                                            <div class="tbl-cell pl-2 text-left">
                                                <p class="m-0 font-18">'.$this->dataClient["Nombre"].'</p>
                                                <p class="m-0 font-14">Administrador</p>
                                            </div>
                                        </div>
                                        <div class="menu-dropdown-body">
                                            <!-- menú del dropdwn 
                                            <ul class="menu-nav">
                                                <li><a href="#"><i class="icon-event"></i><span>My Events</span></a></li>
                                                <li><a href="#"><i class="icon-notebook"></i><span>My Notes</span></a></li>
                                                <li><a href="#"><i class="icon-user"></i><span>My Profile</span></a></li>
                                                <li><a href="#"><i class="icon-globe-alt"></i><span>Client Portal</span></a></li>
                                            </ul>
                                            -->
                                        </div>
                                        <div class="menu-dropdown-footer text-right">
                                            <a id="btnLogout" class="btn btn-outline btn-primary btn-pill btn-outline-2x font-12 btn-sm">Salir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- uso futuro
                            <div class="page-menu menu-dropdown-wrapper menu-notification">
                                <a><i class="icon-bell"></i><span class="notification">20</span></a>
                                <div class="menu-dropdown menu-dropdown-right menu-dropdown-push-right">
                                    <div class="arrow arrow-right"></div> 
                                    <div class="menu-dropdown-inner">
                                        <div class="menu-dropdown-head">Notification</div>
                                        <div class="menu-dropdown-body">
                                            <ul class="timeline m-0">
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">Wallet Adddes </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">Coin Transferred from BTC<span class="badge badge-danger badge-pill badge-sm">Unpaid</span></div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">BTC bought</div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">Server Restarted <span class="badge badge-success badge-pill badge-sm">Resolved</span></div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="" target="_blank" class="timeline-container">
                                                        <div class="arrow"></div>
                                                        <div class="description">New order received</div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="page-menu menu-dropdown-wrapper menu-quick-links">
                                <a><i class="icon-grid"></i></a>
                                <div class="menu-dropdown menu-dropdown-right menu-dropdown-push-right">
                                    <div class="arrow arrow-right"></div> 
                                    <div class="menu-dropdown-inner">
                                        <div class="menu-dropdown-head">Quick Links</div>
                                        <div class="menu-dropdown-body p-0">
                                            <div class="row m-0 box">
                                                <div class="col-6 p-0 box">
                                                    <a href="">
                                                        <i class="icon-emotsmile"></i>
                                                        <span>New Contact</span>
                                                    </a>
                                                </div>
                                                <div class="col-6 p-0 box">
                                                    <a href="">
                                                        <i class="icon-docs"></i>
                                                        <span>New Invoice</span>
                                                    </a>
                                                </div>
                                                <div class="col-6 p-0 box">
                                                    <a href="">
                                                        <i class="icon-calculator"></i>
                                                        <span>New Quote</span>
                                                    </a>
                                                </div>
                                                <div class="col-6 p-0 box">
                                                    <a href="">
                                                        <i class="icon-rocket"></i>
                                                        <span>New Expense</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="page-menu">
                                <a class="open-sidebar-right"><i class="icon-settings"></i><span></span></a>
                            </div>
                            -->
                        </div>
                        <div class="page-hdr-mobile">
                            <div class="page-menu open-mobile-search">
                                <a href="#"><i class="icon-magnifier"></i></a>
                            </div>
                            <div class="page-menu open-left-menu">
                                <a href="#"><i class="icon-menu"></i></a>
                            </div>
                            <div class="page-menu oepn-page-menu-desktop">
                                <a href="#"><i class="icon-options-vertical"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        
        return($html);
    }
    
    public function get_menu_admin() {
        
        $html='<!-- Menu Wrapper -->
                    <div class="menu-wrapper">
                        <div class="menu">
                            <!-- Menu Container -->
                            <ul>';
        
        $html.='<li class="menu-title">Menú</li>
                ';
        $sql="SELECT * FROM ts6_modules WHERE status_module=1";
        $query= $this->obCon->Query($sql);
        while($dataModule=$this->obCon->FetchAssoc($query)){
            $module_id=$dataModule["id"];
            if($module_id==1){
                if($this->dataClient["ID"]<>1){
                    continue;
                }
            }
            $html.='<li class="has-sub"> <a class="ts-menu-modules" data-folder="'.$dataModule["folder"].'"><i class="'.$dataModule["icon_module"].'"></i><span>'.$dataModule["name"].'</span><i class="arrow rotate"></i></a>';
                    $html.='<ul class="sub-menu">';

                    $sql="SELECT * FROM ts6_modules_menu WHERE modules_id='$module_id' AND status_menu=1";
                    $querySubMenu= $this->obCon->Query($sql);
                    while($dataSubMenu= $this->obCon->FetchAssoc($querySubMenu)){
                        $html.='<li>
                                    <a class="ts-submenu-modules" data-submenu_name="'.$dataSubMenu["name_menu"].'" data-submenu_id="'.$dataSubMenu["id"].'" data-folder="'.$dataModule["folder"].'" data-action_view="'.$dataSubMenu["action_view"].'" ><span>'.$dataSubMenu["name_menu"].'</span></a>
                                </li>';
                    }

                $html.='</li></ul>';
        }
        
             
        $html.='</ul>';
        
        $html.='</div>';//Div menu
        $html.='</div>';//div menu-wrapper
        
        return($html);
    }
    
    public function get_js_admin() {
        $html='<!-- Include js files -->
            <!-- Alertify-->
            <script src="'.$this->path.'assets/plugin/alertify/lib/alertify.min.js"></script>
            <!-- Vendor Plugin -->
            <script type="text/javascript" src="'.$this->path.'admin/assets/plugin/vendor.min.js"></script>
            <!-- Raphael Plugin -->
            <script type="text/javascript" src="'.$this->path.'admin/assets/plugin/raphael/raphael-min.js"></script>
            <!-- Morris Plugin -->
            <script type="text/javascript" src="'.$this->path.'admin/assets/plugin/morris/morris.min.js"></script>
            <!-- Sparkline Plugin -->
            <script type="text/javascript" src="'.$this->path.'admin/assets/plugin/sparkline/jquery.sparkline.min.js"></script>
             <!-- Jquery Cookie JavaScript-->
            <script src="'.$this->path.'assets/plugin/jquery_cookie/jquery.cookie.js"></script>  
            <!-- Dropzone Plugin -->
            <script type="text/javascript" src="'.$this->path.'admin/assets/plugin/dropzone/dropzone.min.js"></script>
            <!-- Sweetalert Plugin -->
            <script type="text/javascript" src="'.$this->path.'admin/assets/plugin/sweetalert/sweetalert.js"></script>    
            <!-- Custom Script pages -->            
            <script type="text/javascript" src="'.$this->path.'modules/admin/jsPages/admin.js"></script>
            <script type="text/javascript" src="'.$this->path.'modules/admin/jsPages/migrations.js"></script>  
            <!-- Custom Script Plugin -->
            <script type="text/javascript" src="'.$this->path.'admin/dist/js/custom.js"></script>
            <!-- Custom demo Script for Dashbaord -->
            <script src="'.$this->path.'general/js/general.js"></script>
            <script type="text/javascript" src="'.$this->path.'admin/dist/js/demo/dashboard.js"></script>
               
                ';
        
        return($html);
    }
    
    public function get_body_admin() {
        $html='<body>
                <div class="loader-wrapper">
                    <div class="loader spinner-3">
                        <div class="bg-primary"></div>
                        <div class="bg-primary"></div>
                        <div class="bg-primary"></div>
                        <div class="bg-primary"></div>
                        <div class="bg-primary"></div>
                        <div class="bg-primary"></div>
                        <div class="bg-primary"></div>
                        <div class="bg-primary"></div>
                        <div class="bg-primary"></div>
                    </div>
                </div>';
        return($html);
    }
    
    public function get_heads_admin($title,$keywords,$favicon){
        
        $html='<head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <!-- Meta Description, Author, Keywords Tag -->
                <meta name="author" content="Techno Soluciones SAS">
                <meta name="description" content="Plataforma TS6">
                <meta name="keywords" content="'.$keywords.'">
                <title>'.$title.'</title>
                <link rel="icon" type="image/x-icon" href="'.$favicon.'">
                <!-- Alertify -->
                <link rel="stylesheet" href="'.$this->path.'assets/plugin/alertify/themes/alertify.core.css" />
                <link rel="stylesheet" href="'.$this->path.'assets/plugin/alertify/themes/alertify.default.css" id="toggleCSS" />
                <!-- Switcher CSS -->
                <link rel="stylesheet" href="'.$this->path.'admin/assets/plugin/switchery/switchery.min.css" />
                <!-- Material CSS -->
                <link rel="stylesheet" href="'.$this->path.'admin/assets/plugin/material/material.min.css" />
                <!-- Sweetalert CSS -->
                <link rel="stylesheet" href="'.$this->path.'admin/assets/plugin/sweetalert/sweetalert.css" />
                <!-- Dropzone CSS -->
                <link rel="stylesheet" href="'.$this->path.'admin/assets/plugin/dropzone/dropzone.min.css" />    
                <!-- Morris CSS -->
                <link rel="stylesheet" href="'.$this->path.'admin/assets/plugin/morris/morris.css" />
                    
                <!-- Custom Stylesheet -->
                <link rel="stylesheet" href="'.$this->path.'admin/dist/css/style.css" />
            </head>';
        
        return($html);
    }
    
    function getHtmlTable($Titulo,$Columnas,$Filas,$Acciones=""){
        $html='<div class="mdc-card p-0">
                  <h6 class="card-title card-padding pb-0">'.$Titulo.'</h6>
                  <div class="table-responsive">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>';
        
        foreach ($Columnas as $key => $value) {
            $html.='<th class="text-left">'.$value.'</th>';
        }
        
        $html.='</tr>
                </thead>
                      <tbody>
                ';
        if(is_array($Filas[0])){
            foreach ($Filas as $DatosItems) {
                $html.='<tr >';
                foreach ($DatosItems as $key => $value) {
                    
                    if(isset($Acciones[$key]["js"])){
                        $jsIcon= str_replace("@value", $value, $Acciones[$key]["js"]);
                        $html.='<td class="text-left"><span class="'.$Acciones[$key]["icon"].'" '.$Acciones[$key]["style"].' '.$jsIcon.'></span></td>';
                    }
                    if(isset($Acciones[$key]["html"])){
                        $htmlCol= str_replace("@value", $value, $Acciones[$key]["html"]);
                        $htmlCol= str_replace("@ID", $DatosItems["ID"], $htmlCol);
                        $html.='<td class="text-left">'.$htmlCol.'</td>';
                    }
                    if(isset($Acciones[$key]["Visible"]) AND $Acciones[$key]["Visible"]==0){
                        continue;
                    }
                    $html.='<td class="text-left">'.$value.'</td>';
                    
                    
                   


                }
                $html.='</tr>';
            }
        }
        $html.='</tbody>
                    </table>
                  </div>
                </div>
                ';
        
        return($html);
    }
    
    public function get_form_locals($item_id) {
        $dataLocal=$this->obCon->DevuelveValores("locales", "ID", $item_id);
        
        $form_identify=$this->obCon->getUniqId("frm_");
        $html='<div class="panel panel-default">
                <div class="panel-head">
                    <div class="panel-title">
                        <span class="panel-title-text">Agregar o Editar un local</span>
                    </div>
                </div>
                <div class="panel-body">
                         <form id="frm_save_local" method="post">
                        <div class="form-body">
                            <div class="form-heading">Información General</div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Nombre</label>
                                        <input id="Nombre" value="'.$dataLocal["Nombre"].'" type="text" class="form-control frm_local" placeholder="Nombre">
                                        <span class="form-text">Por favor ingrese el nombre</span> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Dirección</label>
                                        <input id="Direccion" value="'.$dataLocal["Direccion"].'" type="text" class="form-control frm_local" placeholder="Dirección">
                                        <span class="form-text">Por favor digite la dirección</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Teléfono</label>
                                        <input id="Telefono" value="'.$dataLocal["Telefono"].'" type="text" class="form-control frm_local" placeholder="Teléfono">
                                        <span class="form-text">Por favor digite el Teléfono</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Indicativo</label>
                                        <input id="Indicativo" value="'.$dataLocal["Indicativo"].'" type="text" class="form-control frm_local" placeholder="Indicativo">
                                        <span class="form-text">Por favor digite el Indicativo</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Whatsapp</label>
                                        <input id="Whatsapp" value="'.$dataLocal["Whatsapp"].'" type="text" class="form-control frm_local" placeholder="Whatsapp">
                                        <span class="form-text">Por favor digite el Whatsapp</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">id Telegram</label>
                                        <input id="idTelegram" value="'.$dataLocal["idTelegram"].'" type="text" class="form-control frm_local" placeholder="id Telegram">
                                        <span class="form-text">Por favor digite el id del Telegram</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Propietario</label>
                                        <input id="Propietario" value="'.$dataLocal["Propietario"].'" type="text" class="form-control frm_local" placeholder="Propietario">
                                        <span class="form-text">Por favor digite el Propietario</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Tarifa</label>
                                        <input id="Tarifa" type="number" value="'.$dataLocal["Tarifa"].'" class="form-control frm_local" placeholder="Tarifa">
                                        <span class="form-text">Por favor digite el Tarifa</span>
                                    </div>
                                </div>

                                ';
                    $sql="SELECT * FROM ciudades";
                    $query=$this->obCon->Query($sql);
                    $html.='<div class="col-md-4">

                                <div class="form-group">
                                    <label class="col-form-label">Ciudad </label>
                                    <select id="idCiudad" class="form-control frm_local">
                                ';
                    while($datosConsulta= $this->obCon->FetchAssoc($query)){
                            $sel="";
                            if($datosConsulta["ID"]==$dataLocal["idCiudad"]){
                                $sel="selected";
                            }
                        $html.='<option value="'.$datosConsulta["ID"].'" '.$sel.' >'.$datosConsulta["Nombre"].'</option>';
                    }

                    $html.='</select>
                            </div>
                            </div>';

                    $html.='
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Descripción</label>
                                        <textarea id="Descripcion" type="text" class="form-control frm_local" placeholder="Descripción">'.$dataLocal["Descripcion"].'</textarea>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@</span>
                                            </div>
                                            <input id="Email" value="'.$dataLocal["Email"].'" type="email" class="form-control frm_local" placeholder="Email">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Password</label>
                                        <input id="Password" value="'.$dataLocal["Password"].'" type="text" class="form-control frm_local" placeholder="Password">

                                    </div>
                                </div>

                            </div>

                            <div class="form-seperator-dashed"></div>
                            <h3 class="form-heading">Datos de Configuración</h3>
                            <div class="row">';

                            $sql="SELECT * FROM catalogo_categorias WHERE Estado=1";
                            $query=$this->obCon->Query($sql);
                            $html.='<div class="col-md-4">

                                        <div class="form-group">
                                            <label class="col-form-label">Categoría </label>
                                            <select id="idCategoria" class="form-control frm_local">
                                        ';
                            while($datosCategorias= $this->obCon->FetchAssoc($query)){
                                $sel="";
                                if($datosCategorias["ID"]==$dataLocal["idCategoria"]){
                                    $sel="selected";
                                }
                                $html.='<option value="'.$datosCategorias["ID"].'" '.$sel.' >'.$datosCategorias["Nombre"].' </option>';
                            }

                            $html.='</select>
                                    </div>
                                    </div>';

                            $sql="SELECT * FROM html_css_themes";
                            $query=$this->obCon->Query($sql);
                            $html.='<div class="col-md-4">

                                        <div class="form-group">
                                            <label class="col-form-label">Color para interfaz </label>
                                            <select id="theme_id" class="form-control frm_local">
                                        ';
                            while($datosConsulta= $this->obCon->FetchAssoc($query)){
                                $sel="";
                                if($datosConsulta["id"]==$dataLocal["theme_id"]){
                                    $sel="selected";
                                }
                                $html.='<option value="'.$datosConsulta["id"].'" '.$sel.'  >'.$datosConsulta["css"].'</option>';
                            }

                            $html.='</select>
                                    </div>
                                    </div>';

                            $sql="SELECT * FROM pages";
                            $query=$this->obCon->Query($sql);
                            $html.='<div class="col-md-4">

                                        <div class="form-group">
                                            <label class="col-form-label">Página Inicial </label>
                                            <select id="page_initial" class="form-control frm_local">
                                        ';
                            while($datosConsulta= $this->obCon->FetchAssoc($query)){
                                $sel="";
                                if($datosConsulta["id"]==$dataLocal["page_initial"]){
                                    $sel="selected";
                                }
                                
                                $html.='<option value="'.$datosConsulta["id"].'" '.$sel.' >'.$datosConsulta["name"].'</option>';
                            }

                            $html.='</select>
                                    </div>
                                    </div>';
                            if($dataLocal["header_class"]==''){
                                $dataLocal["header_class"]="dark";
                            }
                            if($dataLocal["slider_class"]==''){
                                $dataLocal["slider_class"]="dark";
                            }
                            if($dataLocal["virtual_shop"]==""){
                                $dataLocal["virtual_shop"]=1;
                            }
                           $html.='<div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Orden</label>
                                        <input id="Orden" type="number" value="'.$dataLocal["Orden"].'"  class="form-control frm_local" placeholder="Orden">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">header_class</label>
                                        <input id="header_class" value="'.$dataLocal["header_class"].'" type="text" class="form-control frm_local" placeholder="header_class">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">slider_class</label>
                                        <input id="slider_class" value="'.$dataLocal["slider_class"].'" type="text" class="form-control frm_local" placeholder="slider_class">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">keywords</label>
                                        <input id="keywords" value="'.$dataLocal["keywords"].'" type="text" class="form-control" placeholder="keywords">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">virtual_shop</label>
                                        <input id="virtual_shop" value="'.$dataLocal["virtual_shop"].'"  type="number" class="form-control frm_local" placeholder="virtual_shop">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Url del Local</label>
                                        <input id="UrlLocal" value="'.$dataLocal["UrlLocal"].'" type="text" class="form-control frm_local" placeholder="Url Local">                                                        
                                    </div>
                                    <span class="form-text">URL corta para Acceder al local</span>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Título de la Página</label>
                                        <input id="title_page" value="'.$dataLocal["title_page"].'"  type="text" class="form-control frm_local" placeholder="Título de la Página">                                                        
                                    </div>
                                    <span class="form-text">Título que mostrará el explorador en la parte superior</span>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Alcance</label>
                                        <select id="Alcance" class="form-control frm_local" placeholder="Alcance">'; 
                                        $sel="";
                                        if($dataLocal["Alcance"]==1){
                                            $sel="selected";
                                        }
                                        $html.='<option value="1" '.$sel.'>Local</option>';
                                        $sel="";
                                        if($dataLocal["Alcance"]==0){
                                            $sel="selected";
                                        }
                                        $html.='<option value="0" '.$sel.'>Nacional</option>';                                                        

                                    $html.='</select>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Estado</label>
                                        <select id="Estado" class="form-control frm_local" placeholder="Estado">'; 
                                        $sel="";
                                        if($dataLocal["Estado"]==1){
                                            $sel="selected";
                                        }
                                        $html.='    <option value="1" '.$sel.' >Habilitado</option>';
                                        $sel="";
                                        if($dataLocal["Estado"]==0){
                                            $sel="selected";
                                        }
                                        $html.='    <option value="0" '.$sel.'>Deshabilitado</option>';                                                        

                                       $html.=' </select>
                                    </div>

                                </div>

                            </div>
                            </form>


                            <div class="form-seperator-dashed"></div>
                            <h3 class="form-heading">Imágenes</h3>
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="panel">
                                        <div class="panel-head">
                                            <h5 class="panel-title">Selecciona el logo del local</h5>
                                        </div>
                                        <div class="panel-body">
                                            <form data-form_identify="'.$form_identify.'" action="/" class="dropzone dz-clickable" id="logoLocal"><div class="dz-default dz-message"><span><i class="icon-plus"></i>Drop files here or click here to upload.<br> Upload Any Files.</span></div></form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="panel">
                                        <div class="panel-head">
                                            <h5 class="panel-title">Selecciona una foto del local</h5>
                                        </div>
                                        <div class="panel-body">
                                            <form data-form_identify="'.$form_identify.'" action="/" class="dropzone dz-clickable" id="fotoLocal"><div class="dz-default dz-message"><span><i class="icon-plus"></i>Drop files here or click here to upload.<br> Upload Any Files.</span></div></form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                </div>
                <div class="panel-footer text-right">                                    
                    <button id="btn_form_save" data-table_id="3" data-form_identify="'.$form_identify.'" class="btn btn-primary btn-pill">Crear</button>
                </div>


            </div>';
        return($html);
    }
    
    /**
     * Fin Clase
     */
}

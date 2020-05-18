<?php
include_once 'html_estruct_class.php';
if(file_exists('modelo/php_conexion.php')){
    include_once 'modelo/php_conexion.php';
}
/**
 * Clase constructora de las paginas
 */
class PageConstruct extends html_estruct_class{
    
    public  $path;
    public  $imageLogoLocal;
    public  $dataClient;    
    public  $dataTheme;
    public  $dataPage;
    public  $obCon;
    /**
     * constructor de la clase 
     * @param type $client_id -> id del cliente
     */
    function __construct($client_id,$page_id=1,$path=""){
        if($path==""){
            $arrayPath= explode("/", $_SERVER['REQUEST_URI']);
            if(isset($arrayPath[1])){
                for($i=3;$i<(count($arrayPath));$i++){
                    $this->path.="../";
                }
            }
        }else{
            $this->path.=$path;
        }
        if($client_id==""){
            $client_id=1;
        }
        $this->obCon=new conexion($client_id);
        $this->client_id=$client_id;    
        $this->dataClient=$this->obCon->DevuelveValores("locales", "ID", $client_id);
        $dataImg=$this->obCon->DevuelveValores("locales_imagenes", "idLocal", $client_id);
        $this->imageLogoLocal=$dataImg["Ruta"];
        if($page_id==0){
            $page_id=$this->dataClient["page_initial"];
        }
        $this->dataTheme=$this->obCon->DevuelveValores("html_css_themes", "id", $this->dataClient["theme_id"]);
        if($this->dataTheme["css"]==''){
            $this->dataTheme["css"]="blue";
        }
        
        $this->dataPage=$this->obCon->DevuelveValores("pages", "id", $page_id);
        if($page_id==1000){
            $this->dataPage["id"]=1000;
        }        
        
    }  
    
    /**
     * retorna la inicializacion del html
     * @param type $lang
     * @return type
     */
    public function get_html_init($lang) {
       
        return('<!DOCTYPE html>
                    <html lang="'.$lang.'">');
    }
    
    public function get_Chtml_init() {
        return('</html>');
    }
    /**
     * retorna las cabeceras de la pagina
     * @param type $title
     * @return type
     */
    public function get_heads($title,$keywords='',$favicon='images/favicon.png'){
        $favicon=$this->path.$favicon;
       
        $html='<head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <!-- Site Title -->
                    <title>'.$title.'</title>
                    <!-- Meta Description, Author, Keywords Tag -->
                    <meta name="author" content="Techno Soluciones SAS">
                    <meta name="description" content="Plataforma TS6">
                    <meta name="keywords" content="'.$keywords.'">
                    <!-- Alertify -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/alertify/themes/alertify.core.css" />
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/alertify/themes/alertify.default.css" id="toggleCSS" />
                    <!-- Select2 -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/select2/css/select2.min.css" />
                    <!-- Favicon Icon -->
                    <link rel="icon" type="image/x-icon" href="'.$favicon.'" />
                    <!-- Material Design Lite Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/material/material.min.css" />
                    <!-- Material Design Select Field Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/material/mdl-selectfield.min.css">
                    <!-- Animteheading Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/animateheading/animateheading.min.css" />
                    <!-- Owl Carousel Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/owl_carousel/owl.carousel.min.css" />
                    <!-- Animate Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/animate/animate.min.css" />
                    <!-- Magnific Popup Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/magnific_popup/magnific-popup.min.css" />
                    <!-- Flex Slider Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/flexslider/flexslider.min.css" />
                    <!-- Sweetalert CSS -->
                    <link rel="stylesheet" href="'.$this->path.'assets/plugin/sweetalert/sweetalert.css" /> 
                    <!-- Custom Main Stylesheet by Techno CSS -->
                    <link rel="stylesheet" href="'.$this->path.'dist/css/techno/ts-style.css">
                    <!-- Custom Main Stylesheet CSS -->
                       <link rel="stylesheet" href="'.$this->path.'dist/css/style.css"> 
                    
                       
                    ';
                            
                $html.='
                        </head>';
                return($html);
    }
    
    public function get_menu() {
        
        $html='<div class="tbl-cell hdr-menu">                
                <ul class="menu">';
        $sql="SELECT * FROM menu WHERE client_id ='".$this->dataClient["ID"]."' ORDER BY order_menu,id ASC";        
        $query=$this->obCon->Query($sql);
        while($dataMenu=$this->obCon->FetchAssoc($query)){
            $html.='<li class="menu-megamenu-li">
                        <a id="menu_'.$dataMenu["id"].'"  class="mdl-button mdl-js-button mdl-js-ripple-effect ts_menu" data-name="'.$dataMenu["menu_name"].'" data-page_id="'.$dataMenu["page_id"].'" data-local_id="'.$this->dataClient["ID"].'">'.$dataMenu["menu_name"].'</a>                                    
                    </li>';
        }
        

        $html.=' <li>
                    <a id="iconLoginAdmin" class="mdl-button mdl-js-button mdl-js-ripple-effect hdr-search" href="#"><i class="icon-login"></i></a>
                </li>
                <li class="mobile-menu-close"><i class="fa fa-times"></i></li>
                </ul>';
        return($html);
    }
    
    public function get_headerGeneral() {
        $urlLogo="clients/".$this->dataClient["ID"]."/images/logo-header.png";
        
        if(!file_exists($urlLogo)){
            $urlLogo=$this->path."images/domismall.png";
        }else{
            $urlLogo=$this->path.$urlLogo;
        }
        
        $html='<header id="header" class="header-'.$this->dataClient["header_class"].'">
            <div class="layer-stretch hdr">
                <div class="tbl animated fadeInDown">
                    <div class="tbl-row">
                        <!-- Start Header Logo Section -->
                        <div class="tbl-cell">
                            <a href="#"><img src="'.$urlLogo.'" alt="" style="width:200px"></a>
                        </div><!-- End Header Logo Section -->';
        
        $html.=$this->get_menu();          
        
        $html.='<div id="menu-bar"><a><i class="fa fa-bars"></i></a></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </header><!-- End Header Section -->';
        return($html);
    }
    
    public function get_slider() {
        $page_id=$this->dataPage["id"];
        $dataPage=$this->obCon->DevuelveValores("pages", "id", $page_id);
        $dataSliders=$this->obCon->DevuelveValores("clients_slider", "client_id", $this->dataClient["ID"]);
        
        $html="";
        $background_image='clients/'.$this->dataClient["ID"].'/images/slider1.jpg';
        if(!file_exists($background_image)){
            $background_image=$this->path.'images/slider-1.jpg';
        }else{
            $background_image=$this->path.$background_image;
        }
        
        //print($background_image);
        switch ($dataPage["slider_id"]){
            case 1://slider para la pagina 1 E-Comerce 1
                //$background_image=$this->path.'images/slider-1.jpg';
                $html.='<div id="slider" class="slider-transparent slider-half">
                    <div class="flexslider slider-wrapper">
                        <ul class="slides">
                            <li>
                                <div class="slider-backgroung-image" style="background-image: url('.$background_image.');">

                                    <div class="layer-stretch">
                                        <div class="slider-info">
                                            <h1 style="color:white">'.($this->dataClient["Nombre"]).'</h1>
                                            <p style="color:white">'.($this->dataClient["Descripcion"]).'</p>
                                            <div class="slider-button">
                                                <a id="btn_slider1" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect button button-primary button-pill ts_menu" data-page_id="3" data-local_id="'.$this->dataClient["ID"].'">Tienda Virtual</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div><!-- End Slider Section -->';
            break; //Fin caso 1
            case 2://slider para la pagina 1 E-Comerce 2
                $html.='<div id="slider" class="slider-transparent slider-half">
                    <div class="flexslider slider-wrapper">
                        <ul class="slides">
                            <li>
                                <div class="slider-backgroung-image" style="background-image: url('.$background_image.');">

                                    <div class="layer-stretch">
                                        <div class="slider-info">
                                            <h1 style="'.$dataSliders["style_text"].'">'.($dataSliders["title"]).'</h1>
                                            <p style="'.$dataSliders["style_text"].'">'.($dataSliders["paragraph"]).'</p>
                                            <div class="slider-button">
                                                <a id="'.$dataSliders["button_id"].'" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect button button-primary button-pill ts_menu" data-page_id="'.$dataSliders["button_page_id"].'" data-local_id="'.$this->dataClient["ID"].'">'.($dataSliders["button_value"]).'</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div><!-- End Slider Section -->';
            break; //Fin caso 2
            
            case 3://slider 2
                
                $html.='<!-- Start Page Title Section -->
                        <div class="page-ttl page-'.$this->dataClient["slider_class"].'" style="background-image: url('.$background_image.');>
                            <div class="layer-stretch">
                                <div class="page-ttl-container">
                                    <h1>Tienda Virtual</h1></div>';
                                    
                
                
                $html.='            
                                
                            </div>
                            
                        </div><!-- End Page Title Section -->';
            break;  //Fin caso 2
            
        
        }
        return($html);
        
    }
   
    public function get_JSGeneral() {
        $html=('<!-- Alertify-->
                <script src="'.$this->path.'assets/plugin/alertify/lib/alertify.min.js"></script>
                <!-- MD5 JS-->
                <script src="'.$this->path.'general/js/md5.js"></script>    
                <!-- Jquery Library 2.1 JavaScript-->
                <script src="'.$this->path.'assets/plugin/jquery/jquery-2.1.4.min.js"></script>
                <!-- Jquery Cookie JavaScript-->
                <script src="'.$this->path.'assets/plugin/jquery_cookie/jquery.cookie.js"></script>  
                <!-- Select 2-->
                <script src="'.$this->path.'assets/plugin/select2/js/select2.full.min.js"></script>        
                <!-- Popper JavaScript-->
                <script src="'.$this->path.'assets/plugin/popper/popper.min.js"></script>
                <!-- Bootstrap Core JavaScript-->
                <script src="'.$this->path.'assets/plugin/bootstrap/bootstrap.min.js"></script>
                <!-- Modernizr Core JavaScript-->
                <script src="'.$this->path.'assets/plugin/modernizr/modernizr.js"></script>
                <!-- Animaateheading JavaScript-->
                <script src="'.$this->path.'assets/plugin/animateheading/animateheading.js"></script>
                <!-- Material Design Lite JavaScript-->
                <script src="'.$this->path.'assets/plugin/material/material.min.js"></script>  
                <!-- Material Select Field Script -->
                <script src="'.$this->path.'assets/plugin/material/mdl-selectfield.min.js"></script>
                <!-- Flexslider Plugin JavaScript-->
                <script src="'.$this->path.'assets/plugin/flexslider/jquery.flexslider.min.js"></script>
                <!-- Owl Carousel Plugin JavaScript-->
                <script src="'.$this->path.'assets/plugin/owl_carousel/owl.carousel.min.js"></script>
                <!-- Scrolltofixed Plugin JavaScript-->
                <script src="'.$this->path.'assets/plugin/scrolltofixed/jquery-scrolltofixed.min.js"></script>
                <!-- Magnific Popup Plugin JavaScript-->
                <script src="'.$this->path.'assets/plugin/magnific_popup/jquery.magnific-popup.min.js"></script>
                <!-- WayPoint Plugin JavaScript-->
                <script src="'.$this->path.'assets/plugin/waypoints/jquery.waypoints.min.js"></script>
                <!-- CounterUp Plugin JavaScript-->
                <script src="'.$this->path.'assets/plugin/counterup/jquery.counterup.js"></script>
                <!-- masonry Plugin JavaScript-->
                <script src="'.$this->path.'assets/plugin/masonry_pkgd/masonry.pkgd.min.js"></script>
                <!-- SmoothScroll Plugin JavaScript-->
                <script src="'.$this->path.'assets/plugin/smoothscroll/smoothscroll.min.js"></script>
                <!-- Sweetalert Plugin -->
                <script src="'.$this->path.'assets/plugin/sweetalert/sweetalert.js"></script>
                <!-- js del creador de paginas -->
                <script src="'.$this->path.'modules/main/jsPages/pages.js"></script>
                    
                <!-- Recapcha -->
                <script src="https://www.google.com/recaptcha/api.js?render=6LdoC-gUAAAAADi7iGr_b8WtxMijj24V8v-dAtB-"></script>
                
                <!--Custom JavaScript-->
                <script src="'.$this->path.'general/js/general.js"></script>
                <script src="'.$this->path.'dist/js/custom.js"></script>
                
                ');
                
                if(isset($this->dataTheme["css"]) and $this->dataTheme["css"]<>''){
                        
                    $html.='<link rel="stylesheet" href="'.$this->path.'dist/css/style-'.$this->dataTheme["css"].'.css">';
                }
                if($this->dataPage["id"]==1000){
                    $html.='<!-- js del administrador de paginas -->
                            <script src="'.$this->path.'modules/admin/jsPages/admin.js"></script>
                                <!-- js del migraciones de base de datos -->
                            <script src="'.$this->path.'modules/admin/jsPages/migrations.js"></script>';
                }
                return($html);
    }
    
    
    public function get_footer() {
        $html='<!-- Start Footer Section -->
                    <footer id="footer">
                        <div class="layer-stretch">
                            <!-- Start main Footer Section -->
                            <div class="row layer-wrapper">
                                <div class="col-md-6 footer-block">
                                    <div class="footer-ttl"><p>Información Básica</p></div>
                                    <div class="footer-container footer-a">
                                        <div class="tbl">
                                            <div class="tbl-row">
                                                <div class="tbl-cell"><i class="fa fa-map-marker"></i></div>
                                                <div class="tbl-cell">
                                                    <p class="paragraph-medium paragraph-white">
                                                        '.$this->dataClient["Direccion"].'<br />
                                                        
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="tbl-row">
                                                <div class="tbl-cell"><i class="fa fa-phone"></i></div>
                                                <div class="tbl-cell">
                                                    <p class="paragraph-medium paragraph-white">'.$this->dataClient["Telefono"].'</p>
                                                </div>
                                            </div>
                                            <!--
                                            <div class="tbl-row">
                                                <div class="tbl-cell"><i class="fa fa-envelope"></i></div>
                                                <div class="tbl-cell">
                                                    <p class="paragraph-medium paragraph-white">'.$this->dataClient["Email"].'</p>
                                                </div>
                                            </div>
                                             -->
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 footer-block">
                                    <div class="footer-ttl"><p>Contáctanos</p></div>
                                    <div class="footer-container footer-c">
                                        <div class="footer-subscribe">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label form-input">
                                                <input class="mdl-textfield__input" type="text" id="nameContact">
                                                <label class="mdl-textfield__label" for="subscribe-email">Tú Nombre</label>
                                                
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label form-input">
                                                <input class="mdl-textfield__input" type="text" id="phoneContact">
                                                <label class="mdl-textfield__label" for="subscribe-email">Tú Teléfono</label>
                                                
                                            </div>
                                            
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label form-input">
                                                <input class="mdl-textfield__input" type="text" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" id="subscribe-email">
                                                <label class="mdl-textfield__label" for="subscribe-email">Tú Email</label>
                                                <span class="mdl-textfield__error">Por favor digita un Email válido!</span>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label form-input">
                                                <textarea class="mdl-textfield__input" type="text" id="mensageContact"></textarea>
                                                
                                                
                                                <label class="mdl-textfield__label" for="subscribe-email">Qué deseas decirnos?</label>
                                                
                                            </div>
                                            <div class="footer-subscribe-button">
                                                <button id="btnSendContact" data-local_id="'.$this->dataClient["ID"].'" class="mdl-button mdl-js-button mdl-js-ripple-effect button button-primary">Enviar</button>
                                            </div>
                                        </div>
                                        <ul class="social-list social-list-colored footer-social">';
                                        $sql="SELECT * FROM clients_socials WHERE client_id='".$this->dataClient["ID"]."'";
                                        $query= $this->obCon->Query($sql);
                                        while($dataSocials=$this->obCon->FetchAssoc($query) ){
                                            $html.='<li>
                                                    <a href="'.$dataSocials["link"].'" target="_blank" id="footer-facebook" class="'.$dataSocials["icon"].'"></a>
                                                    <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-facebook">'.$dataSocials["name"].'</span>
                                                </li>';
                                        }
                                        $html.='      
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End main Footer Section -->
                        <!-- Start Copyright Section -->
                        <div id="copyright">
                            <div class="layer-stretch">
                                <div class="paragraph-medium paragraph-white">'.date("Y").' © Techno Soluciones SAS ALL RIGHTS RESERVED.</div>
                            </div>
                        </div><!-- End of Copyright Section -->
                    </footer><!-- End of Footer Section -->';
                                        
        return($html);                                
                                        
    }
    
    
    /**
     * Dibuja el inicio de la pagina
     * @param type $Title ->Titulo de la Pagin
     */
    public function get_page() {
        $html=$this->get_html_init("es");
        $keywords=$this->dataClient["keywords"];
        $favicon="clients/".$this->dataClient["ID"].'/images/favicon.png';
        if(!file_exists($favicon)){
            $favicon="images/favicon.png";
        }
        
        $html.= $this->get_heads($this->dataClient["title_page"],$keywords,$favicon);
        $html.="<body>";
        if($this->dataClient["virtual_shop"]>0){
            $html.=$this->get_login_user_client();        
            $html.=$this->get_modal("modal_virtual_shop", "Domi", "divModal");
            $html.=$this->get_IconWhats("Hola te encontré en Domi y quería ");
            $html.=$this->get_ShoppingCar();
            $html.=$this->get_IconLogin();
        }
        $html.='<div class="wrapper">';
        //if($this->dataPage["header_enabled"]==1){
            $html.=$this->get_headerGeneral(); 
        //}
        
        $html.='<div id="divDrawPage">';
        
        $html.=$this->get_slider(); 
        
        $html.='<div id="divDrawSections">';
        
        $html.= $this->get_sections();
        
        $html.='</div>';
        
        $html.='</div>';
        if($this->dataPage["id"]==3){//si se solicita la tienda virtual
            $html.=($this->get_virtual_shop());
        }
        
        $html.=$this->get_footer();
        
        $html.='</div>'; //end wrapper
        
        //$html.=$this->get_JSGeneral();
        
        return($html);
    }
    
    public function get_sections() {
        $sql="SELECT * FROM clients_has_sections WHERE client_id='".$this->dataClient["ID"]."' AND page_id='".$this->dataPage["id"]."' AND status_section=1 ORDER BY order_section,id ASC";
        $query=$this->obCon->Query($sql);
        $html="";
        while($dataSection=$this->obCon->FetchAssoc($query)){
            $section_id=$dataSection["pages_sections_id"];
            $sql="SELECT text_content FROM web_sections_content WHERE section_id='$section_id'";
            $dataHtml=$this->obCon->FetchAssoc($this->obCon->QueryExterno($sql, HOST, USER, PW, $this->dataClient["db"], ""));
            $html.=($dataHtml["text_content"]);
        }
        if($html==''){
            $html.='<!-- Start About Section -->
        <div class="about">
            <div class="layer-stretch">
                <div class="layer-wrapper">
                    <div class="layer-ttl"><h4><span class="text-primary">Bienvenid@ a Nuestra Tienda Virtual</span> '.$this->dataClient["Nombre"].'</h4></div>
                    <div class="layer-sub-ttl">Estamos encantados de que nos visites.</div>
                    <div class="row pt-4">
                        <div class="col-md-5">
                            <img class="img-fluid" src="'.$this->path.str_replace("../", "", $this->imageLogoLocal).'" alt="">
                        </div>
                        <div class="col-md-7">
                            <div class="about-container">
                                <span class="paragraph-black">'.$this->dataClient["Descripcion"].'</span>

                                <div class="skills mt-3">
                                    <p class="font-14">Disponibilidad<span class="badge badge-primary badge-pill float-right">100%</span></p>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-striped bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="skills mt-3">
                                    <p class="font-14">Confianza<span class="badge badge-success badge-pill float-right">100%</span></p>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="skills mt-3">
                                    <p class="font-14">Seguridad<span class="badge badge-secondary badge-pill float-right">100%</span></p>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-striped bg-secondary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="skills mt-3">
                                    <p class="font-14">Rapidez<span class="badge badge-dark badge-pill float-right">100%</span></p>
                                    <div class="progress progress-xs">
                                        <div class="progress-bar progress-bar-striped bg-dark" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- End of About Section -->';
        }
        return($html);
    }
    
    public function get_contentPage() {
        $html='<div id="divDrawPage">';
        
        $html.=$this->get_slider(); 
        
        $html.='<div id="divDrawSections">';
        
        $html.= $this->get_sections();
        
        $html.='</div>';
        
        $html.='</div>';
        
        return($html);
    }
    
    public function addJsModule($jsPage) {
        print('<script src="'.$this->path.$jsPage.'"></script>');
    }
    
       
    public function get_card_last_product($dataProduct) {
        $arrayImages= explode('|',$dataProduct["Rutas"]);
        $imgProduct=$this->path.str_replace("../", "", $arrayImages[0]);
        
        foreach ($dataProduct as $key => $value) {
            $dataProduct[$key]= str_replace('"', "", $dataProduct[$key]);
        }
        $dataProductCard= base64_encode(json_encode($dataProduct));
        $html='<li class="row align-items-center">
                    <div class="col-4 p-0">
                        <div class="img">
                            <img class="tsProductCard" data-dataproduct="'.$dataProductCard.'" data-produc_id="'.$dataProduct["ID"].'" data-local_id="'.$this->dataClient["ID"].'" src="'.$imgProduct.'" alt="" style="cursor:pointer;">
                        </div>
                    </div>
                    <div class="col-8">
                        <h6 class="title"><a href="#">'.ucfirst(strtolower($dataProduct["Nombre"])).'</a></h6>
                        <div class="price">$'.number_format($dataProduct["PrecioVenta"]).'</div>
                        <div class="link">
                            <a ><i class="icon-basket" style="font-size:14px;"></i>Agregar al Carro</a>
                        </div>
                    </div>
                </li>';
        return($html);
    }
    
    public function get_last_products() {
        $html='<div class="panel panel-default eventp-sidebar">
                        <div class="panel-head">
                            <div class="panel-title">
                                <span class="panel-title-text">Últimos Productos</span>
                            </div>
                        </div>
                        <div class="panel-body">
                            <ul class="widget-product">';
        $sql="SELECT t1.*,
                     (SELECT (GROUP_CONCAT(Ruta SEPARATOR '|') )  FROM productos_servicios_imagenes t2 WHERE t2.idProducto=t1.ID ORDER BY Created ASC ) as Rutas 
                 FROM productos_servicios t1 ORDER BY Created DESC LIMIT 10";
        $query= $this->obCon->QueryExterno($sql, HOST , USER, PW, $this->dataClient["db"], "");
        while($dataProduct=$this->obCon->FetchAssoc($query)){
            $html.= $this->get_card_last_product($dataProduct);
        }
        
        $html.='            
                            </ul>
                            </div>
                        
                    </div>';
        
        return($html);
        
    }
    
    public function get_classifications() {
        $html='<div class="panel panel-default eventp-sidebar">
            <div class="panel-head">
                    <div class="panel-title">
                        <span class="panel-title-text">Categoría de Productos o Servicios</span>
                    </div>
                </div>

                    <div class="panel-body">
                  <ul class="category-list">';
        $sql="SELECT t1.ID,t1.Clasificacion,
                        (SELECT COUNT(*) FROM productos_servicios t2 WHERE t2.idClasificacion=t1.ID) AS TotalItems 
                        FROM inventarios_clasificacion t1 WHERE Estado=1";
                $query= $this->obCon->QueryExterno($sql, HOST, USER, PW, $this->dataClient["db"], "");
                while($dataClasified= $this->obCon->FetchAssoc($query)){
                    $html.='<li><a class="mnu_clasificacion" style="font-size:16px" data-local_id="'.$this->dataClient["ID"].'" data-clasificacion_id="'.$dataClasified["ID"].'"><i class="icon-arrow-right"></i>'.ucfirst(strtolower($dataClasified["Clasificacion"])).'</a><span>('.$dataClasified["TotalItems"].')</span></li>';                               
                
                }
        $html.=' </ul>
                </div>
                </div>
            ';
        
        return($html);
    }
    
    public function get_slider_product($dataProduct) {
        $arrayImages= explode('|',$dataProduct["Rutas"]);
        
        $html='<div class="panel panel-default">
                                <div class="panel-head">
                                    <div class="panel-title">'.ucfirst(strtolower($dataProduct["Nombre"])).'</div>
                                </div>
                                <div class="panel-wrapper">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-12 related-product">
                                                <div class="flexslider thumbnail-slider">
                                                    <ul class="slides">';
        foreach ($arrayImages as $key => $ruta) {
            $ruta= str_replace("../", "", $ruta);
            $html.='                            <li data-thumb="'.$this->path.$ruta.'">
                                                    <img src="'.$this->path.$ruta.'" alt="" />
                                                </li>';
        }
        
        
        $html.='                                       
                                                    </ul>
                                                </div>
                                                Referencia: <strong>'.$dataProduct["Referencia"].'</strong><br>
                                                Precio de Venta: <strong>$ '.number_format($dataProduct["PrecioVenta"]).'</strong><br>
                                                Descripción: <strong>'.ucfirst(strtolower($dataProduct["DescripcionLarga"])).'</strong><br>
                                            </div>                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                      ';
    
        return($html);
        
    }
    
    public function get_carousel_1($id,$arrayImages) {
        $htmlImages="";
        $html='<main><div class="container">
                <div class="carousel slide" id="main-carousel" data-ride="carousel" >';
            $html.='<ol class="carousel-indicators">';
                foreach ($arrayImages as $key => $imgProduct) {
                    $imgProduct=$this->path.str_replace("../", "", $imgProduct);
                    $actived="";
                    if($key==0){
                        $actived='active';
                    }
                    $html.='<li data-target="#'.$id.'" data-slide-to="'.$key.'" class="'.$actived.'"></li>';
                    $htmlImages.='<div class="carousel-item '.$actived.'">
					<img class="d-block img-fluid" src="'.$imgProduct.'" alt="">
					
				</div>';
                }
            $html.='</ol><!-- /.carousel-indicators -->';
            
            $html.='<div class="carousel-inner">';
                $html.=$htmlImages;
            
            $html.='</div><!-- /.carousel-inner -->
			
                            <a href="#'.$id.'" class="carousel-control-prev" data-slide="prev">
                                    <span class="carousel-control-prev-icon"></span>
                                    <span class="sr-only" aria-hidden="true">Anterior</span>
                            </a>
                            <a href="#'.$id.'" class="carousel-control-next" data-slide="next">
                                    <span class="carousel-control-next-icon"></span>
                                    <span class="sr-only" aria-hidden="true">Siguiente</span>
                            </a>
                    </div><!-- /.carousel -->
                </div><!-- /.container -->
                
            </main>    
            ';    
            return($html);
                
    }
    
    public function get_card_product($dataProduct) {
        
        $arrayImages= explode('|',$dataProduct["Rutas"]);
        $Telefono=$this->dataClient["Indicativo"].str_replace(" ", "", $this->dataClient["Whatsapp"]);
        $Mensaje="Hola me gustaría obtener más información sobre el producto ".$dataProduct["Nombre"]." de Referencia: ".$dataProduct["Referencia"];
        $html='<div class="panel panel-default" >
                                <div class="panel-head">
                                    <div class="panel-title">
                                        <i class="fa fa-bookmark panel-head-icon font-24"></i>
                                        <span class="panel-title-text" style="font-size:30px;color:black">'.ucfirst(strtolower($dataProduct["Nombre"])).'</span>
                                    </div>
                                    <div class="panel-action">
                                        <a class="" href="https://api.whatsapp.com/send?phone='.$Telefono.'&text='.$Mensaje.'"  >Info <i class="fa fa-info-circle" style="color:#e30aa1;font-size:30px"></i></a>
                                        
                                    </div>  
                                </div>
                                <div class="panel-wrapper">
                                    <div class="panel-body">';
        
        
      
        $html.='<div class="product-card">
                            ';
         $imgProduct=$this->path.str_replace("../", "", $arrayImages["0"]);
         foreach ($dataProduct as $key => $value) {
            $dataProduct[$key]= str_replace('"', "", $dataProduct[$key]);
        }
         $dataProductCard= base64_encode(json_encode($dataProduct));
         $html.='
                    <img class="tsProductCard" data-dataproduct="'.$dataProductCard.'" data-local_id="'.$this->dataClient["ID"].'" src="'.$imgProduct.'" style="box-shadow: 0 0 30px #000000;width:100%;border-top-right-radius:25%;border-bottom-left-radius:25%;cursor:pointer;"  >
                    
                    </img>
                ';
        
                            
                            $html.='
                                
                            
                            <div class="product-details row">
                                <div class="col-md-4">
                                    <h5 class="title">'.ucfirst(strtolower($dataProduct["DescripcionCorta"])).'</h5>
                                    <div class="price" style="font-size:40px;">
                                        $'.number_format($dataProduct["PrecioVenta"]).'
                                    </div>

                                </div>
                                <div class="col-md-8">
                                    
                                    <textarea id="Observaciones_'.$dataProduct["ID"].'" class="form-control" placeholder="Observaciones" style="bottom:0px;position:absolute"></textarea>
                                </div>
                            </div>
                            
                            
                        </div>';
                            
                            
        $html.='        <div class="panel-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                        
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-dark  btn-gradient btn-shadow tsResteCantidad" data-text_id="Cantidad_'.$dataProduct["ID"].'" style="border-top-left-radius:20%;border-bottom-left-radius:20%; "><i class="far fa-arrow-alt-circle-down" style="font-size:25px;"></i></button>
                                                </div>
                                                <input type="number" id="Cantidad_'.$dataProduct["ID"].'" class="form-control" value=1 style="font-size:25px;text-align:center;-webkit-appearance: textfield !important;margin: 0;-moz-appearance:textfield !important;" min=1 max=100>
                                                <div class="input-group-append">
                                                    <button class="btn btn-success btn-gradient btn-shadow tsSumeCantidad" data-text_id="Cantidad_'.$dataProduct["ID"].'" style="border-top-right-radius:20%;border-bottom-right-radius:20%; "><i class="far fa-arrow-alt-circle-up" style="font-size:25px;"></i></button>
                                                </div>
                                            </div>
                                        </div>
                            <div class="col-md-6">
                                
                                    <button id="btnCarAdd_'.$dataProduct["ID"].'" class="form-control btn btn-secondary btn-pill ts-btn-shopping" style="font-size:20px;" data-product_id="'.$dataProduct["ID"].'" data-local_id="'.$this->dataClient["ID"].'" >Agregar <i class="fab fa-opencart" style=""></i></button>

                                
                            </div>
                        </div>
                         </div>
                        </div>
                    </div>
                </div>';                    
        return($html);
    }
    
    public function get_list_products($page=1,$classification="",$search="") {
        $limit=20;
        $condition=" WHERE Estado=1 ";
        if($classification<>''){
            $condition.=" AND idClasificacion='$classification'";
        }
        if($search<>''){
            $arraySearch= explode(" ", $search);
            $condition.=" AND ( ";
            foreach ($arraySearch as $key => $value) {
                if($value<>''){
                    $condition.=" Nombre like '%$value%' OR ";
                }
            }
            $condition=substr($condition, 0, -3); 
            $condition.=" OR Referencia like '%$search%') ";
        }
        
        $sql="SELECT COUNT(ID) as Items FROM productos_servicios $condition"; 
        $query=$this->obCon->QueryExterno($sql, HOST, USER, PW, $this->dataClient["db"], "");
        $dataTotals = $this->obCon->FetchAssoc($query);
        $totals = $dataTotals['Items'];
        $totalPages= ceil($totals/$limit);
        if($page>$totalPages and $totalPages<>0){
            return;
        }
        $init_point = ($page * $limit) - $limit;
        
        $sql="SELECT t1.*,
                     (SELECT (GROUP_CONCAT(Ruta SEPARATOR '|') )  FROM productos_servicios_imagenes t2 WHERE t2.idProducto=t1.ID ORDER BY Created ASC ) as Rutas 
                 FROM productos_servicios t1 $condition ORDER BY Created DESC LIMIT $init_point,$limit;";
        $query= $this->obCon->QueryExterno($sql, HOST , USER, PW, $this->dataClient["db"], "");
        $find=0;
        $html='<div id="divListProducts" class="row">';
                
            while($dataProduct= $this->obCon->FetchAssoc($query)){
                $find=$find+1;
                $html.='<div class="col-md-6">';
                    $html.= $this->get_card_product($dataProduct);
                $html.='</div>';
            }
            
        $html.='<div class="col-md-12">';    
        
        if($find==0){
            $html.='<div class="alert alert-outline alert-icon alert-danger alert-dismissible fade show" role="alert">
                        <div class="alert--icon">
                            <i class="fa fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-text">
                            <strong>Vaya!</strong> Parece que no hay resultados!
                        </div>
                        
                    </div>';
        }
        
        
        if($totals>$limit){
            
                $html.='<div class="input-group">
                        <div class="input-group-prepend">';
                
                if($page<>1){
                    $html.='<button class=" from-control btn btn-primary  btn-gradient btn-shadow loadMoreProducts" style="border-top-left-radius:2%;border-bottom-left-radius:2%; font-size:20px; width:150px;" data-page="'.($page-1).'" data-local_id="'.$this->dataClient["ID"].'"><i class="far fa-arrow-alt-circle-left" style="font-size:25px;"></i> Atrás</button>';
                }
                
                $html.='</div>
                        <input class="form-control input-group-prepend" disabled></input>
                        <div class="input-group-prepend">';
                
                if($page<>$totalPages){
                    $html.='<button  class="from-control btn btn-info btn-gradient btn-shadow loadMoreProducts" style="border-top-right-radius:2%;border-bottom-right-radius:2%;font-size:20px;width:150px; " data-page="'.($page+1).'" data-local_id="'.$this->dataClient["ID"].'" >Siguiente <i class="far fa-arrow-alt-circle-right" style="font-size:25px;"></i></button>';
                
                }
                
                $html.='</div>
                    </div>';
            
            //$html.='<button class="form-control btn btn-primary loadMoreProducts" data-page="'.($page+1).'" data-local_id="'.$this->dataClient["ID"].'" style="font-size:20px;">Muéstrame Más...</button>';
            $html.="</div>";     
        }else{
            if($find>0){
                $html.='<div class="alert alert-outline alert-icon alert-success alert-dismissible fade show" role="alert">
                        <div class="alert--icon">
                            <i class="fa fa-check"></i>
                        </div>
                        <div class="alert-text">
                            <strong>Bien!</strong> Se han cargado todos los productos!
                        </div>
                        
                    </div>';
            }
        }
        $html.='</div>';
        return($html);
    }
    
    public function get_search_products() {
        $html='<div class="panel panel-default eventp-sidebar" style="border-radius:20px;background-color:white">
                        <div class="panel-head">
                            <div class="panel-title">
                                <span class="panel-title-text">Lista de Productos o Servicios</span>
                            </div>
                        </div>
                        <div class="panel-body">';
        $html.='<div class="row align-items-center shop-filter" style="border-radius:20px;background-color:white">
                            <div class="col-sm-12">
                                <div class="search-input"  >
                                    <input id="searchProducts" data-local_id="'.$this->dataClient["ID"].'" type="text" class="searchProducts" placeholder="Buscar...." >
                                    
                                    
                                    <button id="buttonSearchProduct" data-local_id="'.$this->dataClient["ID"].'" class="search-btn"><i class="icon-magnifier"></i></button>';
        $html.='<select id="searchCategories" class="form-control" data-local_id="'.$this->dataClient["ID"].'">';
        $sql="SELECT t1.ID,t1.Clasificacion,
                        (SELECT COUNT(*) FROM productos_servicios t2 WHERE t2.idClasificacion=t1.ID) AS TotalItems 
                        FROM inventarios_clasificacion t1 WHERE Estado=1";
                $query= $this->obCon->QueryExterno($sql, HOST, USER, PW, $this->dataClient["db"], "");
                $html.='<option value="">';
                        $html.='Todas las categorías';
                    $html.='</option>';
                while($dataClasified= $this->obCon->FetchAssoc($query)){
                    $html.='<option value="'.$dataClasified["ID"].'">';
                        $html.=ucfirst(strtolower($dataClasified["Clasificacion"])).' <span>('.$dataClasified["TotalItems"].')</span>';
                    $html.='</option>';
                    //$html.='<li><a class="mnu_clasificacion" style="font-size:16px" data-local_id="'.$this->dataClient["ID"].'" data-clasificacion_id="'.$dataClasified["ID"].'"><i class="icon-arrow-right"></i>'.ucfirst(strtolower($dataClasified["Clasificacion"])).'</a><span>('.$dataClasified["TotalItems"].')</span></li>';                               
                
                }  
                
        $html.='</select>';
        $html.='                </div>
                            </div>
                            
                        </div>'; 
        
        return($html);
        
    }
    
    public function get_virtual_shop() {
        $html="";
        
        switch ($this->dataClient["virtual_shop"]) {
            
            case 1://Dibuja la primer opcion de tienda virtual
                $html.='<!-- Start Page Section -->
                                <div class="shop">
                                    <div class="layer-stretch">
                                        <div class="layer-wrapper pb-20">
                                            <div class="row pt-4">
                                                                                                
                                                        
                                                        
                                                                ';
                                                         
                
               // $html.=$this->get_classifications();
                
                //$html.= $this->get_last_products();
                
                //$html.='</div>'; //Cierra div col4
                $html.='<div class="col-lg-12">';
                
                $html.=$this->get_search_products();
                
                $html.= $this->get_list_products();                               
                
                $html.='</div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End Page Section -->';
                
                $html.='</div>';$html.='</div>';                              
                                              
                                              
                                              
            break;//fin caso 1

            
        }
        return($html);
    }
    
    public function get_modal($id,$Titulo,$idDivContent,$Tipo=2,$buttonClose=1,$htmlBody="") {
        $ClassLarge="";
        if($Tipo==1){
            $ClassLarge="modal-sm";
        }
        if($Tipo==2){
            $ClassLarge="modal-lg";
        }
        if($Tipo==3){
            $ClassLarge="modal-xl";
        }
        $html='<div class="modal fade" id="'.$id.'">
                    <div class="modal-dialog modal-dialog-scrollable '.$ClassLarge.'">
                      <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                          <h1 class="modal-title">'.$Titulo.'</h1>
                          <button type="button" class="close" data-dismiss="modal">×</button>
                        </div>

                        <!-- Modal body -->
                        <div id='.$idDivContent.' class="modal-body" >
                            '.$htmlBody.'
                        </div>';
            if($buttonClose==1){
            $html.='
                        <!-- Modal footer -->
                        <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                        </div>';
            }
            $html.='
                      </div>
                    </div>
                  </div>';
        return($html);
    }
    
    public function get_IconWhats($Mensaje,$Color="green") {
        $Telefono=$this->dataClient["Indicativo"].str_replace(" ","",$this->dataClient["Whatsapp"]);
        return('<a id="IconWhatsApp" class="whats-domi-icon" style="color:'.$Color.';" target="_blank" href="https://api.whatsapp.com/send?phone='.$Telefono.'&text='.$Mensaje.'" ><img src="'.$this->path.'images/whatsapp.png" style="width:60px"></img></a>');
    }
    
    public function get_ShoppingCar($id="aShoppingCar",$idSp="spItemsCar",$idSpTotal="spTotalCar",$Color="#d91d1d") {
        return('<a id="'.$id.'" data-local_id="'.$this->dataClient["ID"].'" class="cart-icon" ><img src="'.$this->path.'images/shoping3.png" style="width:60px;">      
            <span class="cart-icon-sp" id="'.$idSp.'">0</span> 
            <span class="cart-icon-sp-total" id="'.$idSpTotal.'">0</span>     
        </a>');
    }
    
    public function get_IconLogin($id="IconLogin",$Color="green") {
        return('<a id="'.$id.'" class="login-domi-icon fa fa-user-circle"  style="color:'.$Color.';"></a>');
    }
    
    public function get_shop_order($idClientUser) {
        $local_id=$this->dataClient["ID"];
        $sql="SELECT t1.*
                    FROM pedidos t1                     
                    WHERE t1.cliente_id='$idClientUser' AND local_id='$local_id' AND t1.Estado=1"; 
        
        $query=$this->obCon->Query($sql);
        $dataOrder= $this->obCon->FetchAssoc($query);
        $totalOrder=0;
        
        $html='<div class="panel panel-default">
                    <div class="panel-head">
                        <div class="panel-title">Pedido para: <small class="text-muted">'.$this->dataClient["Nombre"].'</small></div>
                    </div>
                    <div class="panel-wrapper">
                        <div class="">
                            
                        ';
        
        $html.='<ul class="menu-cart">'; 
        $idPedido=$dataOrder["ID"];
        $sql="SELECT t1.*,t2.Nombre,(SELECT t3.Ruta FROM productos_servicios_imagenes t3 WHERE t3.idProducto=t1.product_id LIMIT 1) as Ruta  
                         FROM pedidos_items t1 INNER JOIN productos_servicios t2 ON t1.product_id=t2.ID
                         
                            WHERE t1.pedido_id='$idPedido' ";
        $query=$this->obCon->QueryExterno($sql, HOST, USER, PW, $this->dataClient["db"], "");
        
        while($dataItemsOrder=$this->obCon->FetchAssoc($query)){
            $totalOrder=$totalOrder+$dataItemsOrder["Total"];
            $html.='
                    <li class="cart-overview">
                        <a class="row">
                            <div class="col-4 pr-0 cart-img">
                                <img src="'.$this->path.str_replace("../", "", $dataItemsOrder["Ruta"]).'" alt="">
                            </div>
                            <div class="col-8 cart-details">
                                <span class="title">'.$dataItemsOrder["Nombre"].'</span>
                                <span class="price">$'.number_format($dataItemsOrder["ValorUnitario"]).'</span>
                                <span class="qty">Cantidad - '.number_format($dataItemsOrder["Cantidad"]).'</span>
                                <span class="price">$'.number_format($dataItemsOrder["Total"]).'</span>
                                <div class="cart-remove del-item" data-local_id="'.$this->dataClient["ID"].'" data-item_id="'.$dataItemsOrder["ID"].'"  ><i class="icon-close" style="font-size:25px;color:red"></i></div>
                            </div>
                        </a>
                    </li>';
        }
        
                    
        $html.='    <li class="row align-items-center">
                        <div class="col-6">
                            
                        </div>
                        <div class="col-6 text-right">
                            <p class="font-dosis font-20 m-0">Total : $'.number_format($totalOrder).'</p>
                        </div>
                    </li>
                </ul></div>
                    </div>';
        
        if($totalOrder>0){
        $html.='
                    <div class="panel-footer">
                        <div class="row">
                            <div class="col-md-12">  
                                <div class="panel-head">
                                   <div class="panel-title">Dinos quien eres:</div>
                               </div>
                               
                               <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color:#17277c;color:white"><li class="far fa-address-card"></li></span>
                                    </div>
                                    <input type="text" class="form-control" id="NombreCliente" placeholder="Nombre" style="font-size:25px;">
                                </div>
                                <br>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color:#17277c;color:white"><li class="fa fa-home"></li></span>
                                    </div>
                                    <input type="text" class="form-control" id="DireccionCliente" placeholder="Dirección" style="font-size:25px;">
                                </div>
                                <br>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color:#17277c;color:white;"><li class="fa fa-phone"></li></span>
                                    </div>
                                    <input type="number" class="form-control" id="Telefono" placeholder="Teléfono" style="font-size:25px;-webkit-appearance: textfield !important;margin: 0;-moz-appearance:textfield !important;" >
                                </div>
                                
                                
                                <br>
                                <textarea id="ObservacionesPedido" class="form-control" placeholder="Observaciones" style="font-size:25px;"></textarea>
                                <br>
                                <div class="custom-control custom-checkbox custom-checkbox-1 mb-3" >
                                    <input type="checkbox" class="custom-control-input" id="chRegistrarse" >
                                    <label class="custom-control-label" for="chRegistrarse" style="font-size:20px">Deseo registrarme</label>
                                </div>
                                
                                <div id="divRegistrarse" style="display:none;">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text ti-email" style="background-color:#0d2ccc;color:white"></span>
                                        </div>
                                        <input type="mail" class="form-control" id="Email" placeholder="Email" style="font-size:25px;">
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text icon-lock" style="background-color:#0d2ccc;color:white"></span>
                                        </div>
                                        <input type="password" class="form-control" id="Password" placeholder="Contraseña" style="font-size:25px;">
                                    </div>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text icon-lock" style="background-color:#0d2ccc;color:white;"></span>
                                        </div>
                                        <input type="password" class="form-control" id="PasswordConfirm" placeholder="Confirma tu Contraseña" style="font-size:25px;-webkit-appearance: textfield !important;margin: 0;-moz-appearance:textfield !important;" >
                                    </div>
                                </div>
                               
                            </div>   
                            
                            <div class="col-md-6"></div>
                            <div class="col-md-6 text-right">
                                <br>
                                <button id="tsSendOrder" data-pedido_id="'.$idPedido.'" class="btn btn-success btn-pill btn-sm" style="font-size:20px;">Enviar <li class="fab fa-telegram-plane"></li></button>
                                
                            </div>
                        </div>
                    </div>
                </div>';
        
        }
        
        return($html);
    }
    
    public function get_login_user_client() {
        $htmlBody=' 
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text ti-email" style="background-color:#0d2ccc;color:white"></span>
                        </div>
                        <input type="mail" class="form-control" id="emailLogin" placeholder="Email" style="font-size:25px;">
                    </div>
                    <br>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text icon-lock" style="background-color:#0d2ccc;color:white"></span>
                        </div>
                        <input type="password" class="form-control" id="passLogin" placeholder="Contraseña" style="font-size:25px;">
                    </div>
                    
                    <div class="pt-4 text-center">
                        <button id="btnLoginUser" data-type_login="1" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent button button-dark" data-upgraded=",MaterialButton,MaterialRipple">
                            Ingresa
                                <span class="mdl-button__ripple-container">
                                    <span class="mdl-ripple is-animating" style="width: 237.451px; height: 237.451px; transform: translate(-50%, -50%) translate(58px, 28px);">
                                    </span>
                                </span>
                        </button>
                    </div>

                    ';
        
        
        
        $html=$this->get_modal("modalLogin", "Logueate", "div_modal_login_user_client", 3, 0, $htmlBody);
        return($html);
    }
    
    
    public function get_map() {
        $html='<div id="map"></div><iframe id="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.5393313827085!2d-76.30459495422376!3d3.891650978592515!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e39e66f306f6e05%3A0x8f83f20bf093dd2b!2sCl.%206%20Sur%20%2313%2C%20Guadalajara%20de%20Buga%2C%20Valle%20del%20Cauca!5e0!3m2!1ses-419!2sco!4v1589736162194!5m2!1ses-419!2sco" width="600" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>';
    
        return($html);
    }
    
    
//Fin clase    
}
	
	

?>
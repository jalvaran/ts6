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
        $this->dataPage=$this->obCon->DevuelveValores("pages", "id", $page_id);
                
        
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
                        <!-- Custom Main Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'dist/css/ts-style.css">
                    <!-- Custom Main Stylesheet CSS -->
                    <link rel="stylesheet" href="'.$this->path.'dist/css/style.css">';
                    if(isset($this->dataTheme["css"]) and $this->dataTheme["css"]<>''){
                        $html.='
                                <link rel="stylesheet" href="'.$this->path.'dist/css/style-'.$this->dataTheme["css"].'.css">';
                    }
                    
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
                        <a id="menu_'.$dataMenu["id"].'"  class="mdl-button mdl-js-button mdl-js-ripple-effect ts_menu" data-name="'.$dataMenu["menu_name"].'" data-page_id="'.$dataMenu["page_id"].'">'.$dataMenu["menu_name"].'</a>                                    
                    </li>';
        }
        

        $html.=' <li>
                    <a id="btnLogin" class="mdl-button mdl-js-button mdl-js-ripple-effect hdr-search" href="#"><i class="icon-login"></i></a>
                </li>
                <li class="mobile-menu-close"><i class="fa fa-times"></i></li>
                </ul>';
        return($html);
    }
    
    public function get_headerGeneral() {
        $urlLogo=$this->path."clients/".$this->dataClient["ID"]."/images/logo-header.png";
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
        $background_image=$this->path.'clients/'.$this->dataClient["ID"].'/images/slider1.jpg';
        //print($background_image);
        switch ($dataPage["slider_id"]){
            case 1://slider para la pagina 1 E-Comerce 1
                $background_image=$this->path.'images/slider-1.jpg';
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
                                    <h1>Tienda</h1></div>';
                                    
                
                
                $html.='            
                                
                            </div>
                            
                        </div><!-- End Page Title Section -->';
            break;  //Fin caso 2
            
        
        }
        return($html);
        
    }
    
    /**
     * Incluye los JS Generales
     * @return type
     */
    public function get_JSGeneral() {
        return('<!-- Jquery Library 2.1 JavaScript-->
                <script src="'.$this->path.'assets/plugin/jquery/jquery-2.1.4.min.js"></script>
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
                    <!-- Sweetalert Plugin -->
                <script src="'.$this->path.'modules/main/jsPages/pages.js"></script>
                <!--Custom JavaScript-->
                <script src="'.$this->path.'dist/js/custom.js"></script>
                
                ');
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
                                            <div class="tbl-row">
                                                <div class="tbl-cell"><i class="fa fa-envelope"></i></div>
                                                <div class="tbl-cell">
                                                    <p class="paragraph-medium paragraph-white">'.$this->dataClient["Email"].'</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 footer-block">
                                    <div class="footer-ttl"><p>Suscribete</p></div>
                                    <div class="footer-container footer-c">
                                        <div class="footer-subscribe">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label form-input">
                                                <input class="mdl-textfield__input" type="text" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" id="subscribe-email">
                                                <label class="mdl-textfield__label" for="subscribe-email">Tú Email</label>
                                                <span class="mdl-textfield__error">Por favor digita un Email válido!</span>
                                            </div>
                                            <div class="footer-subscribe-button">
                                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect button button-primary">Enviar</button>
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
        $html.= $this->get_heads($this->dataClient["title_page"],$keywords,$favicon);
        $html.="<body>";
        $html.='<div class="wrapper">';
        if($this->dataPage["header_enabled"]==1){
            $html.=$this->get_headerGeneral(); 
        }
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
        
        $html.=$this->get_JSGeneral();
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
        $imgProduct=$this->path.str_replace("../", "", $dataProduct["Ruta"]);
        $html='<li class="row align-items-center">
                    <div class="col-4 p-0">
                        <div class="img">
                            <a href="#"><img src="'.$imgProduct.'" alt=""></a>
                        </div>
                    </div>
                    <div class="col-8">
                        <h6 class="title"><a href="#">'.ucfirst(strtolower($dataProduct["Nombre"])).'</a></h6>
                        <div class="price">$'.number_format($dataProduct["PrecioVenta"]).'</div>
                        <div class="link">
                            <a><i class="icon-basket" style="font-size:14px;"></i>Agregar al Carro</a>
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
                     (SELECT Ruta FROM productos_servicios_imagenes t2 WHERE t2.idProducto=t1.ID ORDER BY Created DESC LIMIT 1 ) as Ruta 
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
        $html='<div class="panel-body">
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
            </div>';
        
        return($html);
    }
    
    public function get_card_product($dataProduct) {
        
        $arrayImages= explode('|',$dataProduct["Rutas"]);
        $html='<div class="panel panel-default" >
                                <div class="panel-head">
                                    <div class="panel-title">
                                        <i class="fa fa-bookmark panel-head-icon font-24"></i>
                                        <span class="panel-title-text" style="font-size:30px;color:black">'.ucfirst(strtolower($dataProduct["Nombre"])).'</span>
                                    </div>
                                    <div class="panel-action">
                                        <a class=""><i class="fa fa-link" style="color:#e30aa1;font-size:24px"></i></a>
                                        
                                    </div>  
                                </div>
                                <div class="panel-wrapper">
                                    <div class="panel-body">';
        
        $html.='<div class="product-card">
                            <div class="product-img">
                                <div class="owl-carousel owl-theme theme-owlslider dots-overlay text-center">';
                                
        foreach ($arrayImages as $key => $imgProduct) {
            $imgProduct=$this->path.str_replace("../", "", $imgProduct);
            $html.='<div class="theme-owlslider-container">
                        <a href="#"><img class="img-responsive" src="'.$imgProduct.'" alt=""></a>
                    </div>';
        }    
                            
                            
                            $html.='
                                </div>
                            </div>
                            
                            <div class="product-details row">
                                <div class="col-md-4">
                                    <h5 class="title">'.ucfirst(strtolower($dataProduct["DescripcionCorta"])).'</h5>
                                    <div class="price" style="font-size:40px;">
                                        $'.number_format($dataProduct["PrecioVenta"]).'
                                    </div>

                                </div>
                                <div class="col-md-8">
                                    
                                    <textarea class="form-control" placeholder="Observaciones" style="bottom:0px;position:absolute"></textarea>
                                </div>
                            </div>
                            
                            
                        </div>';
                            
                            
        $html.='        <div class="panel-footer">
                                    <div class="row">
                                        <div class="col-md-6">
                                        
                                            <div class="input-group">
                                            <div class="input-group-prepend">
                                                <button class="btn btn-dark  btn-gradient btn-shadow" style="border-top-left-radius:20%;border-bottom-left-radius:20%; "><i class="far fa-arrow-alt-circle-down" style="font-size:25px;"></i></button>
                                            </div>
                                            <input type="number" class="form-control" value=1 style="font-size:25px;text-align:center;-webkit-appearance: textfield !important;margin: 0;-moz-appearance:textfield !important;" min=1 max=100>
                                            <div class="input-group-append">
                                                <button class="btn btn-success btn-gradient btn-shadow" style="border-top-right-radius:20%;border-bottom-right-radius:20%; "><i class="far fa-arrow-alt-circle-up" style="font-size:25px;"></i></button>
                                            </div>
                                    </div>
                            </div>
                            <div class="col-md-6">
                                
                                    <button class="form-control btn btn-secondary btn-pill" style="font-size:20px;">Agregar <i class="fab fa-opencart" style=""></i></button>

                                
                            </div>
                        </div>
                         </div>
                        </div>
                    </div>
                </div>';                    
        return($html);
    }
    
    public function get_list_products() {
               
        $sql="SELECT t1.*,
                     (SELECT (GROUP_CONCAT(Ruta SEPARATOR '|') )  FROM productos_servicios_imagenes t2 WHERE t2.idProducto=t1.ID ORDER BY Created ASC ) as Rutas 
                 FROM productos_servicios t1 ORDER BY Created DESC LIMIT 10";
        $query= $this->obCon->QueryExterno($sql, HOST , USER, PW, $this->dataClient["db"], "");
        
        $html='<div id="divListProducts" class="row">';
                
            while($dataProduct= $this->obCon->FetchAssoc($query)){
                $html.='<div class="col-md-12">';
                    $html.= $this->get_card_product($dataProduct);
                $html.='</div>';
            }
            
        $html.='</div>';
        
        return($html);
    }
    
    public function get_search_products() {
        $html='<div class="row align-items-center shop-filter" style="border-radius:20px;background-color:white">
                            <div class="col-sm-12">
                                <div class="search-input">
                                    <input data-local_id="'.$this->dataClient["ID"].'" type="text" class="searchProducts" placeholder="Buscar....">
                                    <button class="search-btn"><i class="icon-magnifier"></i></button>
                                </div>
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
                                                <div class="col-lg-4">
                                                    <div class="panel panel-default eventp-sidebar">
                                                        <div class="panel-head">
                                                            <div class="panel-title">
                                                                <span class="panel-title-text">Categoría de Productos o Servicios</span>
                                                            </div>
                                                        </div>
                                                        
                                                                ';
                                                         
                
                $html.=$this->get_classifications();
                
                $html.= $this->get_last_products();                                   
                $html.='</div> 
                        <div class="col-lg-8">';
                
                $html.=$this->get_search_products();
                $html.= $this->get_list_products();                               
                
                $html.='</div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End Page Section -->';
                
                                              
                                              
                                              
                                              
            break;//fin caso 1

            
        }
        return($html);
    }
    
    
    
//Fin clase    
}
	
	

?>
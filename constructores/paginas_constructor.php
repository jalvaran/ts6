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
    public  $dataClient;    
    public  $dataTheme;
    public  $dataPage;
    public  $obCon;
    /**
     * constructor de la clase 
     * @param type $client_id -> id del cliente
     */
    function __construct($client_id,$page_id=1){
        $this->path="";
        $arrayPath= explode("/", $_SERVER['REQUEST_URI']);
        if(isset($arrayPath[2])){
            for($i=3;$i<(count($arrayPath));$i++){
                $this->path.="../";
            }
        }
        
        if($client_id==""){
            $client_id=1;
        }
        $this->obCon=new conexion($client_id);
        $this->client_id=$client_id;      
        $this->dataClient=$this->obCon->DevuelveValores("clients", "id", $client_id);
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
        $sql="SELECT * FROM menu WHERE client_id ='".$this->dataClient["id"]."' ORDER BY order_menu,id ASC";        
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
        $urlLogo=$this->path."clients/".$this->dataClient["id"]."/images/logo-header.png";
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
        $dataSliders=$this->obCon->DevuelveValores("clients_slider", "client_id", $this->dataClient["id"]);
        $dataPage=$this->obCon->DevuelveValores("pages", "id", $page_id);
        $html="";
        switch ($dataPage["id"]){
            case 1://slider para la pagina 1 E-Comerce
                $html.='<div id="slider" class="slider-transparent slider-half">
                    <div class="flexslider slider-wrapper">
                        <ul class="slides">
                            <li>
                                <div class="slider-backgroung-image" style="background-image: url('.$this->path.'clients/'.$this->dataClient["id"].'/images/slider1.jpg);">

                                    <div class="layer-stretch">
                                        <div class="slider-info">
                                            <h1 style="'.$dataSliders["style_text"].'">'.utf8_encode($dataSliders["title"]).'</h1>
                                            <p style="'.$dataSliders["style_text"].'">'.utf8_encode($dataSliders["paragraph"]).'</p>
                                            <div class="slider-button">
                                                <a id="'.$dataSliders["button_id"].'" class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored mdl-js-ripple-effect button button-primary button-pill">'.utf8_encode($dataSliders["button_value"]).'</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div><!-- End Slider Section -->';
            break;    
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
                <script src="'.$this->path.'dist/js/custom.js"></script>');
    }
    
    
    public function get_footer() {
        return('<!-- Start Footer Section -->
                    <footer id="footer">
                        <div class="layer-stretch">
                            <!-- Start main Footer Section -->
                            <div class="row layer-wrapper">
                                <div class="col-md-4 footer-block">
                                    <div class="footer-ttl"><p>Basic Info</p></div>
                                    <div class="footer-container footer-a">
                                        <div class="tbl">
                                            <div class="tbl-row">
                                                <div class="tbl-cell"><i class="fa fa-map-marker"></i></div>
                                                <div class="tbl-cell">
                                                    <p class="paragraph-medium paragraph-white">
                                                        Your office, Building Name<br />
                                                        Street name, Area<br />
                                                        City, Country Pin Code
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="tbl-row">
                                                <div class="tbl-cell"><i class="fa fa-phone"></i></div>
                                                <div class="tbl-cell">
                                                    <p class="paragraph-medium paragraph-white">11122333333</p>
                                                </div>
                                            </div>
                                            <div class="tbl-row">
                                                <div class="tbl-cell"><i class="fa fa-envelope"></i></div>
                                                <div class="tbl-cell">
                                                    <p class="paragraph-medium paragraph-white">hello@yourdomain.com</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 footer-block">
                                    <div class="footer-ttl"><p>Quick Links</p></div>
                                    <div class="footer-container footer-b">
                                        <div class="tbl">
                                            <div class="tbl-row">
                                                <ul class="tbl-cell">
                                                    <li><a href="index.html">Home</a></li>
                                                    <li><a href="about-1.html">About</a></li>
                                                    <li><a href="event-1.html">Event</a></li>
                                                    <li><a href="contact-1.html">Contact</a></li>
                                                    <li><a href="portfolio-1.html">Portfolio</a></li>
                                                    <li><a href="#">Link</a></li>
                                                </ul>
                                                <ul class="tbl-cell">
                                                    <li><a href="signin.html">Sign In</a></li>
                                                    <li><a href="signup.html">Sign Up</a></li>
                                                    <li><a href="services-1.html">Services</a></li>
                                                    <li><a href="Blogs-1.html">Blogs</a></li>
                                                    <li><a href="Blog-1.html">Blog</a></li>
                                                    <li><a href="team-1.html">Team</a></li>
                                                    <li><a href="faq.html">Faq</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 footer-block">
                                    <div class="footer-ttl"><p>Newsletter</p></div>
                                    <div class="footer-container footer-c">
                                        <div class="footer-subscribe">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label form-input">
                                                <input class="mdl-textfield__input" type="text" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" id="subscribe-email">
                                                <label class="mdl-textfield__label" for="subscribe-email">Your Email</label>
                                                <span class="mdl-textfield__error">Please Enter Valid Email!</span>
                                            </div>
                                            <div class="footer-subscribe-button">
                                                <button class="mdl-button mdl-js-button mdl-js-ripple-effect button button-primary">Submit</button>
                                            </div>
                                        </div>
                                        <ul class="social-list social-list-colored footer-social">
                                            <li>
                                                <a href="#" target="_blank" id="footer-facebook" class="fab fa-facebook"></a>
                                                <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-facebook">Facebook</span>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank" id="footer-twitter" class="fab fa-twitter"></a>
                                                <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-twitter">Twitter</span>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank" id="footer-google" class="fab fa-google"></a>
                                                <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-google">Google</span>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank" id="footer-instagram" class="fab fa-instagram"></a>
                                                <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-instagram">Instagram</span>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank" id="footer-youtube" class="fab fa-youtube"></a>
                                                <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-youtube">Youtube</span>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank" id="footer-linkedin" class="fab fa-linkedin"></a>
                                                <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-linkedin">Linkedin</span>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank" id="footer-flickr" class="fab fa-flickr"></a>
                                                <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-flickr">Flickr</span>
                                            </li>
                                            <li>
                                                <a href="#" target="_blank" id="footer-rss" class="fab fa-rss"></a>
                                                <span class="mdl-tooltip mdl-tooltip--top" data-mdl-for="footer-rss">Rss</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End main Footer Section -->
                        <!-- Start Copyright Section -->
                        <div id="copyright">
                            <div class="layer-stretch">
                                <div class="paragraph-medium paragraph-white">2017 © Pepdev ALL RIGHTS RESERVED.</div>
                            </div>
                        </div><!-- End of Copyright Section -->
                    </footer><!-- End of Footer Section -->');
    }
    
    
    /**
     * Dibuja el inicio de la pagina
     * @param type $Title ->Titulo de la Pagin
     */
    public function get_page() {
        $html=$this->get_html_init("es");
        $keywords=$this->dataClient["keywords"];
        $favicon="clients/".$this->dataClient["id"].'/images/favicon.png';
        $html.= $this->get_heads($this->dataClient["title"],$keywords,$favicon);
        $html.="<body>";
        $html.='<div class="wrapper">';
        if($this->dataPage["header_enabled"]==1){
            $html.=$this->get_headerGeneral(); 
        }
        
        $html.=$this->get_slider(); 
        
        $html.='<div id="divDrawSections">';
        $html.='</div>';
        $html.=$this->get_footer();
        
        $html.='</div>'; //end wrapper
        
        $html.=$this->get_JSGeneral();
        return($html);
    }
   
    public function addJsModule($jsPage) {
        print('<script src="'.$jsPage.'"></script>');
    }
    
//Fin clase    
}
	
	

?>
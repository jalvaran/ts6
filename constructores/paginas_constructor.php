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
    
    public function get_headerGeneral() {
        $urlLogo=$this->path."clients/".$this->dataClient["id"]."/images/logo-header.png";
        return('<header id="header" class="header-'.$this->dataClient["header_class"].'">
            <div class="layer-stretch hdr">
                <div class="tbl animated fadeInDown">
                    <div class="tbl-row">
                        <!-- Start Header Logo Section -->
                        <div class="tbl-cell">
                            <a href="#"><img src="'.$urlLogo.'" alt="" style="width:200px"></a>
                        </div><!-- End Header Logo Section -->
                        <div class="tbl-cell hdr-menu">
                            <!-- Start Menu Section -->
                            <ul class="menu">
                                <li class="menu-megamenu-li">
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect">Home <i class="fa fa-chevron-down"></i></a>
                                    <ul class="menu-megamenu menu-megamenu-small">
                                        <li class="row">
                                            <div class="col-lg-4">
                                                <ul>
                                                    <li><a href="index.html">Home Style 1</a></li>
                                                    <li><a href="index-1.html">Home Style 2</a></li>
                                                    <li><a href="index-2.html">Home Style 3</a></li>
                                                    <li><a href="index-3.html">Home Style 4</a></li>
                                                    <li><a href="index-4.html">Home Style 5</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-4">
                                                <ul>
                                                    <li><a href="index-5.html">Home Style 6</a></li>
                                                    <li><a href="index-6.html">Home Style 7</a></li>
                                                    <li><a href="index-7.html">Home Style 8</a></li>
                                                    <li><a href="index-8.html">Home Style 9</a></li>
                                                    <li><a href="index-9.html">Home Style 10</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-4">
                                                <ul>
                                                    <li><a href="index-ecommerce.html">Home E-commerce</a></li>
                                                    <li><a href="index-law.html">Home Law firm</a></li>
                                                    <li><a href="index-property.html">Home Property</a></li>
                                                    <li><a href="index-listing.html">Home Listing</a></li>
                                                </ul>
                                            </div>
                                        </li> 
                                    </ul>
                                </li>
                                <li>
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect">Feature <i class="fa fa-chevron-down"></i></a>
                                    <ul class="menu-dropdown">
                                        <li>
                                            <a>Property</a>
                                            <ul class="menu-dropdown">
                                                <li><a href="property-1.html">Property Listing 1</a></li>
                                                <li><a href="property-2.html">Property Listing 2</a></li>
                                                <li><a href="property-3.html">Property Listing 3</a></li>
                                                <li><a href="property-page.html">Property Page</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a>Page Header</a>
                                            <ul class="menu-dropdown">
                                                <li><a href="page-header-1.html">Page Header 1</a></li>
                                                <li><a href="page-header-2.html">Page Header 2</a></li>
                                                <li><a href="page-header-3.html">Page Header 3</a></li>
                                                <li><a href="page-header-4.html">Page Header 4</a></li>
                                                <li><a href="page-header-5.html">Page Header 5</a></li>
                                                <li><a href="page-header-6.html">Page Header 6</a></li>
                                                <li><a href="page-header-7.html">Page Header 7</a></li>
                                                <li><a href="page-header-8.html">Page Header 8</a></li>
                                                <li><a href="page-header-9.html">Page Header 9</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a>Page Footer</a>
                                            <ul class="menu-dropdown menu-dropdown-left">
                                                <li><a href="page-footer-1.html">Page Footer 1</a></li>
                                                <li><a href="page-footer-2.html">Page Footer 2</a></li>
                                                <li><a href="page-footer-3.html">Page Footer 3</a></li>
                                                <li><a href="page-footer-4.html">Page Footer 4</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a>Side Panel</a>
                                            <ul class="menu-dropdown">
                                                <li><a href="side-panel-1.html">Light Side Panel</a></li>
                                                <li><a href="side-panel-2.html">Dark Side Panel</a></li>
                                                <li><a href="side-panel-3.html">Colored Side Panel</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="popup.html">Popups</a></li>
                                        <li><a href="coming-soon.html">Coming Soon</a></li>
                                        <li><a href="404.html">404 Page Not Found</a></li>
                                        <li><a href="503.html">503 Temporarily Unavailable</a></li>
                                    </ul>
                                </li>
                                <li class="menu-megamenu-li">
                                    <a id="menu-pages" class="mdl-button mdl-js-button mdl-js-ripple-effect">Pages <i class="fa fa-chevron-down"></i></a>
                                    <ul class="menu-megamenu">
                                        <li class="row">
                                            <div class="col-lg-2">
                                                <div class="megamenu-ttl">Service Styles</div>
                                                <ul>
                                                    <li><a href="services-1.html">Services Style 1</a></li>
                                                    <li><a href="services-2.html">Services Style 2</a></li>
                                                    <li><a href="services-3.html">Services Style 3</a></li>
                                                    <li><a href="services-4.html">Services Style 4</a></li>
                                                    <li><a href="services-5.html">Services Style 5</a></li>
                                                    <li><a href="services-6.html">Services Style 6</a></li>
                                                    <li><a href="services-7.html">Services Style 7</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="megamenu-ttl">Team Styles</div>
                                                <ul>
                                                    <li><a href="team-1.html">Team Style 1</a></li>
                                                    <li><a href="team-2.html">Team Style 2</a></li>
                                                    <li><a href="team-3.html">Team Style 3</a></li>
                                                    <li><a href="team-4.html">Team Style 4</a></li>
                                                    <li><a href="team-5.html">Team Style 5</a></li>
                                                    <li><a href="team-6.html">Team Style 6</a></li>
                                                    <li><a href="team-7.html">Team Style 7</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="megamenu-ttl">Shop Styles</div>
                                                <ul>
                                                    <li><a href="shop-1.html">Shop Style 1</a></li>
                                                    <li><a href="shop-2.html">Shop Style 2</a></li>
                                                    <li><a href="shop-3.html">Shop Style 3</a></li>
                                                    <li><a href="shop-4.html">Shop Style 4</a></li>
                                                    <li><a href="shop-5.html">Shop Style 5</a></li>
                                                    <li><a href="shop-6.html">Shop Style 6</a></li>
                                                    <li><a href="shop-7.html">Shop Style 7</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="megamenu-ttl">Shop Styles</div>
                                                <ul>
                                                    <li><a href="shop-8.html">Shop Style 8</a></li>
                                                    <li><a href="shop-9.html">Shop Style 9</a></li>
                                                    <li><a href="shop-10.html">Shop Style 10</a></li>
                                                    <li><a href="shop-page-1.html">Product Page 1</a></li>
                                                    <li><a href="shop-page-2.html">Product Page 2</a></li>
                                                    <li><a href="shop-page-3.html">Product Page 3</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="megamenu-ttl">Info Pages</div>
                                                <ul>
                                                    <li><a href="about-1.html">About Us Style 1</a></li>
                                                    <li><a href="about-2.html">About Us Style 2</a></li>
                                                    <li><a href="about-3.html">About Us Style 3</a></li>
                                                    <li><a href="contact-1.html">Contact Us Style 1</a></li>
                                                    <li><a href="contact-2.html">Contact Us Style 2</a></li>
                                                    <li><a href="gallery-1.html">Gallery Style</a></li>
                                                    <li><a href="faq.html">FAQ</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="megamenu-ttl">Other Pages</div>
                                                <ul>
                                                    <li><a href="event-1.html">Event Style 1</a></li>
                                                    <li><a href="event-2.html">Event Style 2</a></li>
                                                    <li><a href="event-3.html">Event Style 3</a></li>
                                                    <li><a href="event-4.html">Event Style 4</a></li>
                                                    <li><a href="event-page-1.html">Event Details Page</a></li>
                                                    <li><a href="signin.html">Sign In or Login</a></li>
                                                    <li><a href="signup.html">Sign Up or Register</a></li>
                                                </ul>
                                            </div>
                                        </li> 
                                    </ul>
                                </li>
                                <li>
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect">Portfolio <i class="fa fa-chevron-down"></i></a>
                                    <ul class="menu-dropdown">
                                        <li>
                                            <a>Portfolio Default</a>
                                            <ul class="menu-dropdown menu-dropdown-left">
                                                <li><a href="portfolio-1.html">2 Column</a></li>
                                                <li><a href="portfolio-2.html">3 Column</a></li>
                                                <li><a href="portfolio-3.html">4 Column</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a>Portfolio Wide</a>
                                            <ul class="menu-dropdown menu-dropdown-left">
                                                <li><a href="portfolio-4.html">Wide Portfolio</a></li>
                                                <li><a href="portfolio-5.html">No Spacing Portfolio</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a>Portfolio Masonary</a>
                                            <ul class="menu-dropdown menu-dropdown-left">
                                                <li><a href="portfolio-6.html">3 Column Portfolio</a></li>
                                                <li><a href="portfolio-7.html">4 Column Portfolio</a></li>
                                                <li><a href="portfolio-8.html">Wide Portfolio</a></li>
                                                <li><a href="portfolio-9.html">No Spacing Portfolio</a></li>
                                            </ul>
                                        </li>
                                        <li><a href="portfolio-grid.html">Portfolio Grid</a></li>
                                        <li><a href="portfolio-list.html">Portfolio List</a></li>
                                    </ul>
                                </li>
                                <li class="menu-megamenu-li">
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect">Blogs <i class="fa fa-chevron-down"></i></a>
                                    <ul class="menu-megamenu menu-megamenu-medium">
                                        <li class="row">
                                            <div class="col-lg-3">
                                                <div class="megamenu-ttl">Blog Grid Sytle</div>
                                                <ul>
                                                    <li><a href="blogs-1.html">Blog 2 Column</a></li>
                                                    <li><a href="blogs-2.html">Blog 3 Column</a></li>
                                                    <li><a href="blogs-3.html">Blog 4 Column</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="megamenu-ttl">Blog List Sytle</div>
                                                <ul>
                                                    <li><a href="blogs-9.html">Blog List</a></li>
                                                    <li><a href="blogs-10.html">Blog List Left Sidebar</a></li>
                                                    <li><a href="blogs-11.html">Blog List Right Sidebar</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="megamenu-ttl">Blog with Sidebar Sytle</div>
                                                <ul>
                                                    <li><a href="blogs-4.html">Blog 1 Column Right Sidebar</a></li>
                                                    <li><a href="blogs-5.html">Blog 2 Column Right Sidebar</a></li>
                                                    <li><a href="blogs-6.html">Blog 3 Column Right Sidebar</a></li>
                                                    <li><a href="blogs-7.html">Blog 2 Column Left Sidebar</a></li>
                                                    <li><a href="blogs-8.html">Blog 3 Column Left Sidebar</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-2">
                                                <div class="megamenu-ttl">Blog Page</div>
                                                <ul>
                                                    <li><a href="blog-1.html">Blog Detail 1</a></li>
                                                    <li><a href="blog-2.html">Blog Detail 2</a></li>
                                                </ul>
                                            </div>
                                        </li> 
                                    </ul>
                                </li>
                                <li class="menu-megamenu-li">
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect">Components <i class="fa fa-chevron-down"></i></a>
                                    <ul class="menu-megamenu menu-megamenu-small">
                                        <li class="row">
                                            <div class="col-lg-4">
                                                <div class="megamenu-ttl">Components Link</div>
                                                <ul>
                                                    <li><a href="components/accordions.html"><i class="icon-plus mr-2"></i> Accordions</a></li>
                                                    <li><a href="components/alerts.html"><i class="icon-exclamation mr-2"></i> Alerts</a></li>
                                                    <li><a href="components/badges.html"><i class="icon-badge mr-2"></i> Badge</a></li>
                                                    <li><a href="components/buttons.html"><i class="icon-star mr-2"></i> Buttons</a></li>
                                                    <li><a href="components/cards.html"><i class="icon-credit-card mr-2"></i> Cards</a></li>
                                                    <li><a href="components/dropcap.html"><i class="icon-bulb mr-2"></i> Dropcap</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="megamenu-ttl">Components Link</div>
                                                <ul>
                                                    <li><a href="components/grid.html"><i class="icon-grid mr-2"></i> Grid</a></li>
                                                    <li><a href="components/lists.html"><i class="icon-list mr-2"></i> Lists</a></li>
                                                    <li><a href="components/panels.html"><i class="icon-speedometer mr-2"></i> Panels</a></li>
                                                    <li><a href="components/pricingtable.html"><i class="fa fa-table mr-2"></i> Pricing Table</a></li>
                                                    <li><a href="components/progressbars.html"><i class="icon-chart mr-2"></i> Progressbars</a></li>
                                                    <li><a href="components/sliders.html"><i class="icon-compass mr-2"></i> Sliders</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="megamenu-ttl">Components Link</div>
                                                <ul>
                                                    <li><a href="components/social-list.html"><i class="icon-list mr-2"></i> Social List</a></li>
                                                    <li><a href="components/tables.html"><i class="fa fa-table mr-2"></i> Tables</a></li>
                                                    <li><a href="components/tabs.html"><i class="icon-cursor mr-2"></i> Tabs</a></li>
                                                    <li><a href="components/typography.html"><i class="icon-tag mr-2"></i> Typography</a></li>
                                                </ul>
                                            </div>
                                        </li> 
                                    </ul>
                                </li>
                                
                                <li>
                                    <a class="mdl-button mdl-js-button mdl-js-ripple-effect hdr-search" href="#"><i class="icon-login"></i></a>
                                </li>
                                <li class="mobile-menu-close"><i class="fa fa-times"></i></li>
                            </ul><!-- End Menu Section -->
                            <div id="menu-bar"><a><i class="fa fa-bars"></i></a></div>
                        </div>
                    </div>
                </div>
                
            </div>
        </header><!-- End Header Section -->');
    }
    
    public function get_slider($page_id) {
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
    public function PageInit($title='',$keywords='') {
        $html=$this->get_html_init("es");
        if($keywords==''){
            $keywords=$this->dataClient["keywords"];
        }
        $favicon="clients/".$this->dataClient["id"].'/images/favicon.png';
        $html.= $this->get_heads($this->dataClient["title"],$keywords,$favicon);
        $html.="<body>";
        $html.='<div class="wrapper">';
        if($this->dataPage["header_enabled"]==1){
            $html.=$this->get_headerGeneral(); 
        }
        
        $html.='<div id="divContentTS6" >';
        $html.=$this->get_slider(1);
        
        print($html);
    }
    
    
    
    /**
     * Fin a de la pagina
     */
    public function PageFin() {
        $html='</div>';
        $html.=$this->get_footer();
        $html.='</div>';
        
        $html.=$this->get_JSGeneral();
        
        print($html);
    }
    
//Fin clase    
}
	
	

?>
<?php
    $path="";
    $arrayPath= explode("/", $_SERVER['REQUEST_URI']);
    if(isset($arrayPath[2])){
        for($i=3;$i<(count($arrayPath));$i++){
            $path.="../";
        }
    }
    //print_r($arrayPath);
    $urlHome=$path."../".$arrayPath[1];
    print('

    <!DOCTYPE html>
    <html lang="en">
    <head>
            <meta charset="UTF-8">
            <!-- Site Title -->
            <title>404 - Pagina no encontrada</title>
            <!-- Favicon Icon -->
            <link rel="icon" type="image/x-icon" href="'.$path.'images/favicon.png" />
        <!-- Custom Main Stylesheet CSS -->
        <link rel="stylesheet" href="'.$path.'dist/css/style.css">
    </head>
    <body>
            <!-- Start Not Found Section -->
            <div class="notfound-wrapper">
                    <h1 class="notfound-ttl">404</h1>
                    <p class="notfound-tag-1">Página no encontrada!!!</p>
                    <span class="notfound-tag-2">lo que intentas visitar no existe...</span>
                    <div class="notfound-link">
                            <a href="javascript:window.location=`'.$urlHome.'`;" class="btn btn-primary"><span>Ir al Inicio</span><i class="fa fa-home ml-2"></i></a>

                    </div>

            </div><!-- End Not Found Section -->

    </body>
    </html>

    ');
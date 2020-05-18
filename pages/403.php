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
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>TS6 | Error 403</title>
        <link rel="icon" type="image/x-icon" href="'.$path.'images/favicon.png">
        <!-- Custom Stylesheet -->
        <link rel="stylesheet" href="'.$path.'dist/css/style.css" />
    </head>
    <body>
    <!-- Page Wrapper -->
    <div class="notfound-wrapper">
        <h1 class="notfound-ttl">403</h1>
        <p class="notfound-tag-1">PROHIBIDO!!!</p>
        <span class="notfound-tag-2">usted necesita permisos para acceder a esta página...</span>
        <div class="notfound-link">');
    
    print('<a href="javascript:window.location=`'.$urlHome.'`;" class="btn btn-primary"><span>Ir al Inicio</span><i class="fa fa-home ml-2"></i></a>');
    
    print('</div>
    </div>
    <!-- Include js files -->
    <!-- jQuery Library -->
    <script type="text/javascript" src="'.$path.'assets/plugin/jquery/jquery-3.3.1.min.js"></script>
    <!-- Popper Plugin -->
    <script type="text/javascript" src="'.$path.'assets/plugin/popper/popper.min.js"></script>
    <!-- Bootstrap Framework -->
    <script type="text/javascript" src="'.$path.'assets/plugin/bootstrap/bootstrap.min.js"></script>
</body>
</html>');
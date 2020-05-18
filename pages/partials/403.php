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
    
    
    <!-- Page Wrapper -->
    <div class="notfound-wrapper">
        <h1 class="notfound-ttl">403</h1>
        <p class="notfound-tag-1">PROHIBIDO!!!</p>
        <span class="notfound-tag-2">usted necesita permisos para acceder a esta página...</span>
        <div class="notfound-link">');
    
    
    print('</div>
    </div>
    ');
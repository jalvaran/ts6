/**
 * Controlador para dibujar las paginas
 * JULIAN ANDRES ALVARAN
 * 2020-05-04
 */
var isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
    }
};

var terminal_type=1;

if(isMobile.any()) {
  terminal_type=2;
}

var pathname = window.location.pathname;

var arrayPath = pathname.split('/');

var myPath=""
for(i=4;i<=arrayPath.length;i++){
    myPath=myPath+"../";
}

var URLdomain = window.location.host;
var URLAjax="http://"+URLdomain+"/"+arrayPath[1]+"/";
var Page=1;
var local_id=1;

$('.ts_menu').on('click',function () {
    drawPageContent($(this).data("page_id"),$(this).data("local_id"));
    if(terminal_type==2){//Is mobile
        $('.mobile-menu-close').click();
    }
    
});

getIdClientUser();

function getIdClientUser(){
    
    var form_data = new FormData();
        form_data.append('Accion', '8'); 
        urlQuery=URLAjax+'process';        
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            var respuestas = data.split(';');
            if(respuestas[0]=="OK"){   
                console.log("Entra a verificar "+respuestas[1]);
                idClientUser=respuestas[1];
                ActualizarTotalItemsCarro(idClientUser);
            }else{
                
                idClientUser = $.cookie("idClientUser"); 
                
                if(idClientUser==undefined){        
                    idClientUser=uuid.v4();
                    $.cookie("idClientUser",idClientUser,{expires: 9999});
                    
                }
                //ActualizarTotalItemsCarro(idClientUser);
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
      
    
}

function addItemShoppingCar(local_id,product_id){
    
    
    var idBoton='btnCarAdd_'+product_id;
    document.getElementById(idBoton).disabled=true;
    var idObservaciones="Observaciones_"+product_id;
    var idCantidad="Cantidad_"+product_id;
    var Observaciones=document.getElementById(idObservaciones).value;  
    var Cantidad=document.getElementById(idCantidad).value;  
    
    var form_data = new FormData();
        form_data.append('ActionShopping', '1'); 
        form_data.append('user_id', idClientUser);
        form_data.append('local_id', local_id);
        form_data.append('product_id', product_id);
        form_data.append('Observaciones', Observaciones);
        form_data.append('Cantidad', Cantidad);
        
        
        urlQuery=URLAjax+'process';        
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function() {
            $('#loader').fadeIn();
        },
        complete: function(){
            $('#loader').fadeOut();
        },
        success: function(data){
            document.getElementById(idBoton).disabled=false;
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.success(respuestas[1],1000);
                document.getElementById('spItemsCar').innerHTML=respuestas[2];
                document.getElementById('spTotalCar').innerHTML=respuestas[4];
                
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                alertify.alert(data);                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;   
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}

function ActualizarTotalItemsCarro(user_id){
    
    var form_data = new FormData();
        form_data.append('Accion', '2'); 
        form_data.append('user_id', user_id);
                
        $.ajax({
        url: './procesadores/main.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                if(respuestas[1]==''){
                    respuestas[1]=0;
                }
                document.getElementById('spItemsCar').innerHTML=respuestas[1];
                document.getElementById('spTotalCar').innerHTML=respuestas[3];
                if(document.getElementById('spTotalFormPedido')){
                    document.getElementById('spTotalFormPedido').innerHTML=respuestas[3];
                }
                
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                
            }else{
                alertify.alert(data);                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}

function drawPageContent(page_id,local_id){
    var idDiv="divDrawPage";
    urlQuery=URLAjax+'views';    
    var form_data = new FormData();
        form_data.append('actionPagesDraw', 2);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('page_id', page_id);
        form_data.append('local_id', local_id);
        form_data.append('myPath', myPath);
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        beforeSend: function() {
            $('#loader').fadeIn();
        },
        complete: function(){
            $('#loader').fadeOut();
        },
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            
            $(".flexslider").flexslider();
            $('#btn_slider1').on('click',function () {
                drawPageContent($(this).data("page_id"),$(this).data("local_id")); 
            });
            
            goUpPage();
            
            if(page_id==3){
                add_events_virtual_shop();
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

function goUpPage(){
    $("html, body").animate({ scrollTop: $("#searchProducts").offset().top }, 600);
    return false;
}

function buttonUpCreate(){
        
    $('body').prepend('<div class="btnupper">' +
        '<div class="btnupper-wrapper">' +
        '<div class="btnupper-handle" ><i class="fa fa-arrow-alt-circle-up"></i></div></div></div>');

    $('.btnupper').fadeOut();

    $(window).scroll(function(){
       if ($(this).scrollTop() > 100) {
            $('.btnupper').fadeIn();
       } else {
            $('.btnupper').fadeOut();
       }
    });
    
    $('.btnupper').on('click',function () {
        goUpPage();
        
    });
}

function spinnerCreate(){
        
    $('body').prepend('<div class="m-2 d-inline-block spinnerloadpage"><div id="loader" class="spinner-4"><div class="bg-info"></div><div class="bg-info"></div><div class="bg-info"></div></div></div>');
    //$('body').prepend('<div class="m-2 d-inline-block spinnerloadpage"><div id="loader" class="spinner-5 info"></div></div>');
    $('#loader').fadeOut();    

}
function add_events_virtual_shop(){
    
    $('.tsProductCard').on('click',function () {
        drawSliderProduct($(this).data("local_id"),$(this).data("dataproduct"));
    });
            
    $('.loadMoreProducts').on('click',function () {
        drawMoreProducts($(this).data("page"),$(this).data("local_id"));         
        $(this).remove();
    });
    
    $('.tsResteCantidad').on('click',function () {
        CambieCantidad($(this).data("text_id"),"-");         
        
    });
    
    $('.tsSumeCantidad').on('click',function () {
        CambieCantidad($(this).data("text_id"),"+"); 
    });
    
    $('.ts-btn-shopping').on('click',function () {
        addItemShoppingCar($(this).data("local_id"),$(this).data("product_id")); 
    });
    
    
    
}

function CambieCantidad(idCaja,operation){
    
    var Cantidad=$('#'+idCaja).val();
    
    if(operation=='+'){
        
        $('#'+idCaja).val(Number($('#'+idCaja).val())+1);
    }
    if(operation=='-' && Cantidad>1){
        $('#'+idCaja).val(Number($('#'+idCaja).val())-1);
    }
}

$(document).ready(add_events_virtual_shop);

$(document).ready(function(){
    $("#searchProducts").keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code==13){
            drawMoreProducts(1,$(this).data("local_id"));   
        }
    });
    
    $('#searchCategories').on('change',function () {
        drawMoreProducts(1,$(this).data("local_id"));
    });
    
    $('#buttonSearchProduct').on('click',function () {
        drawMoreProducts(1,$(this).data("local_id"));
    });
    
    $('.tsProductCard').on('click',function () {
        drawSliderProduct($(this).data("local_id"),$(this).data("dataproduct"));
    });
    
} );

function drawSliderProduct(local_id,dataProduct){
    console.log(dataProduct)
    openModal('modal_virtual_shop');
    var idDiv="divModal";
       
    urlQuery=URLAjax+'views';    
    var form_data = new FormData();
        form_data.append('actionPagesDraw', 4);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        
        form_data.append('local_id', local_id);
        form_data.append('myPath', myPath);
        form_data.append('dataProduct', dataProduct);
        
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        beforeSend: function() {
            $('#loader').fadeIn();
            
        },
        complete: function(){
            $('#loader').fadeOut();
        },
        success: function(data){
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            
            $('.flexslider').flexslider();
            $('.slider-wrapper').flexslider({
                animation: "fade",
                animationLoop: true,
                pauseOnHover: true,
                keyboard: true,
                controlNav: false
            });
            $('.slider-height').removeClass();
            $('.thumbnail-slider').flexslider({
                animation: "slide",
                controlNav: "thumbnails"
            });
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

function drawMoreProducts(page,local_id){
    var idDiv="divListProducts";
    var category=$('#searchCategories').val();
    var search_product=$('#searchProducts').val();
    
    urlQuery=URLAjax+'views';    
    var form_data = new FormData();
        form_data.append('actionPagesDraw', 3);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('page', page);
        form_data.append('local_id', local_id);
        form_data.append('myPath', myPath);
        form_data.append('category', category);
        form_data.append('search_product', search_product);
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        beforeSend: function() {
            $('#loader').fadeIn();
            //$('.ts-gallery').removeClass('ts-gallery');
        },
        complete: function(){
            $('#loader').fadeOut();
        },
        success: function(data){
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            
            add_events_virtual_shop();
            goUpPage();
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}
spinnerCreate();
buttonUpCreate();

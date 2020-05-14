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
                drawPageContent($(this).data("page_id")); 
            });
            goUpPage();
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

function goUpPage(){
    $("html, body").animate({ scrollTop: 0 }, 600);
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
spinnerCreate();
buttonUpCreate();

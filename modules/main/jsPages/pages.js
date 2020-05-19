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
        urlQuery=URLAjax+'processShopping';        
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
                
                idClientUser=respuestas[1];
                ActualizarTotalItemsCarro(idClientUser);
            }else{
                
                idClientUser = $.cookie("idClientUser"); 
                
                if(idClientUser==undefined){        
                    idClientUser=uuid.v4();
                    $.cookie("idClientUser",idClientUser,{expires: 9999});
                    
                }
                if($('#aShoppingCar').length){
                    updateTotalsCar();
                }
                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
      
    
}

function delete_item_order(local_id,item_id){
     
    var form_data = new FormData();
        form_data.append('ActionShopping', '4'); 
        form_data.append('local_id', local_id);
        form_data.append('item_id', item_id);
              
        urlQuery=URLAjax+'processShopping';        
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
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){                
                alertify.error(respuestas[1]);                
                drawShoppingOrder(local_id);
                updateTotalsCar();
                
               
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
                
            }else{
                alertify.alert(data);                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            $('#loader').fadeOut();
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
        
        
        urlQuery=URLAjax+'processShopping';        
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
                updateTotalsCar();
                alertify.success(respuestas[1],1000);
                
                
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

function updateTotalsCar(){
    
    var local_id=$('#aShoppingCar').data("local_id");
    var form_data = new FormData();
        form_data.append('ActionShopping', '2'); 
        form_data.append('user_id', idClientUser);
        form_data.append('local_id', local_id);
                
        urlQuery=URLAjax+'processShopping';        
        $.ajax({
        url: urlQuery,
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
    var top_go=0;
    if($("#searchProducts").length>0){
        var top_go=$("#searchProducts").offset().top;
    }
    $("html, body").animate({ scrollTop: top_go }, 600);
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

function add_events_virtual_shop_order(){
    
    $('.del-item').on('click',function () {
        delete_item_order($(this).data("local_id"),$(this).data("item_id"));
    });
    
    $('#tsSendOrder').on('click',function () {
        ConfimarSolicitarPedidos($(this).data("pedido_id"),idClientUser);
    });
    
    $('#chRegistrarse').on('change',function () {
        
        MostrarCamposRegistro();
    });
    
    $('#Telefono').on('wheel',function () {
        
        $(this).blur();
    });
    
    
              
}

function MostrarCamposRegistro(){
    var chRegistro=document.getElementById('chRegistrarse').checked;
    
    if(chRegistro==false){
        MuestraOcultaXID("divRegistrarse",0);
                
    }else{
        MuestraOcultaXID("divRegistrarse",1);
        
    }
    
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
    
    $('#aShoppingCar').on('click',function () {
        drawShoppingOrder($(this).data("local_id")); 
    });
    
    $('#IconLogin').on('click',function () {
        $("#btnLoginUser").data("type_login","1");
        openModal('modalLogin');
        
    });
    
    $('#iconLoginAdmin').on('click',function () {
        $("#btnLoginUser").data("type_login","2");
        openModal('modalLogin');
        
    });
    
    $('#btnLoginUser').on('click',function () {
        if($(this).data("type_login")==1){
            console.log("Entra a loguear usuario");
            initLoginUser();
        }
        if($(this).data("type_login")==2){
            console.log("Entra a loguear administrador");
            initLoginAdmin();
        }
    });
    
    $('#btnSendContact').on('click',function () {
        initSendContact();
    });
} );


function drawShoppingOrder(local_id){
    
    openModal('modal_virtual_shop');
    var idDiv="divModal";
       
    urlQuery=URLAjax+'views';    
    var form_data = new FormData();
        form_data.append('actionPagesDraw', 5);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        
        form_data.append('local_id', local_id);
        form_data.append('myPath', myPath);        
        form_data.append('idClientUser', idClientUser);
        
        
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
            
            add_events_virtual_shop_order();
            AutocomplementarDatosCliente();
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            $('#loader').fadeOut();
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

function drawSliderProduct(local_id,dataProduct){
    
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

function ConfimarSolicitarPedidos(pedido_id,idClientUser){
    
    alertify.confirm('Seguro que desea Realizar el pedido? ',
        function (e) {
            if (e) {
               
                grecaptcha.execute('6LdoC-gUAAAAADi7iGr_b8WtxMijj24V8v-dAtB-', {action: 'homepage'}).then(function(token) {
                     CrearPedido(idClientUser,token);
                });
                
                CrearPedido(idClientUser,'');
            }else{
                alertify.error("Se canceló el proceso");
                return;
            }
        });

}


function CrearPedido(idClientUser,token){
    var local_id=$('#aShoppingCar').data("local_id");
    var pedido_id=$('#tsSendOrder').data("pedido_id");
    var idBoton='tsSendOrder';
    document.getElementById(idBoton).disabled=true;
    document.getElementById(idBoton).value="Enviando...";
    var NombreCliente=document.getElementById('NombreCliente').value;
    var DireccionCliente=document.getElementById('DireccionCliente').value;
    var Telefono=document.getElementById('Telefono').value;
    var ObservacionesPedido=document.getElementById('ObservacionesPedido').value;
    var chRegistrarse=document.getElementById('chRegistrarse').checked;
    var Email=document.getElementById('Email').value;
    var Password=document.getElementById('Password').value;
    var PasswordConfirm=document.getElementById('PasswordConfirm').value;
    var idDiv="divModal";
        
    var form_data = new FormData();
        form_data.append('ActionShopping', '5'); 
        form_data.append('NombreCliente', NombreCliente);
        form_data.append('DireccionCliente', DireccionCliente);
        form_data.append('Telefono', Telefono);
        form_data.append('ObservacionesPedido', ObservacionesPedido);
        form_data.append('idUserClient', idClientUser);
        form_data.append('chRegistrarse', chRegistrarse);
        form_data.append('Email', Email);
        form_data.append('Password', Password);
        form_data.append('PasswordConfirm', PasswordConfirm);
        form_data.append('token', token);
        form_data.append('action', 'homepage');
        form_data.append('local_id', local_id);
        form_data.append('pedido_id', pedido_id);
        
        urlQuery=URLAjax+'processShopping';        
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById(idBoton).disabled=false;
            document.getElementById(idBoton).value="Solicitar";
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                alertify.alert(respuestas[1]);
                drawShoppingOrder(local_id);
                updateTotalsCar();
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
            }else{
                alertify.alert(data);                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;
            document.getElementById(idBoton).value="Solicitar";
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}


function initLoginUser(){

    grecaptcha.execute('6LdoC-gUAAAAADi7iGr_b8WtxMijj24V8v-dAtB-', {action: 'login'}).then(function(token) {
            LoginUser(token);
       });
       
    
   // LoginUser('');
}

function LoginUser(token){
    var idBoton='btnLoginUser';
    document.getElementById(idBoton).disabled=true;
    document.getElementById(idBoton).value="Entrando...";
    var emailLogin=document.getElementById('emailLogin').value;
    var passLogin=CryptoJS.MD5($("#passLogin").val());
    
    var idDiv="divMain";
        
    var form_data = new FormData();
        form_data.append('ActionShopping', '10'); 
        form_data.append('emailLogin', emailLogin);
        form_data.append('passLogin', passLogin);        
        form_data.append('token', token);
        form_data.append('action', 'login');
                        
        urlQuery=URLAjax+'processShopping';        
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById(idBoton).disabled=false;
            document.getElementById(idBoton).value="Entrar";
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                alertify.success(respuestas[1]);
                idClientUser=respuestas[2];
                $.cookie("idClientUser",idClientUser,{expires: 9999});                
                $('#emailLogin').val('');
                $('#passLogin').val('');
                closeModal('modalLogin');               
                updateTotalsCar(idClientUser);
                
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
            }else{
                alertify.alert(data);                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;
            document.getElementById(idBoton).value="Solicitar";
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}


function initSendContact(){
 
    grecaptcha.execute('6LdoC-gUAAAAADi7iGr_b8WtxMijj24V8v-dAtB-', {action: 'contact'}).then(function(token) {
            Contact(token);
       });
     
    
    
}

function Contact(token){
    var idBoton='btnSendContact';
    document.getElementById(idBoton).disabled=true;
    document.getElementById(idBoton).value="Enviando...";
    var nameContact=document.getElementById('nameContact').value;
    var email=document.getElementById('subscribe-email').value;
    var mensageContact=document.getElementById('mensageContact').value;
    var phone=document.getElementById('phoneContact').value;
    var local_id= $('#btnSendContact').data("local_id");       
    var form_data = new FormData();
        form_data.append('ActionShopping', '11'); 
        form_data.append('local_id', local_id);
        form_data.append('nameContact', nameContact);
        form_data.append('email', email);
        form_data.append('phone', phone);
        form_data.append('mensageContact', mensageContact);
        form_data.append('token', token);
        form_data.append('action', 'contact');
                        
        urlQuery=URLAjax+'processShopping';        
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById(idBoton).disabled=false;
            document.getElementById(idBoton).value="Enviar";
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                alertify.success(respuestas[1]);
                          
                $('#nameContact').val('');
                $('#subscribe-email').val('');
                $('#mensageContact').val('');
                $('#phoneContact').val('');
                               
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                //MarqueErrorElemento(respuestas[2]);
            }else{
                alertify.alert(data);                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBoton).disabled=false;
            document.getElementById(idBoton).value="Enviar";
            alert(xhr.status);
            alert(thrownError);
          }
      });
    
}


function AutocomplementarDatosCliente(){
    
    if(!$("#NombreCliente").length){
        return;
    }
    
       
    var form_data = new FormData();
        form_data.append('ActionShopping', '7'); 
        
        form_data.append('idUserClient', idClientUser);
                        
        urlQuery=URLAjax+'processShopping';        
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                
                document.getElementById('NombreCliente').value=respuestas[1];
                document.getElementById('DireccionCliente').value=respuestas[2];
                document.getElementById('Telefono').value=(respuestas[3]);
                
                
                
               
            }          
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

function initLoginAdmin(){
    /*
    grecaptcha.execute('6LdoC-gUAAAAADi7iGr_b8WtxMijj24V8v-dAtB-', {action: 'loginAdmin'}).then(function(token) {
            LoginAdmin(token);
       });
      */ 
    
    LoginAdmin('');
}

function LoginAdmin(token){
        
    var user_domi=document.getElementById("emailLogin").value;
    var pw_domi=document.getElementById("passLogin").value;
    
    var form_data = new FormData();
        form_data.append('action', '1'); 
        form_data.append('user_domi', user_domi);
        form_data.append('pw_domi', pw_domi);
        form_data.append('Token_user', idClientUser);
        form_data.append('token', token);
        //form_data.append('action', '1');
        
        urlQuery=URLAjax+'processAdminShop';
        
        $.ajax({
        url: urlQuery,
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            console.log("DAtos"+data);
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                
                var urlAdmin = pathname+"admin"
                window.open(urlAdmin);
                $('#emailLogin').val('');
                $('#passLogin').val('');
                closeModal('modalLogin'); 
                
            }else if(respuestas[0]=="E1"){  
                alertify.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
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


spinnerCreate();
buttonUpCreate();

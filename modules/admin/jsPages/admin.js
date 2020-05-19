/**
 * Controlador para el administrador
 * JULIAN ANDRES ALVARAN
 * 2020-04-04
 */

//variables globales
idClientUser = $.cookie("idClientUser");
var pathname = window.location.pathname;
var arrayPath = pathname.split('/');
var myPath=""
for(i=4;i<=arrayPath.length;i++){
    myPath=myPath+"../";
}

var URLdomain = window.location.host;
var URLAjax="http://"+URLdomain+"/"+arrayPath[1]+"/";

//Agrega eventos generales de la pagina

$(document).ready(function(){
    
    $('.ts-submenu-modules').on('click',function () {        
        $('#titleModule').text($(this).data("submenu_name"));
        draw_content_submenu($(this).data("submenu_id"),$(this).data("folder"),$(this).data("action_view"));
    });
    
} );

function add_events_tables_data(){
    $('.ts_form_table').on('click',function () { 
        draw_forms_tables($(this).data("form_id"),$(this).data("item_id"));
    });
    
    $('#btnMigrates').on('click',function () { 
        ConfirmarMigracion();
    });
    
    
}

//Creadores de elementos generales

function spinnerCreate(){
        
    $('body').prepend('<div class="m-2 d-inline-block spinnerloadpage"><div id="loader" class="spinner-4"><div class="bg-info"></div><div class="bg-info"></div><div class="bg-info"></div></div></div>');
    
    $('#loader').fadeOut();    

}

spinnerCreate();


function draw_content_submenu(submenu_id,folder,action_view){
    
    var idDiv="divContentModule";
    urlQuery=URLAjax+'viewsAdmin';    
    var form_data = new FormData();
        form_data.append('actionPagesDrawAdmin', action_view);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('submenu_id', submenu_id);
        form_data.append('folder', folder);        
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
            add_events_tables_data();
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function draw_forms_tables(form_id,item_id){
    
    if(form_id==1){
        draw_form_locals(item_id);
    }
    
}


function draw_form_locals(item_id){
    
    var idDiv="divContentModule";
    urlQuery=URLAjax+'viewsAdmin';    
    var form_data = new FormData();
        form_data.append('actionPagesDrawAdmin', 3);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('item_id', item_id);          
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
            
            $('#btn_form_save').on('click',function () { 
                ConfirmaGuardarEditar($("#btn_form_save").data("table_id"),item_id);
            });
            
            Dropzone.autoDiscover = false;
           
            urlQuery=URLAjax+'processAdminShop'; 
            var form_identify=$("#btn_form_save").data("form_identify");
            var myDropzone = new Dropzone("#logoLocal", { url: urlQuery,paramName: "logo",acceptedFiles: 'image/*'});
                myDropzone.on("sending", function(file, xhr, formData) { 
                    console.log(file.type);
                    formData.append("actionAdmin", 10);
                    formData.append("myPath", myPath);
                    formData.append("form_identify", form_identify);
                    formData.append("typeImage", 0);//Le indico al sistema que es el logo
                });
              
                myDropzone.on("addedfile", function(file) {
                    file.previewElement.addEventListener("click", function() {
                        myDropzone.removeFile(file);
                    });
                });
              
                myDropzone.on("success", function(file, data) {
                    
                    var respuestas = data.split(';');
                    if(respuestas[0]=="OK"){
                        toastr.success(respuestas[1]);
                    }else if(respuestas[0]=="E1"){
                        toastr.warning(respuestas[1]);
                    }else{
                        swal(data);
                    }
                    
                });
                
                
           
            var myDropzone2 = new Dropzone("#fotoLocal", { url: urlQuery,paramName: "logo",acceptedFiles: 'image/*',addRemoveLinks: true});
                myDropzone2.on("sending", function(file, xhr, formData) { 
                    console.log(file.type);
                    formData.append("actionAdmin", 10);
                    formData.append("myPath", myPath);
                    formData.append("form_identify", form_identify);
                    formData.append("typeImage", 1);//Le indico al sistema que es la foto
                });
              
                myDropzone2.on("addedfile", function(file) {
                    file.previewElement.addEventListener("click", function() {
                        myDropzone.removeFile(file);
                    });
                });
              
                myDropzone2.on("success", function(file, data) {
                    
                    var respuestas = data.split(';');
                    if(respuestas[0]=="OK"){
                        toastr.success(respuestas[1]);
                    }else if(respuestas[0]=="E1"){
                        toastr.warning(respuestas[1]);
                    }else{
                        swal(data);
                    }
                    
                });
              
            new Dropzone(document.body, {
                previewsContainer: ".dropzone-previews",
                
                clickable: false
            });
                        
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function ConfirmaGuardarEditar(Tabla,idItem){
    
    swal({   
            title: "Estas Seguro?",   
            //text: "You will not be able to recover this imaginary file!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Si, deseo Guardar!",   
            cancelButtonText: "No, quiero Cancelar!",   
            closeOnConfirm: false,   
            closeOnCancel: false 
        }, function(isConfirm){   
            if (isConfirm) {     
                swal("Enviando!", "", "success");
                if(Tabla==1){
                    GuardarEditarClasificacion(idItem);
                }
                if(Tabla==2){
                    GuardarEditarProducto(idItem);
                }
                if(Tabla==3){
                    GuardarEditarLocal(idItem);
                }
                if(Tabla==4){
                    GuardarFotoProducto(idItem);
                }
            } else {     
                swal("Cancelado", "Se ha cancelado el proceso :)", "error");   
            } 
        });
                
     /*           
    alertify.confirm('Seguro que desea Guardar? ',
        function (e) {
            if (e) {
                if(Tabla==1){
                    GuardarEditarClasificacion(idItem);
                }
                if(Tabla==2){
                    GuardarEditarProducto(idItem);
                }
                if(Tabla==3){
                    GuardarEditarLocal(idItem);
                }
                if(Tabla==4){
                    GuardarFotoProducto(idItem);
                }
                
            }else{
                alertify.error("Se canceló el proceso");
                return;
            }
        });
        */
}


function GuardarEditarLocal(idItem){
    
    var form_identify=$("#btn_form_save").data("form_identify");
    var idCategoria=document.getElementById('idCategoria').value;
    var Nombre=document.getElementById('Nombre').value;
    var Direccion=document.getElementById('Direccion').value;
    var Telefono=document.getElementById('Telefono').value;
    var Propietario=document.getElementById('Propietario').value;
    var Tarifa=document.getElementById('Tarifa').value;
    var Email=document.getElementById('Email').value;
    var Password=document.getElementById('Password').value;
    var Descripcion=document.getElementById('Descripcion').value;
    var Orden=document.getElementById('Orden').value;
    var Estado=document.getElementById('Estado').value;
    
    var Indicativo=document.getElementById('Indicativo').value;
    var Whatsapp=document.getElementById('Whatsapp').value;
    var idTelegram=document.getElementById('idTelegram').value;    
    var idCiudad=document.getElementById('idCiudad').value;
    var theme_id=document.getElementById('theme_id').value;
    var page_initial=document.getElementById('page_initial').value;    
    var header_class=document.getElementById('header_class').value;
    var slider_class=document.getElementById('slider_class').value;
    var keywords=document.getElementById('keywords').value;
    var title_page=document.getElementById('title_page').value;
    var virtual_shop=document.getElementById('virtual_shop').value;
    var UrlLocal=document.getElementById('UrlLocal').value;
    var Alcance=document.getElementById('Alcance').value;
    
    
    var form_data = new FormData();
       
        form_data.append('actionAdmin', '6');         
        form_data.append('idItem', idItem);
        form_data.append('Token_user', idClientUser);
        form_data.append('idCategoria', idCategoria);
        form_data.append('Nombre', Nombre);
        form_data.append('Direccion', Direccion);
        form_data.append('Telefono', Telefono);
        form_data.append('Propietario', Propietario);
        form_data.append('Tarifa', Tarifa);
        form_data.append('Email', Email);
        form_data.append('Password', Password);
        form_data.append('Descripcion', Descripcion);
        form_data.append('Orden', Orden);
        form_data.append('Estado', Estado);
        
        form_data.append('Indicativo', Indicativo);
        form_data.append('Whatsapp', Whatsapp);
        form_data.append('idTelegram', idTelegram);
        form_data.append('idCiudad', idCiudad);
        form_data.append('theme_id', theme_id);
        form_data.append('page_initial', page_initial);
        form_data.append('header_class', header_class);
        form_data.append('slider_class', slider_class);
        form_data.append('keywords', keywords);
        form_data.append('virtual_shop', virtual_shop);
        form_data.append('UrlLocal', UrlLocal);
        form_data.append('title_page', title_page);
        form_data.append('Alcance', Alcance);
        form_data.append('form_identify', form_identify);
        
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
            
            var respuestas = data.split(';'); //Armamos un vector separando los punto y coma de la cadena de texto
            if(respuestas[0]=="OK"){ 
                toastr.success(respuestas[1]);                
                draw_content_submenu(1,'admin',2); 
                if(idItem==''){
                    ConfirmarMigracion();
                }
            }else if(respuestas[0]=="E1"){  
                toastr.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
            }else{
                swal(data);                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
     
}
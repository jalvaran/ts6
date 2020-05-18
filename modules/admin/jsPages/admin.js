/**
 * Controlador para el administrador
 * JULIAN ANDRES ALVARAN
 * 2020-04-04
 */

//variables globales

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
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

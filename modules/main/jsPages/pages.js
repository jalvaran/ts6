/**
 * Controlador para dibujar las paginas
 * JULIAN ANDRES ALVARAN
 * 2020-05-04
 */
var pathname = window.location.pathname;
var arrayPath = pathname.split('/');
var URLdomain = window.location.host;
var URLAjax="http://"+URLdomain+"/"+arrayPath[1]+"/";
var Page=1;
var local_id=1;

$('.ts_menu').on('click',function () {
    pagesDraw($(this).data("page_id"));
});
    
function pagesDraw(page_id){
    var idDiv="divDrawPage";
    urlQuery=URLAjax+'modulos/main/Consultas/pages.draw.php';    
    var form_data = new FormData();
        form_data.append('actionMain', 1);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('page_id', page_id);
        form_data.append('local_id', local_id);
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        success: function(data){            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            sectionsDraw(page_id);
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function sectionsDraw(page_id){
    var idDiv="divDrawSections";
    urlQuery=URLAjax+'views';
    
    var form_data = new FormData();
        form_data.append('actionPagesDraw', 2);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('page_id', page_id);
        form_data.append('local_id', local_id);
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
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

sectionsDraw(local_id);


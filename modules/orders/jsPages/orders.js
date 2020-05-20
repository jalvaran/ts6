
function add_events_orders(){
    $('.ts_status_order').on('change',function () {        

    change_status_order($(this).data("item_id"));

    });

    $('#filter_status_order').on('change',function () { 
        draw_orders_filter($(this).data("route_view"),$(this).data("submenu_id"),$(this).data("folder"),$(this).data("action_view"),$(this).data("page"),$(this).val());
    });
    
    $('.ts_paginator').on('click',function () { 
        draw_content_submenu($(this).data("route_view"),$(this).data("submenu_id"),$(this).data("folder"),$(this).data("action_view"),$(this).data("page"));
    });
    
}

$('.ts_status_order').on('change',function () {        

    change_status_order($(this).data("item_id"));

});

$('#filter_status_order').on('change',function () { 
    draw_orders_filter($(this).data("route_view"),$(this).data("submenu_id"),$(this).data("folder"),$(this).data("action_view"),$(this).data("page"),$(this).val());
});

function draw_orders_filter(route_view,submenu_id,folder,action_view,page,status_filter){
    var Busqueda=document.getElementById('txtSearch').value;
    
    var idDiv="divContentModule";
    urlQuery=URLAjax+route_view;    
    var form_data = new FormData();
        form_data.append('action', action_view);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('submenu_id', submenu_id);
        form_data.append('folder', folder);
        form_data.append('page', page);
        form_data.append('Busqueda', Busqueda);
        form_data.append('status_filter', status_filter);
        form_data.append('myPath', myPath);
        
       $.ajax({// se arma un objecto por medio de ajax  
        url: urlQuery,// se indica donde llegara la informacion del objecto
        
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post', // se especifica que metodo de envio se utilizara normalmente y por seguridad se utiliza el post
        
        success: function(data){    
            
            document.getElementById(idDiv).innerHTML=data; //La respuesta del servidor la dibujo en el div DivTablasBaseDatos                      
            add_events_orders();
            
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function change_status_order(item_id){
    var select_id="cmbEstado_"+item_id;
    var Estado=document.getElementById(select_id).value;
    
    var form_data = new FormData();
       
        form_data.append('action', '1');         
        form_data.append('item_id', item_id);
        form_data.append('Token_user', idClientUser);        
        form_data.append('Estado', Estado);
                        
        urlQuery=URLAjax+'processOrders';                       
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
                                
            }else if(respuestas[0]=="E1"){  
                toastr.error(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
            }else{
                swal(data);                
            }
                    
        },
        error: function (xhr, ajaxOptions, thrownError) {
            swal(data);
            alert(xhr.status);
            alert(thrownError);
          }
      });
     
}

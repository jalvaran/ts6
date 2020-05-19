
$('.ts_form_table_inventory').on('click',function () {        

    draw_forms_tables_inventory($(this).data("form_id"),$(this).data("item_id"));

});
    
function draw_forms_tables_inventory(form_id,item_id){
    
    if(form_id==1){
        draw_form_clasifieds(item_id);
    }
    if(form_id==2){
        draw_form_inventory(item_id);
    }
    
}       

function draw_form_clasifieds(item_id){
    
    var idDiv="divContentModule";
    urlQuery=URLAjax+'viewsInventory';    
    var form_data = new FormData();
        form_data.append('action', 2);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
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
                
                ConfirmaGuardarEditarInventory($("#btn_form_save").data("table_id"),item_id);
            });
                     
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function ConfirmaGuardarEditarInventory(Tabla,idItem){
    
    swal({   
            title: "Estas Seguro?",   
            
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
                
            } else {     
                swal("Cancelado", "Se ha cancelado el proceso :)", "error");   
            } 
        });
                
}


function GuardarEditarClasificacion(idItem){
    
    var route_view=$("#btn_form_save").data("route_view");
    
    var Clasificacion=document.getElementById('Clasificacion').value;
    var Estado=document.getElementById('Estado').value;
    
    var form_data = new FormData();
       
        form_data.append('action', '1');         
        form_data.append('idItem', idItem);
        form_data.append('Token_user', idClientUser);
        
        form_data.append('Estado', Estado);
        
        form_data.append('Clasificacion', Clasificacion);
                
        urlQuery=URLAjax+'processInventory';                       
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
                //draw_content_submenu(route_view,submenu_id,folder,action_view,page)
                draw_content_submenu(route_view,2,'inventory',1,1); 
                
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


function draw_form_inventory(item_id){
    
    var idDiv="divContentModule";
    urlQuery=URLAjax+'viewsInventory';    
    var form_data = new FormData();
        form_data.append('action', 4);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('item_id', item_id);          
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
            
            $('#btn_form_save').on('click',function () {
                
                ConfirmaGuardarEditarInventory($("#btn_form_save").data("table_id"),item_id);
            });
                     
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}

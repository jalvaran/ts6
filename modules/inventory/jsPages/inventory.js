
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
            var product_id=document.getElementById('ID').value;
            draw_images_product(product_id);
            $('#btn_form_save').on('click',function () {
                
                ConfirmaGuardarEditarInventory($("#btn_form_save").data("table_id"),item_id);
            });
            
            
            $('#btn_cancel_save').on('click',function () {
                
                draw_content_submenu($(this).data("route_view"),3,'inventory',3,1);
            });
            
            
            Dropzone.autoDiscover = false;
           
            urlQuery=URLAjax+'processInventory'; 
            
            var myDropzone = new Dropzone("#imgs_product", { url: urlQuery,paramName: "ImagenProducto",acceptedFiles: 'image/*'});
                myDropzone.on("sending", function(file, xhr, formData) { 
                    
                    formData.append("action", 3);
                    formData.append("myPath", myPath);
                    formData.append("product_id", product_id);
                    
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
                        draw_images_product(product_id);
                    }else if(respuestas[0]=="E1"){
                        toastr.warning(respuestas[1]);
                    }else{
                        swal(data);
                    }
                    
                });
                     
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function GuardarEditarProducto(idItem){
    
    var route_view=$("#btn_form_save").data("route_view");
    
    
    var Estado=document.getElementById('Estado').value;
    
    var ID=document.getElementById('ID').value;
    var Referencia=document.getElementById('Referencia').value;
    var Nombre=document.getElementById('Nombre').value;
    var PrecioVenta=document.getElementById('PrecioVenta').value;
    var DescripcionCorta=document.getElementById('DescripcionCorta').value;
    var DescripcionLarga=document.getElementById('DescripcionLarga').value;
    var idClasificacion=document.getElementById('idClasificacion').value;
    var Orden=document.getElementById('Orden').value;
    
    var form_data = new FormData();
       
        form_data.append('action', '2');         
        form_data.append('idItem', idItem);        
        form_data.append('Token_user', idClientUser);        
        form_data.append('Estado', Estado);
        
        form_data.append('ID', ID);
        form_data.append('Referencia', Referencia);
        form_data.append('Nombre', Nombre);
        form_data.append('PrecioVenta', PrecioVenta);
        form_data.append('DescripcionCorta', DescripcionCorta);
        form_data.append('DescripcionLarga', DescripcionLarga);
        form_data.append('idClasificacion', idClasificacion);
        form_data.append('Orden', Orden);
                
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
                draw_content_submenu(route_view,3,'inventory',3,1); 
                
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

function draw_images_product(product_id){
    
    var idDiv="divImagesProduct";
    urlQuery=URLAjax+'viewsInventory';    
    var form_data = new FormData();
        form_data.append('action', 5);// pasamos la accion y el numero de accion para el dibujante sepa que caso tomar
        form_data.append('product_id', product_id);          
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
            $('.ts_remove_img_product').on('click',function () {
                
                delete_img_product($(this).data("img_id"),product_id);
                
            });
                     
        },
        error: function (xhr, ajaxOptions, thrownError) {// si hay error se ejecuta la funcion
            
            var alertMensanje='<div class="alert alert-danger mt-3"><h4 class="alert-heading">Error!</h4><p>Parece que no hay conexión con el servidor.</p><hr><p class="mb-0">Intentalo de nuevo.</p></div>';
            document.getElementById(idDiv).innerHTML=alertMensanje;
            swal("Error de Conexión");
          }
      });
}


function delete_img_product(idItem,product_id){
    
        
    var form_data = new FormData();
       
        form_data.append('action', '8');         
        form_data.append('idItem', idItem);        
        form_data.append('Token_user', idClientUser);        
                        
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
                draw_images_product(product_id);
                
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

/**
 * Controlador para cargar los egresos
 * JULIAN ALVARAN 2019-05-24
 * TECHNO SOLUCIONES SAS 
 * 
 */

function ConfirmarMigracion(){
    
    alertify.confirm('Desea Ejecutar las migraciones?</strong>',
        function (e) {
            if (e) {

                alertify.success("Validando Migraciones");                    
                IniciarMigraciones();
            }else{
                alertify.error("Se canceló el proceso");

                return;
            }
        });
}

/**
 * Verifica Si ya fue cargado el archivo a subir
 * @returns {undefined}
 */
function IniciarMigraciones(){
    AbreModal('modalMain');
    var idDiv="DivModal";
    document.getElementById(idDiv).innerHTML='<div id="GifProcess">Procesando...<br><img   src="../../images/loader.gif" alt="Cargando" height="100" width="100"></div>';
        
    var form_data = new FormData();
        form_data.append('Accion', 1);
        form_data.append('Token_user', idClientUser);
        
        
        $.ajax({
        url: './procesadores/migrations.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
           var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){  //SI no existe 
                
                alertify.success(respuestas[1]);
                EjecutarMigraciones(1);
            }else if(respuestas[0]==="E1"){ //Si existe debe pedir o no actualizacion
                document.getElementById(idDiv).innerHTML=respuestas[1];
                return;      
                
            }else{
                document.getElementById(idDiv).innerHTML=data;
                alertify.alert(data);
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      });
}

/**
 * Se ejecutan las migraciones
 * @returns {undefined}
 */
function EjecutarMigraciones(MigracionActual){
    
    var idDiv="DivModal";
    
    var form_data = new FormData();
        form_data.append('Accion', 2);        
        
        form_data.append('MigracionActual', MigracionActual);
        form_data.append('Token_user', idClientUser);
      
    $.ajax({
        //async:false,
        url: './procesadores/migrations.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';'); 
           if(respuestas[0]==="OK"){   
               document.getElementById(idDiv).innerHTML=(respuestas[1]);
               alertify.success(respuestas[1]);
                
            }else if(respuestas[0]==="E1"){
                                
                document.getElementById(idDiv).innerHTML=(respuestas[1]);
                
                return;                
            }else{
                
                
                document.getElementById(idDiv).innerHTML=data;
                
            }
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            
            alert(xhr.status);
            alert(thrownError);
          }
      })
}


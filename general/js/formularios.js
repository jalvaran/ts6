/**
 * Controlador para realizar cargar y guardar formularios que se usaran en varios modulos
 * JULIAN ALVARAN 2019-03-27
 * TECHNO SOLUCIONES SAS 
 * 317 774 0609
 */

/**
 * Abre el modal para crear un tercero
 * @returns {undefined}
 */
function ModalCrearTercero(idModal,idDivFormulario){
    $("#"+idModal).modal();
    
    var form_data = new FormData();
        
        form_data.append('Accion', 1);
        
        $.ajax({
        url: '../../general/Consultas/formularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById(idDivFormulario).innerHTML=data;
            $('#CodigoMunicipio').select2();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })  
}

/**
 * Abre el modal para crear un tercero
 * @returns {undefined}
 */
function ModalEditarTercero(idModal,idDivFormulario,idTercero,Tabla){
    $("#"+idModal).modal();
    
    var form_data = new FormData();
        
        form_data.append('Accion', 3);
        form_data.append('idTercero', idTercero);
        form_data.append('Tabla', Tabla);
        $.ajax({
        url: '../../general/Consultas/formularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById(idDivFormulario).innerHTML=data;
            $('#CodigoMunicipio').select2();
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })  
}

/**
 * Crear un tercero
 * @returns {undefined}
 */
function CrearTercero(idModal,idBotonModal){
    
    var TipoDocumento=document.getElementById('TipoDocumento').value;
    var Num_Identificacion=document.getElementById('Num_Identificacion').value;    
    var CodigoMunicipio=document.getElementById('CodigoMunicipio').value;
    var Telefono=document.getElementById('Telefono').value;
    var PrimerNombre=document.getElementById('PrimerNombre').value;
    var OtrosNombres=document.getElementById('OtrosNombres').value;
    var PrimerApellido=document.getElementById('PrimerApellido').value;
    var SegundoApellido=document.getElementById('SegundoApellido').value;
    var RazonSocial=document.getElementById('RazonSocial').value;
    var Direccion=document.getElementById('Direccion').value;
    var Email=document.getElementById('Email').value;
    var Cupo=document.getElementById('Cupo').value;
    var CodigoTarjeta=document.getElementById('CodigoTarjeta').value;
    
    if(!$.isNumeric(Num_Identificacion) || Num_Identificacion <= 0){
        alertify.error("El Campo Identificacion debe ser un número mayor a Cero y no puede estar en blanco");
        document.getElementById("Num_Identificacion").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Num_Identificacion").style.backgroundColor="white";
    }
    
    if(Telefono==''){
        alertify.error("El Campo Teléfono no puede estar vacío");
        document.getElementById("Telefono").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Telefono").style.backgroundColor="white";
    }
    
    if(RazonSocial==''){
        alertify.error("El Campo Razón Social no puede estar vacío");
        document.getElementById("RazonSocial").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("RazonSocial").style.backgroundColor="white";
    }
    
    
    if(Direccion==''){
        alertify.error("El Campo Dirección no puede estar vacío");
        document.getElementById("Direccion").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Direccion").style.backgroundColor="white";
    }
    
    if(Email==''){
        alertify.error("El Campo Email no puede estar vacío");
        document.getElementById("Email").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Email").style.backgroundColor="white";
    }
    
    if(!$.isNumeric(Cupo) || Cupo < 0){
        alertify.error("El Campo Cupo debe ser un número mayor o igual a Cero y no puede estar en blanco");
        document.getElementById("Cupo").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Cupo").style.backgroundColor="white";
    }
    
    var form_data = new FormData();
        
        form_data.append('Accion', 1);
        form_data.append('TipoDocumento', TipoDocumento);
        form_data.append('Num_Identificacion', Num_Identificacion);
        form_data.append('CodigoMunicipio', CodigoMunicipio);
        form_data.append('Telefono', Telefono);
        form_data.append('TipoDocumento', TipoDocumento);
        form_data.append('PrimerNombre', PrimerNombre);
        form_data.append('OtrosNombres', OtrosNombres);
        form_data.append('PrimerApellido', PrimerApellido);
        form_data.append('SegundoApellido', SegundoApellido);
        form_data.append('RazonSocial', RazonSocial);
        form_data.append('Direccion', Direccion);
        form_data.append('Email', Email);
        form_data.append('Cupo', Cupo);
        form_data.append('CodigoTarjeta', CodigoTarjeta);
        
        document.getElementById("RazonSocial").value='';
        
        $.ajax({
        url: '../../general/procesadores/formularios.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';');
            if(respuestas[0]=="E1"){
                alertify.alert(respuestas[1]);
                
            }else if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
                CierraModal(idModal);
                
            }else{
                alertify.alert(data);
            }
            document.getElementById(idBotonModal).disabled=false;
            document.getElementById(idBotonModal).value="Guardar";
                      
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })  
    
}

/**
 * Crear un tercero
 * @returns {undefined}
 */
function EditarTercero(idModal,idBotonModal,idTercero,Tabla){
    document.getElementById(idBotonModal).disabled=true;
    document.getElementById(idBotonModal).value="Guardando...";
    var TipoDocumento=document.getElementById('TipoDocumento').value;
    var Num_Identificacion=document.getElementById('Num_Identificacion').value;    
    var CodigoMunicipio=document.getElementById('CodigoMunicipio').value;
    var Telefono=document.getElementById('Telefono').value;
    var PrimerNombre=document.getElementById('PrimerNombre').value;
    var OtrosNombres=document.getElementById('OtrosNombres').value;
    var PrimerApellido=document.getElementById('PrimerApellido').value;
    var SegundoApellido=document.getElementById('SegundoApellido').value;
    var RazonSocial=document.getElementById('RazonSocial').value;
    var Direccion=document.getElementById('Direccion').value;
    var Email=document.getElementById('Email').value;
    var Cupo=document.getElementById('Cupo').value;
    var CodigoTarjeta=document.getElementById('CodigoTarjeta').value;
    
    if(!$.isNumeric(Num_Identificacion) || Num_Identificacion <= 0){
        alertify.error("El Campo Identificacion debe ser un número mayor a Cero y no puede estar en blanco");
        document.getElementById("Num_Identificacion").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Num_Identificacion").style.backgroundColor="white";
    }
    
    if(Telefono==''){
        alertify.error("El Campo Teléfono no puede estar vacío");
        document.getElementById("Telefono").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Telefono").style.backgroundColor="white";
    }
    
    if(RazonSocial==''){
        alertify.error("El Campo Razón Social no puede estar vacío");
        document.getElementById("RazonSocial").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("RazonSocial").style.backgroundColor="white";
    }
    
    
    if(Direccion==''){
        alertify.error("El Campo Dirección no puede estar vacío");
        document.getElementById("Direccion").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Direccion").style.backgroundColor="white";
    }
    
    if(Email==''){
        alertify.error("El Campo Email no puede estar vacío");
        document.getElementById("Email").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Email").style.backgroundColor="white";
    }
    
    if(!$.isNumeric(Cupo) || Cupo < 0){
        alertify.error("El Campo Cupo debe ser un número mayor o igual a Cero y no puede estar en blanco");
        document.getElementById("Cupo").style.backgroundColor="pink";
        
        return;
    }else{
        document.getElementById("Cupo").style.backgroundColor="white";
    }
    
    var form_data = new FormData();
        
        form_data.append('Accion', 4);
        form_data.append('TipoDocumento', TipoDocumento);
        form_data.append('Num_Identificacion', Num_Identificacion);
        form_data.append('CodigoMunicipio', CodigoMunicipio);
        form_data.append('Telefono', Telefono);
        form_data.append('TipoDocumento', TipoDocumento);
        form_data.append('PrimerNombre', PrimerNombre);
        form_data.append('OtrosNombres', OtrosNombres);
        form_data.append('PrimerApellido', PrimerApellido);
        form_data.append('SegundoApellido', SegundoApellido);
        form_data.append('RazonSocial', RazonSocial);
        form_data.append('Direccion', Direccion);
        form_data.append('Email', Email);
        form_data.append('Cupo', Cupo);
        form_data.append('CodigoTarjeta', CodigoTarjeta);
        form_data.append('idTercero', idTercero);
        form_data.append('Tabla', Tabla);
        
        //document.getElementById("RazonSocial").value='';
        
        $.ajax({
        url: '../../general/procesadores/formularios.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById(idBotonModal).disabled=false;
            document.getElementById(idBotonModal).value="Guardar";
            var respuestas = data.split(';');
            if(respuestas[0]=="E1"){
                alertify.alert(respuestas[1]);
                MarqueErrorElemento(respuestas[2]);
            }else if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
                CierraModal(idModal);
                
            }else{
                alertify.alert(data);
            }
            document.getElementById(idBotonModal).disabled=false;
            document.getElementById(idBotonModal).value="Guardar";
                      
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })  
    
}

/**
 * Verifica si existe un nit
 * @returns {undefined}
 */
function VerificaNIT(){
    var Num_Identificacion=document.getElementById('Num_Identificacion').value;
    
    var form_data = new FormData();
        
        form_data.append('Accion', 2);
        form_data.append('Num_Identificacion', Num_Identificacion);
        
        $.ajax({
        url: '../../general/procesadores/formularios.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';');
            if(respuestas[0]=="E1"){
                alertify.error(respuestas[1]);
                document.getElementById("Num_Identificacion").style.backgroundColor="pink";
                posiciona('Num_Identificacion');
                document.getElementById("BntModalPOS").disabled=true;
            }else if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
                document.getElementById("Num_Identificacion").style.backgroundColor="white";
                document.getElementById("BntModalPOS").disabled=false;
            }else{
                alertify.alert(data);
                document.getElementById("BntModalPOS").disabled=false;
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })  
}

/**
 * Verifica si el codigo de una tarjeta ya existe
 * @returns {undefined}
 */
function VerificaCodigoTarjeta(){
    var CodigoTarjeta=document.getElementById('CodigoTarjeta').value;
    
    var form_data = new FormData();
        
        form_data.append('Accion', 3);
        form_data.append('CodigoTarjeta', CodigoTarjeta);
        
        $.ajax({
        url: '../../general/procesadores/formularios.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';');
            if(respuestas[0]=="E1"){
                alertify.error(respuestas[1]);
                document.getElementById("CodigoTarjeta").style.backgroundColor="pink";
                posiciona('CodigoTarjeta');
                document.getElementById("BntModalPOS").disabled=true;
            }else if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
                document.getElementById("CodigoTarjeta").style.backgroundColor="white";
                document.getElementById("BntModalPOS").disabled=false;
            }else{
                alertify.alert(data);
                document.getElementById("BntModalPOS").disabled=false;
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })  
}
/**
 * Crea la razon social
 * @returns {undefined}
 */
function CompletaRazonSocial() {

    var PrimerApellido=document.getElementById('PrimerApellido').value;
    var SegundoApellido=document.getElementById('SegundoApellido').value;
    var PrimerNombre=document.getElementById('PrimerNombre').value;
    var OtrosNombres=document.getElementById('OtrosNombres').value;
	

    var RazonSocial=PrimerNombre+" "+OtrosNombres+" "+PrimerApellido+" "+SegundoApellido;

    document.getElementById('RazonSocial').value=RazonSocial;


}

function FormularioCreacionProductos(idModal,idDivFormulario,idBotonModal,CrearBotonGuardar=0){
    if(idModal!=""){
        $("#"+idModal).modal();
        document.getElementById(idBotonModal).disabled=true;
    }
    var form_data = new FormData();
        
        form_data.append('Accion', 2);
        form_data.append('CrearBotonGuardar', CrearBotonGuardar);
        
        $.ajax({
        url: '../../general/Consultas/formularios.draw.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById(idDivFormulario).innerHTML=data;
            ConvierteSelectoresSubgrupos('D');
            $('#CmbCuentaPUC').select2({		  
                placeholder: 'Seleccione una Cuenta Contable',
                ajax: {
                  url: '../../general/buscadores/CuentaPUCIngresos.search.php',
                  dataType: 'json',
                  delay: 250,
                  processResults: function (data) {

                    return {                     
                      results: data
                    };
                  },
                 cache: true
                }
              });
            document.getElementById(idBotonModal).disabled=false;
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById(idBotonModal).disabled=false;
            alert(xhr.status);
            alert(thrownError);
          }
      })  
}

function ConvierteSelectoresSubgrupos(idAccion){
        
    if(idAccion=='D'){
        $('#CmbDepartamento').select2({
		  
            placeholder: 'Selecciona un Departamento',
            ajax: {
              url: '../../general/buscadores/departamentos.search.php',
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                  
                return {                     
                  results: data
                };
              },
             cache: true
            }
          });
          
        document.getElementById("CmbSub1").value='';
        
        if(document.getElementById("select2-CmbSub1-container")){
            
            document.getElementById("select2-CmbSub1-container").innerHTML='Seleccione el Subgrupo 1';
        }
        
        document.getElementById("CmbSub2").value='';
        if(document.getElementById("select2-CmbSub2-container")){
            
            document.getElementById("select2-CmbSub2-container").innerHTML='Seleccione el Subgrupo 2';
        }
        
        document.getElementById("CmbSub4").value='';
        if(document.getElementById("select2-CmbSub4-container")){
            
            document.getElementById("select2-CmbSub4-container").innerHTML='Seleccione el Subgrupo 4';
        }
        document.getElementById("CmbSub3").value='';
        if(document.getElementById("select2-CmbSub3-container")){
            
            document.getElementById("select2-CmbSub3-container").innerHTML='Seleccione el Subgrupo 3';
        }
        document.getElementById("CmbSub6").value='';
        if(document.getElementById("select2-CmbSub6-container")){
            document.getElementById("select2-CmbSub6-container").innerHTML='Seleccione el Subgrupo 6';
        }
        
        var Departamento=document.getElementById("CmbDepartamento").value;
        
        $('#CmbSub1').select2({
		  
            placeholder: 'Selecciona el sub grupo 1',
            ajax: {
              url: '../../general/buscadores/sub1.search.php?idDepartamento='+Departamento,
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                  
                return {                     
                  results: data
                };
              },
             cache: true
            }
          });
    }
    if(idAccion=='1'){
        
        document.getElementById("CmbSub2").value='';
        if(document.getElementById("select2-CmbSub2-container")){
            document.getElementById("select2-CmbSub2-container").innerHTML='Seleccione el Subgrupo 2';
        }
        document.getElementById("CmbSub4").value='';
        if(document.getElementById("select2-CmbSub4-container")){
            document.getElementById("select2-CmbSub4-container").innerHTML='Seleccione el Subgrupo 4';
        }
        document.getElementById("CmbSub3").value='';
        if(document.getElementById("select2-CmbSub3-container")){
            document.getElementById("select2-CmbSub3-container").innerHTML='Seleccione el Subgrupo 3';
        }
        document.getElementById("CmbSub6").value='';
        if(document.getElementById("select2-CmbSub6-container")){
            document.getElementById("select2-CmbSub6-container").innerHTML='Seleccione el Subgrupo 6';
        }
        var CmbSub1=document.getElementById("CmbSub1").value;
        $('#CmbSub2').select2({
		  
            placeholder: 'Selecciona el sub grupo 2',
            ajax: {
              url: '../../general/buscadores/sub2.search.php?idSub1='+CmbSub1,
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                  
                return {                     
                  results: data
                };
              },
             cache: true
            }
          });
    }
    if(idAccion=='2'){
        
        
        document.getElementById("CmbSub4").value='';
        if(document.getElementById("select2-CmbSub4-container")){
            document.getElementById("select2-CmbSub4-container").innerHTML='Seleccione el Subgrupo 4';
        }
        document.getElementById("CmbSub3").value='';
        if(document.getElementById("select2-CmbSub3-container")){
            document.getElementById("select2-CmbSub3-container").innerHTML='Seleccione el Subgrupo 3';
        }
        document.getElementById("CmbSub6").value='';
        if(document.getElementById("select2-CmbSub6-container")){
            document.getElementById("select2-CmbSub6-container").innerHTML='Seleccione el Subgrupo 6';
        }
        var CmbSub2=document.getElementById("CmbSub2").value;
        $('#CmbSub3').select2({
		  
            placeholder: 'Selecciona el sub grupo 3',
            ajax: {
              url: '../../general/buscadores/sub3.search.php?idSub2='+CmbSub2,
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                  
                return {                     
                  results: data
                };
              },
             cache: true
            }
          });
        
    }
    if(idAccion=='3'){        
        
        document.getElementById("CmbSub4").value='';   
        if(document.getElementById("select2-CmbSub4-container")){
            document.getElementById("select2-CmbSub4-container").innerHTML='Seleccione el Subgrupo 4';
        }
        document.getElementById("CmbSub6").value='';
        if(document.getElementById("select2-CmbSub6-container")){
            document.getElementById("select2-CmbSub6-container").innerHTML='Seleccione el Subgrupo 6';
        }
        var CmbSub3=document.getElementById("CmbSub3").value;
        $('#CmbSub4').select2({
		  
            placeholder: 'Selecciona el sub grupo 4',
            ajax: {
              url: '../../general/buscadores/sub4.search.php?idSub3='+CmbSub3,
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                  
                return {                     
                  results: data
                };
              },
             cache: true
            }
          });
    }
    if(idAccion=='4'){
        
        
        document.getElementById("CmbSub6").value='';
        if(document.getElementById("select2-CmbSub6-container")){
            document.getElementById("select2-CmbSub6-container").innerHTML='Seleccione el Subgrupo 6';
        }
        var CmbSub4=document.getElementById("CmbSub4").value;
        $('#CmbSub6').select2({
		  
            placeholder: 'Selecciona el sub grupo 6',
            ajax: {
              url: '../../general/buscadores/sub6.search.php?idSub4='+CmbSub4,
              dataType: 'json',
              delay: 250,
              processResults: function (data) {
                  
                return {                     
                  results: data
                };
              },
             cache: true
            }
          });
    }
    
      
    
}

function ValidaReferencia(Tabla=1){
    
    var TxtReferencia=document.getElementById('TxtReferencia').value;
    
    var form_data = new FormData();
        
        form_data.append('Accion', 1);
        form_data.append('Tabla', Tabla);
        form_data.append('TxtReferencia', TxtReferencia);
        
        $.ajax({
        url: '../inventarios/procesadores/inventarios.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            var respuestas = data.split(';');
            if(respuestas[0]=="E1"){
                alertify.error(respuestas[1]);
                
                posiciona('TxtReferencia');
                
            }else if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
               
            }else{
                alertify.alert(data);
                
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
            alert(thrownError);
          }
      })  

}

function CrearProductoVenta(ModuloQueInvoca=''){
    var idModal ="ModalAccionesPOS";
    var CmbDepartamento=document.getElementById('CmbDepartamento').value;
    var CmbSub1=document.getElementById('CmbSub1').value;
    var CmbSub2=document.getElementById('CmbSub2').value;
    var CmbSub3=document.getElementById('CmbSub3').value;
    var CmbSub4=document.getElementById('CmbSub4').value;
    var CmbSub6=document.getElementById('CmbSub6').value;
    
    var TxtNombre=document.getElementById('TxtNombre').value;
    var TxtReferencia=document.getElementById('TxtReferencia').value;
    var TxtExistencias=document.getElementById('TxtExistencias').value;
    var TxtPrecioVenta=document.getElementById('TxtPrecioVenta').value;
    var TxtPrecioMayorista=document.getElementById('TxtPrecioMayorista').value;
    var TxtCostoUnitario=document.getElementById('TxtCostoUnitario').value;
    var CmbIVA=document.getElementById('CmbIVA').value;
    var CmbCuentaPUC=document.getElementById('CmbCuentaPUC').value;
    var TxtCodigoBarras=document.getElementById('TxtCodigoBarras').value;
    
    var form_data = new FormData();
        
        form_data.append('Accion', 2);
        form_data.append('CmbDepartamento', CmbDepartamento);
        form_data.append('CmbSub1', CmbSub1);
        form_data.append('CmbSub2', CmbSub2);
        form_data.append('CmbSub3', CmbSub3);
        form_data.append('CmbSub4', CmbSub4);
        form_data.append('CmbSub6', CmbSub6);
        form_data.append('TxtReferencia', TxtReferencia);
        form_data.append('TxtNombre', TxtNombre);
        form_data.append('TxtExistencias', TxtExistencias);
        form_data.append('TxtPrecioVenta', TxtPrecioVenta);
        form_data.append('TxtPrecioMayorista', TxtPrecioMayorista);
        form_data.append('TxtCostoUnitario', TxtCostoUnitario);
        form_data.append('CmbIVA', CmbIVA);
        form_data.append('CmbCuentaPUC', CmbCuentaPUC);
        form_data.append('TxtCodigoBarras', TxtCodigoBarras);
        
        
        $.ajax({
        url: '../inventarios/procesadores/inventarios.process.php',
        //dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(data){
            document.getElementById("BntModalPOS").disabled=false;
            document.getElementById("BntModalPOS").value="Guardar";
            var respuestas = data.split(';');
            if(respuestas[0]=="E1"){
                alertify.alert(respuestas[1]);                
                MarqueErrorElemento(respuestas[2]);
                
            }else if(respuestas[0]=="OK"){
                alertify.success(respuestas[1]);
               if(ModuloQueInvoca==1){//Invoca el pos
                   CierraModal(idModal);
                   document.getElementById('Codigo').value=respuestas[1];
                   //AgregarItem();
               }
            }else{
                alertify.alert(data);
                
            }
            
            
        },
        error: function (xhr, ajaxOptions, thrownError) {
            document.getElementById("BntModalPOS").disabled=false;
            document.getElementById("BntModalPOS").value="Guardar";
            alert(xhr.status);
            alert(thrownError);
          }
      })  
}

function MarqueErrorElemento(idElemento){
    console.log(idElemento);
    if(idElemento==undefined){
       return; 
    }
    document.getElementById(idElemento).style.backgroundColor="pink";
    document.getElementById(idElemento).focus();
}
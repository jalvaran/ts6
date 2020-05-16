/**
 * Controlador para funciones generales de la aplicacion
 * JULIAN ALVARAN 2020-05-16
 * TECHNO SOLUCIONES SAS 
 * 317 774 0609
 */
function openModal(idModal){
    var id="#"+idModal;
    $(id).modal();
}

function closeModal(idModal){
    
    $("#"+idModal).modal('hide');//ocultamos el modal
    $('body').removeClass('modal-open');//eliminamos la clase del body para poder hacer scroll
    $('.modal-backdrop').remove();//eliminamos el backdrop del modal
}
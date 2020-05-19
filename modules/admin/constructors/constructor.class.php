<?php

include_once("constructores/paginas_constructor.php");

class AdminConstruct extends PageConstruct{
    
    public $obCon;
    
    
    public function get_form_locals($item_id,$route_view) {
        $dataLocal=$this->obCon->DevuelveValores("locales", "ID", $item_id);
        
        $form_identify=$this->obCon->getUniqId("frm_");
        $html='<div class="panel panel-default">
                <div class="panel-head">
                    <div class="panel-title">
                        <span class="panel-title-text">Agregar o Editar un local</span>
                    </div>
                </div>
                <div class="panel-body">
                         <form id="frm_save_local" method="post">
                        <div class="form-body">
                            <div class="form-heading">Información General</div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Nombre</label>
                                        <input id="Nombre" value="'.$dataLocal["Nombre"].'" type="text" class="form-control frm_local" placeholder="Nombre">
                                        <span class="form-text">Por favor ingrese el nombre</span> 
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Dirección</label>
                                        <input id="Direccion" value="'.$dataLocal["Direccion"].'" type="text" class="form-control frm_local" placeholder="Dirección">
                                        <span class="form-text">Por favor digite la dirección</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Teléfono</label>
                                        <input id="Telefono" value="'.$dataLocal["Telefono"].'" type="text" class="form-control frm_local" placeholder="Teléfono">
                                        <span class="form-text">Por favor digite el Teléfono</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Indicativo</label>
                                        <input id="Indicativo" value="'.$dataLocal["Indicativo"].'" type="text" class="form-control frm_local" placeholder="Indicativo">
                                        <span class="form-text">Por favor digite el Indicativo</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Whatsapp</label>
                                        <input id="Whatsapp" value="'.$dataLocal["Whatsapp"].'" type="text" class="form-control frm_local" placeholder="Whatsapp">
                                        <span class="form-text">Por favor digite el Whatsapp</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">id Telegram</label>
                                        <input id="idTelegram" value="'.$dataLocal["idTelegram"].'" type="text" class="form-control frm_local" placeholder="id Telegram">
                                        <span class="form-text">Por favor digite el id del Telegram</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Propietario</label>
                                        <input id="Propietario" value="'.$dataLocal["Propietario"].'" type="text" class="form-control frm_local" placeholder="Propietario">
                                        <span class="form-text">Por favor digite el Propietario</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Tarifa</label>
                                        <input id="Tarifa" type="number" value="'.$dataLocal["Tarifa"].'" class="form-control frm_local" placeholder="Tarifa">
                                        <span class="form-text">Por favor digite el Tarifa</span>
                                    </div>
                                </div>

                                ';
                    $sql="SELECT * FROM ciudades";
                    $query=$this->obCon->Query($sql);
                    $html.='<div class="col-md-4">

                                <div class="form-group">
                                    <label class="col-form-label">Ciudad </label>
                                    <select id="idCiudad" class="form-control frm_local">
                                ';
                    while($datosConsulta= $this->obCon->FetchAssoc($query)){
                            $sel="";
                            if($datosConsulta["ID"]==$dataLocal["idCiudad"]){
                                $sel="selected";
                            }
                        $html.='<option value="'.$datosConsulta["ID"].'" '.$sel.' >'.$datosConsulta["Nombre"].'</option>';
                    }

                    $html.='</select>
                            </div>
                            </div>';

                    $html.='
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Descripción</label>
                                        <textarea id="Descripcion" type="text" class="form-control frm_local" placeholder="Descripción">'.$dataLocal["Descripcion"].'</textarea>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">@</span>
                                            </div>
                                            <input id="Email" value="'.$dataLocal["Email"].'" type="email" class="form-control frm_local" placeholder="Email">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Password</label>
                                        <input id="Password" value="'.$dataLocal["Password"].'" type="text" class="form-control frm_local" placeholder="Password">

                                    </div>
                                </div>

                            </div>

                            <div class="form-seperator-dashed"></div>
                            <h3 class="form-heading">Datos de Configuración</h3>
                            <div class="row">';

                            $sql="SELECT * FROM catalogo_categorias WHERE Estado=1";
                            $query=$this->obCon->Query($sql);
                            $html.='<div class="col-md-4">

                                        <div class="form-group">
                                            <label class="col-form-label">Categoría </label>
                                            <select id="idCategoria" class="form-control frm_local">
                                        ';
                            while($datosCategorias= $this->obCon->FetchAssoc($query)){
                                $sel="";
                                if($datosCategorias["ID"]==$dataLocal["idCategoria"]){
                                    $sel="selected";
                                }
                                $html.='<option value="'.$datosCategorias["ID"].'" '.$sel.' >'.$datosCategorias["Nombre"].' </option>';
                            }

                            $html.='</select>
                                    </div>
                                    </div>';

                            $sql="SELECT * FROM html_css_themes";
                            $query=$this->obCon->Query($sql);
                            $html.='<div class="col-md-4">

                                        <div class="form-group">
                                            <label class="col-form-label">Color para interfaz </label>
                                            <select id="theme_id" class="form-control frm_local">
                                        ';
                            while($datosConsulta= $this->obCon->FetchAssoc($query)){
                                $sel="";
                                if($datosConsulta["id"]==$dataLocal["theme_id"]){
                                    $sel="selected";
                                }
                                $html.='<option value="'.$datosConsulta["id"].'" '.$sel.'  >'.$datosConsulta["css"].'</option>';
                            }

                            $html.='</select>
                                    </div>
                                    </div>';

                            $sql="SELECT * FROM pages";
                            $query=$this->obCon->Query($sql);
                            $html.='<div class="col-md-4">

                                        <div class="form-group">
                                            <label class="col-form-label">Página Inicial </label>
                                            <select id="page_initial" class="form-control frm_local">
                                        ';
                            while($datosConsulta= $this->obCon->FetchAssoc($query)){
                                $sel="";
                                if($datosConsulta["id"]==$dataLocal["page_initial"]){
                                    $sel="selected";
                                }
                                
                                $html.='<option value="'.$datosConsulta["id"].'" '.$sel.' >'.$datosConsulta["name"].'</option>';
                            }

                            $html.='</select>
                                    </div>
                                    </div>';
                            if($dataLocal["header_class"]==''){
                                $dataLocal["header_class"]="dark";
                            }
                            if(!isset($dataLocal["slider_class"])){
                                $dataLocal["slider_class"]="dark";
                            }
                            if(!isset($dataLocal["virtual_shop"])){
                                $dataLocal["virtual_shop"]=1;
                            }
                            if(!isset($dataLocal["Orden"])){
                                $dataLocal["Orden"]=1;
                            }
                            if(!isset($dataLocal["keywords"])){
                                $dataLocal["keywords"]='';
                            }
                            if(!isset($dataLocal["UrlLocal"])){
                                $dataLocal["UrlLocal"]='';
                            }
                            if(!isset($dataLocal["title_page"])){
                                $dataLocal["title_page"]='';
                            }
                            
                            if(!isset($dataLocal["Alcance"])){
                                $dataLocal["Alcance"]=1;
                            }
                            if(!isset($dataLocal["Estado"])){
                                $dataLocal["Estado"]='1';
                            }
                           $html.='<div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Orden</label>
                                        <input id="Orden" type="number" value="'.$dataLocal["Orden"].'"  class="form-control frm_local" placeholder="Orden">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">header_class</label>
                                        <input id="header_class" value="'.$dataLocal["header_class"].'" type="text" class="form-control frm_local" placeholder="header_class">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">slider_class</label>
                                        <input id="slider_class" value="'.$dataLocal["slider_class"].'" type="text" class="form-control frm_local" placeholder="slider_class">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">keywords</label>
                                        <input id="keywords" value="'.$dataLocal["keywords"].'" type="text" class="form-control" placeholder="keywords">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">virtual_shop</label>
                                        <input id="virtual_shop" value="'.$dataLocal["virtual_shop"].'"  type="number" class="form-control frm_local" placeholder="virtual_shop">                                                        
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Url del Local</label>
                                        <input id="UrlLocal" value="'.$dataLocal["UrlLocal"].'" type="text" class="form-control frm_local" placeholder="Url Local">                                                        
                                    </div>
                                    <span class="form-text">URL corta para Acceder al local</span>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Título de la Página</label>
                                        <input id="title_page" value="'.$dataLocal["title_page"].'"  type="text" class="form-control frm_local" placeholder="Título de la Página">                                                        
                                    </div>
                                    <span class="form-text">Título que mostrará el explorador en la parte superior</span>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Alcance</label>
                                        <select id="Alcance" class="form-control frm_local" placeholder="Alcance">'; 
                                        $sel="";
                                        if($dataLocal["Alcance"]==1){
                                            $sel="selected";
                                        }
                                        $html.='<option value="1" '.$sel.'>Local</option>';
                                        $sel="";
                                        if($dataLocal["Alcance"]==0){
                                            $sel="selected";
                                        }
                                        $html.='<option value="0" '.$sel.'>Nacional</option>';                                                        

                                    $html.='</select>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Estado</label>
                                        <select id="Estado" class="form-control frm_local" placeholder="Estado">'; 
                                        $sel="";
                                        if($dataLocal["Estado"]==1){
                                            $sel="selected";
                                        }
                                        $html.='    <option value="1" '.$sel.' >Habilitado</option>';
                                        $sel="";
                                        if($dataLocal["Estado"]==0){
                                            $sel="selected";
                                        }
                                        $html.='    <option value="0" '.$sel.'>Deshabilitado</option>';                                                        

                                       $html.=' </select>
                                    </div>

                                </div>

                            </div>
                            </form>


                            <div class="form-seperator-dashed"></div>
                            <h3 class="form-heading">Imágenes</h3>
                            <div class="row">

                                <div class="col-sm-6">
                                    <div class="panel">
                                        <div class="panel-head">
                                            <h5 class="panel-title">Selecciona el logo del local</h5>
                                        </div>
                                        <div class="panel-body">
                                            <form data-form_identify="'.$form_identify.'" action="/" class="dropzone dz-clickable" id="logoLocal"><div class="dz-default dz-message"><span><i class="icon-plus"></i>Drop files here or click here to upload.<br> Upload Any Files.</span></div></form>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="panel">
                                        <div class="panel-head">
                                            <h5 class="panel-title">Selecciona una foto del local</h5>
                                        </div>
                                        <div class="panel-body">
                                            <form data-form_identify="'.$form_identify.'" action="/" class="dropzone dz-clickable" id="fotoLocal"><div class="dz-default dz-message"><span><i class="icon-plus"></i>Drop files here or click here to upload.<br> Upload Any Files.</span></div></form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                </div>
                <div class="panel-footer text-right">                                    
                    <button id="btn_form_save" data-route_view="'.$route_view.'" data-table_id="3" data-form_identify="'.$form_identify.'" class="btn btn-primary btn-pill"> - Guardar - </button>
                </div>


            </div>';
        return($html);
    }
    
    /**
     * Fin Clase
     */
}

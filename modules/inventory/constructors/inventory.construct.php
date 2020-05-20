<?php

include_once("constructores/paginas_constructor.php");

class InventoryConstruct extends PageConstruct{
    
    public $obCon;    
       
    public function get_form_classifications($item_id,$route_view) {
        $obCon=new conexion();
        $sql="SELECT * FROM inventarios_clasificacion WHERE ID = '$item_id'";        
        $query=$obCon->QueryExterno($sql, HOST, USER, PW, $this->dataClient["db"], "");
        $dataClasified=$obCon->FetchAssoc($query);
                
        $html='<div class="panel panel-default">
                <div class="panel-head">
                    <div class="panel-title">
                        <span class="panel-title-text">Agregar o Editar una clasificacion del inventario</span>
                    </div>
                </div>
                <div class="panel-body">
                         
                        <div class="form-body">
                            <div class="form-heading">Clasificaciones</div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Clasificación</label>
                                        <input id="Clasificacion" value="'.$dataClasified["Clasificacion"].'" type="text" class="form-control frm_ts6" placeholder="Clasificacion">
                                        <span class="form-text">Por favor ingrese el nombre de la clasificacion</span> 
                                    </div>
                                </div>
                                
                                
                                ';
                    
                       
                       $html.='
                                    

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Estado</label>
                                        <select id="Estado" class="form-control frm_local" placeholder="Estado">'; 
                                        $sel="";
                                        if($dataClasified["Estado"]==1 or $dataClasified["Estado"]==''){
                                            $sel="selected";
                                        }
                                        $html.='    <option value="1" '.$sel.' >Habilitado</option>';
                                        $sel="";
                                        if($dataClasified["Estado"]=='0'){
                                            $sel="selected";
                                        }
                                        $html.='    <option value="0" '.$sel.'>Deshabilitado</option>';                                                        

                                       $html.=' </select>
                                    </div>

                                </div>

                            
                            
                </div>
                <div class="panel-footer text-right">                                    
                    <button id="btn_form_save" data-table_id="1" data-route_view="'.$route_view.'" class="btn btn-primary btn-pill"> - Guardar - </button>
                </div>


            </div>';
        return($html);
    }
    
    public function get_form_inventory($item_id,$route_view) {
        $obCon=new conexion();
        $sql="SELECT * FROM productos_servicios WHERE ID = '$item_id'";        
        $query=$obCon->QueryExterno($sql, HOST, USER, PW, $this->dataClient["db"], "");
        $dataProducts=$obCon->FetchAssoc($query);
        
        if($item_id==''){
            
            $dataProducts["ID"]=$obCon->getUniqId();
            $dataProducts["Referencia"]=$dataProducts["ID"];
            $dataProducts["Nombre"]="";
            $dataProducts["PrecioVenta"]="";
            $dataProducts["DescripcionCorta"]="";
            $dataProducts["DescripcionLarga"]="";
            $dataProducts["Orden"]="1";
            $dataProducts["Estado"]="1";
            
        }        
        $html='<div class="row">
                        
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-head">
                                    <div class="panel-title">
                                        <span class="panel-title-text">Productos o Servicios</span>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    
                                        <div class="form-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">ID</label>
                                                        <input id="ID" type="text" readonly value="'.$dataProducts["ID"].'" class="form-control" placeholder="ID">
                                                        <span class="form-text">Identificador del producto o servicios</span> 
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Referencia</label>
                                                        <input id="Referencia" type="text" value="'.$dataProducts["Referencia"].'" class="form-control" placeholder="Referencia">
                                                        <span class="form-text">Escribe la referencia del producto</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Nombre</label>
                                                        <input id="Nombre" type="text" value="'.$dataProducts["Nombre"].'" class="form-control" placeholder="Nombre">
                                                        <span class="form-text">Escribe el nombre del producto o servicio</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Precio de Venta</label>
                                                        <input id="PrecioVenta" type="number" value="'.$dataProducts["PrecioVenta"].'" class="form-control" placeholder="PrecioVenta" min=0 max=1000000>
                                                        <span class="form-text">Escribe el precio de venta del producto o servicio</span>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Descripción Corta</label>
                                                        <div class="input-group">
                                                            
                                                            <textarea id="DescripcionCorta" class="form-control" placeholder="Descripcion corta">'.$dataProducts["DescripcionCorta"].'</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Descripción Larga</label>
                                                        <div class="input-group">                                                            
                                                            <textarea id="DescripcionLarga" class="form-control" placeholder="Descripcion Larga">'.$dataProducts["DescripcionLarga"].'</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Clasificación</label>';
        
                                    $html.='            <select id="idClasificacion" class="form-control">
                                                            <option value="">Selecciona una clasificación</option>

                                            ';
                                    if(!isset($dataProducts["idClasificacion"])){
                                        $dataProducts["idClasificacion"]="";
                                    }
                                    $sql="SELECT * FROM inventarios_clasificacion";        
                                    $query=$obCon->QueryExterno($sql, HOST, USER, PW, $this->dataClient["db"], "");
                                    while($dataClasified=$obCon->FetchAssoc($query)){
                                        $sel="";
                                        if($dataClasified["ID"]==$dataProducts["idClasificacion"]){
                                            $sel="selected";
                                        }
                                        $html.='<option '.$sel.' value="'.$dataClasified["ID"].'">'.$dataClasified["Clasificacion"].'</option>';
                                    }
                                    
                                                           
                                    $html.='            </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Orden</label>
                                                        <input id="Orden" type="number" value="'.$dataProducts["Orden"].'"  class="form-control frm_local" placeholder="Orden">                                                        
                                                    </div>
                                                </div>';
                                    
                                    $html.='
                                    

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="col-form-label">Estado</label>
                                                        <select id="Estado" class="form-control frm_local" placeholder="Estado">'; 
                                                        $sel="";
                                                        if($dataClasified["Estado"]==1 or $dataClasified["Estado"]==''){
                                                            $sel="selected";
                                                        }
                                                        $html.='    <option value="1" '.$sel.' >Habilitado</option>';
                                                        $sel="";
                                                        if($dataClasified["Estado"]=='0'){
                                                            $sel="selected";
                                                        }
                                                        $html.='    <option value="0" '.$sel.'>Deshabilitado</option>';                                                        

                                                       $html.=' </select>
                                                    </div>

                                                </div>
                                            </div>
                                            
                                        </div>
                                    
                                </div>
                            
                            <div class="panel-footer text-right">       
                                <button id="btn_cancel_save" data-route_view="'.$route_view.'" class="btn btn-default btn-pill"> - Cancelar - </button>
                                <button id="btn_form_save" data-table_id="2" data-route_view="'.$route_view.'" class="btn btn-primary btn-pill"> - Guardar - </button>
                            </div>
                            </div>
                        </div>
                       
                            <div class="col-lg-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="panel panel-default">
                                            <div class="panel-body">

                                                <div class="panel">
                                                    <div class="panel-head">
                                                        <h5 class="panel-title">Sube imágenes para el producto</h5>
                                                    </div>
                                                    <div class="panel-body">
                                                        <form data-product_id="'.$dataProducts["ID"].'" action="/" class="dropzone dz-clickable" id="imgs_product"><div class="dz-default dz-message"><span><i class="icon-plus"></i>Arrastre las imágenes en ésta área<br> Suba sólo imágenes.</span></div></form>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="panel panel-default">
                                            <div class="panel-head">
                                                <h5 class="panel-title">Imágenes Agregadas al producto</h5>
                                            </div>
                                            <div class="panel-body">

                                                <div id="divImagesProduct">
                                                    
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>    
                            </div>

                            
                            
                       
                        
                    </div>';
        return($html);
    }
    
    /**
     * Fin Clase
     */
}

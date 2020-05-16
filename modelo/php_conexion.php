<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once 'php_mysql_i.php';

class conexion extends db_conexion{
    
    
    function __construct(){
        
        
    }
    
    public function getUniqId($prefijo='') {
        return (str_replace(".","",uniqid($prefijo, true)));
    }
     
    public function getDataBaseLocal($idLocal) {
        $sql="SELECT db FROM locales WHERE ID='$idLocal'";
        $Datos= $this->FetchAssoc($this->Query($sql));
        return($Datos["db"]);
    }
    
    public function logVisit($client_user_id,$idPantalla,$idLocal,$IP) {
        if($client_user_id<>''){
            
       
            $tab="log_visits";
            $Datos["client_user_id"]=$client_user_id;
            $Datos["idPantalla"]=$idPantalla;
            $Datos["idLocal"]=$idLocal;
            $Datos["IP"]=$IP;
            $Datos["Created"]=date("Y-m-d H:i:s");
            $sql= $this->getSQLInsert($tab, $Datos);
            $this->Query($sql);
         }
        
    }
    
    public function validaTokenGoogle($token,$action,$Key) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('secret' => $Key, 'response' => $token)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        $arrResponse = json_decode($response, true);
        return($arrResponse);
    }
    
    public function VerificaSesion($Token_user) {
        if(isset($_SESSION["idLocal"])){
            if($_SESSION["Token"]==$Token_user){
                $DatosSesion["Estado"]="OK";
                $DatosSesion["Mensaje"]="Sesion iniciada correctamente";
            }else{
                $DatosSesion["Estado"]="E1";
                $DatosSesion["Mensaje"]="El token ha cambiado, debe iniciar sesion de nuevo";
            }
            
        }else{
            $DatosSesion["Estado"]="E1";
            $DatosSesion["Mensaje"]="No se ha iniciado sesion";
        }
        return($DatosSesion);
    }
}

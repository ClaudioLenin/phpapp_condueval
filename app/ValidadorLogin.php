<?php
include_once 'RepositorioPersona.php';
class ValidadorLogin{
    private $usuario;
    private $error;
    
    public function __construct($cedpersona,$clave,$conexion) {
        $this->error='';
        
        if(!$this->variable_iniciada($cedpersona)||!$this->variable_iniciada($clave)){
            $this-> usuario = null;
            $this-> error = "Ingrese Usuario y Contraseña";
        } else {
            $this->usuario = RepositorioPersona::obtener_usuario($conexion, $cedpersona);
            if(is_null($this->usuario)|| !$this->usuario->getClave()){
                $this->error="Datos Incorrectos";
            }
        }
    }
    
    private function variable_iniciada($variable){
        if(isset($variable)&&!empty($variable)){
            return true;
        }else{
            return false;
        }
    }
    public function obtener_usuario(){
        return $this->usuario;
    }
    public function obtener_error(){
        return $this->error;
    }
    public function mostrar_error(){
        if($this->error !== ''){
            echo "<br><div class='alert-danger' role='alert'>";
            echo $this->error;
            echo "</div><br>";
        }
    }
}


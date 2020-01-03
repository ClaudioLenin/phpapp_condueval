<?php
class Persona{
    private $codpersona;
    private $cedpersona;
    private $apepersona;
    private $nompersona;
    private $clave;


    public function __construct($codpersona,$cedpersona,$apepersona,$nompersona,$clave) {
        $this->codpersona=$codpersona;
        $this->cedpersona=$cedpersona;
        $this->apepersona=$apepersona;
        $this->nompersona=$nompersona;
        $this->clave=$clave;
    }
    public function getCodpersona(){
        return $this->codpersona;
    }
    public function getCedpersona(){
        return $this->cedpersona;
    }
    public function getApepersona(){
        return $this->apepersona;
    }
    public function getNompersona(){
        return $this->nompersona;
    }
    public function getClave(){
        return $this->clave;
    }
    public function getActivo(){
        return $this->activo;
    }
    public function setCodpersona($codpersona){
        $this->codpersona=$codpersona;
    }
    public function setCedpersona($cedpersona){
        $this->codpersona=$cedpersona;
    }
    public function setApepersona($apepersona){
        $this->codpersona=$apepersona;
    }
    public function setNompersona($nompersona){
        $this->codpersona=$nompersona;
    }
    public function setClave($clave){
        $this->codpersona=$clave;
    }
}
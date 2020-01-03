<?php
class Conexion{
    private static $conexion;
    
    public static function abrir_conexion(){
        if(!isset(self::$conexion)){
            try {
                include 'dbDetalles.php';
                self::$conexion = new PDO("pgsql:host=$host; dbname=$dbname",$user,$password);
                self::$conexion->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
                //print 'CONEXION ABIERTA';
            } catch (PDOException $ex) {
                print 'ERROR: '.$ex->getMessage()."<br>";
                die();
            }
        }
    }
    public static function cerrar_conexion(){
        if(isset(self::$conexion)){
            self::$conexion=null;
            //print 'CONEXION CERRADA';
            
        }
    }
    public static function obtener_conexion(){
        return self::$conexion;
    }
}?>

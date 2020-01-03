<?php

class RepositorioPersona {

    public static function obtener_todos($conexion) {
        $usuarios = array();

        if (isset($conexion)) {

            try {
                include_once 'Persona.php';

                $sql = "SELECT * FROM tpersona";

                $sentencia = $conexion->prepare($sql);
                $sentencia -> execute();
                $resultado = $sentencia->fetchAll();

                if (count($resultado)) {
                    foreach ($resultado as $fila) {
                        $usuarios[] = new Persona($fila['codpersona'], $fila['cedpersona'], $fila['apepersona'], $fila['nompersona'], $fila['clave']);
                    }
                } else {
                    print 'No hay usuarios';
                }
            } catch (Exception $ex) {
                print 'ERROR'.$ex->getMessage();
            }
        }
        return $usuarios;
    }
    public static function obtener_usuario($conexion,$cedpersona,$clave){
        $usuario = null;
        if(isset($conexion)){
            try {
                $sql = "SELECT * FROM tpersona WHERE cedpersona = :cedpersona AND clave = :clave";
                $sentencia = $conexion -> prepare($sql);
                $sentencia -> bindParam(":cedpersona",$cedpersona,PDO::PARAM_STR);
                $sentencia -> bindParam(":clave",$clave,PDO::PARAM_STR);
                $sentencia -> execute();
                
                $resultado = $sentencia -> fetch();
                
                if(!empty($resultado)){
                    $usuario = new Persona($fila['codpersona'], $fila['cedpersona'], $fila['apepersona'], $fila['nompersona'], $fila['clave']);
                }
            } catch (Exception $ex) {
                print 'ERROR'.$ex->getMessage();
            }
        }
    }

}


<?php
require_once 'app/conexion.php';
require_once 'app/ControlSesion.php';
session_start();
$cedpersona = $_POST['cedula'];
$clave = $_POST['contrasenia'];
if (isset($cedpersona) && isset($clave)) {
    try {
        $sql = "SELECT * FROM tpersona WHERE cedpersona = :cedpersona AND clave = :clave";
        Conexion::abrir_conexion();
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":cedpersona", $cedpersona, PDO::PARAM_STR);
        $sentencia->bindParam(":clave", $clave, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetch();
        
        $sql = "SELECT nomperiodo, codperiodo FROM tperiodo ORDER BY codperiodo desc limit 1";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->execute();
        $periodo = $sentencia->fetch();
//        $codperiodo = 44; //<----
        if (!empty($resultado) && ($resultado['cedpersona']==$cedpersona)&&($resultado['clave']==$clave)) {
            ControlSesion::iniciar_sesion($resultado['codpersona'],$resultado['nompersona'], $resultado['apepersona'], $resultado['cedpersona'], $resultado['tippersona'],$periodo['nomperiodo'],$periodo['codperiodo']);
            Conexion::cerrar_conexion();
            if($_SESSION['tippersona'] == 'Administrativo'){
                header("Location:Administrador/Pregunta.php");
            }else if($_SESSION['tippersona'] == 'Docente'){
                header("Location:Docente/Pregunta.php");
            }else if($_SESSION['tippersona'] == 'Estudiante'){
                header("Location:Estudiante/Examen.php");
            }else {
                header("Location:index.php");
            }
        } else {
            header("Location:index.php");
        }
    } catch (Exception $ex) {
        header("Location:index.php");
    }
}
?>
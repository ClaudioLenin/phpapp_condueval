<?php

require '../../app/conexion.php';
date_default_timezone_set('Etc/GMT+5');
$hoy = array();
$hoy = date("Y-m-d H:i:00", time());
Conexion::abrir_conexion();
$sql = "SELECT * FROM texamen "
        . "WHERE codexamen = :codexamen";
$sentencia = Conexion::obtener_conexion()->prepare($sql);
$sentencia->bindParam(":codexamen", $_POST['codexamen'], PDO::PARAM_STR);
$sentencia->execute();
$hoja = $sentencia->fetch();
Conexion::cerrar_conexion();

if (strtotime($hoja['fechaejecucion']) > strtotime($hoja['fechafin']) || strtotime($hoy) > strtotime($hoja['fechafin'])) {
    $estado = "DESHABILITADO";
} else {
    $estado = "HABILITADO";
}
Conexion::abrir_conexion();
$sql = "UPDATE texamen SET estado = :estado WHERE codexamen = :codexamen";
$sentencia = Conexion::obtener_conexion()->prepare($sql);
$sentencia->bindParam(":estado", $estado, PDO::PARAM_STR);
$sentencia->bindParam(":codexamen", $hoja['codexamen'], PDO::PARAM_STR);
$sentencia->execute();
Conexion::cerrar_conexion();

echo $hoy . $estado;

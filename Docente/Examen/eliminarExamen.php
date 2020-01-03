<?php

require '../../app/conexion.php';
$cod = $_POST['id'];
if (isset($cod)) {
    Conexion::abrir_conexion();
    $sql = "DELETE FROM texamen WHERE codexamen = :codexamen";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codexamen", $cod, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
}
    
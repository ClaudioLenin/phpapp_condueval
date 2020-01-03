<?php

require '../../app/conexion.php';
session_start();
$codperiodoseccion = $_POST['codperiodoseccion'];
if (isset($codperiodoseccion)) {
    Conexion::abrir_conexion();
    $sql = "SELECT codperiodoseccionparalelo,codparalelo FROM tperiodoseccionparalelo WHERE codperiodoseccion = :codperiodoseccion";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codperiodoseccion", $codperiodoseccion, PDO::PARAM_STR);
    $sentencia->execute();
    $paralelos = $sentencia->fetchAll();

    $json = array();
    foreach ($paralelos as $fila) {
        $json[] = array(
            'codperiodoseccionparalelo' => $fila['codperiodoseccionparalelo'],
            'codparalelo' => $fila['codparalelo']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}


<?php

require '../../app/conexion.php';
$codperiodo = $_POST['codperiodo'];
if (isset($codperiodo)) {
    Conexion::abrir_conexion();
    $sql = "SELECT codperiodoseccion,nomseccion FROM tperiodoseccion INNER JOIN tseccion ON tperiodoseccion.codseccion = tseccion.codseccion WHERE codperiodo = :codperiodo";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codperiodo", $codperiodo, PDO::PARAM_STR);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    $json = array();
    foreach ($resultado as $fila) {
        $json[] = array(
            'codperiodoseccion' => $fila['codperiodoseccion'],
            'nomseccion' => $fila['nomseccion']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}


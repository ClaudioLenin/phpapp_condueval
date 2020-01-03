<?php

require '../../app/conexion.php';

$search = $_POST['search'];
$coddocentemateria = $_POST['coddocentemateria'];

if (!empty($search)) {
    Conexion::abrir_conexion();
    $sql = "SELECT * FROM tpregunta WHERE pregunta LIKE :search OR tipo LIKE :search AND coddocentemateria = :coddocentemateria";
    $keyword = $search."%";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":search", $keyword, PDO::PARAM_STR);
    $sentencia->bindParam(":coddocentemateria", $coddocentemateria, PDO::PARAM_STR);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    $json = array();
    foreach ($resultado as $fila){
        $json[] = array(
            'codpregunta' => $fila['codpregunta'],
            'pregunta' => $fila['pregunta'],
            'valor' => $fila['valor'],
            'tipo' => $fila['tipo'],
            'imagen' => $fila['imagen']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
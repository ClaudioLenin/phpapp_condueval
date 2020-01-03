<?php

require '../../app/conexion.php';
session_start();
$codperiodoseccionparalelo = $_POST['codperiodoseccionparalelo'];
if (isset($codperiodoseccionparalelo)) {
    Conexion::abrir_conexion();
    $sql = "SELECT * FROM tdocentemateria "
            . "INNER JOIN tmateria ON tdocentemateria.codmateria = tmateria.codmateria "
            . "INNER JOIN tpersona ON tpersona.codpersona = tdocentemateria.codpersona "
            . "WHERE codperiodoseccionparalelo = :codperiodoseccionparalelo";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codperiodoseccionparalelo", $codperiodoseccionparalelo, PDO::PARAM_STR);
    //$sentencia->bindParam(":codpersona",$_SESSION['codpersona'], PDO::PARAM_STR);
    $sentencia->execute();
    $materias = $sentencia->fetchAll();
    
    $json = array();
    foreach ($materias as $fila) {
        $json[] = array(
            'coddocentemateria' => $fila['coddocentemateria'],
            'nommateria' => $fila['nommateria']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
<?php

require '../../app/conexion.php';

if (isset($_POST['coddocentemateria'])) {
    Conexion::abrir_conexion();
    $sql = "SELECT tpersona.nompersona,tpersona.apepersona FROM tpersona "
            . "INNER JOIN tdocentemateria ON tdocentemateria.codpersona = tpersona.codpersona "
            . "WHERE tdocentemateria.coddocentemateria = :coddocentemateria";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":coddocentemateria", $_POST['coddocentemateria'], PDO::PARAM_STR);
    $sentencia->execute();
    $persona = $sentencia->fetch();
    Conexion::cerrar_conexion();
    $json = array();
    $json[] = array(
        'nompersona' => $persona['nompersona'],
        'apepersona' => $persona['apepersona']
    );
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

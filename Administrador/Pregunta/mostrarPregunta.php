<?php

require '../../app/conexion.php';
$coddocentemateria = $_POST['coddocentemateria'];
if ($coddocentemateria) {
    try {        
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM  tpregunta WHERE coddocentemateria = :coddocentemateria order by codpregunta desc";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":coddocentemateria", $coddocentemateria, PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll();
        Conexion::cerrar_conexion();

        $json = array();
        foreach ($resultado as $fila) {
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
        //echo $_POST['coddocentemateria'];
    } catch (Exception $ex) {
        echo 'ERROR: ' . $ex->getMessage();
    }
}


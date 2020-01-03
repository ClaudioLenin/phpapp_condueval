<?php

require '../../app/conexion.php';
if (isset($_POST['coddocentemateria'])) {
    Conexion::abrir_conexion();
    $sql = "SELECT DISTINCT tmateriaexamen.codexamen FROM tmateriaexamen INNER JOIN texamen ON texamen.codexamen = tmateriaexamen.codexamen WHERE tmateriaexamen.coddocentemateria = :coddocentemateria order by tmateriaexamen.codexamen desc";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":coddocentemateria", $_POST['coddocentemateria'], PDO::PARAM_STR);
    $sentencia->execute();
    $codexamenes = $sentencia->fetchAll();
    Conexion::cerrar_conexion();

    date_default_timezone_set('Etc/GMT+5');
    $hoy = array();
    $hoy = date("Y-m-d H:i:00", time());
    $json = array();
    foreach ($codexamenes as $exam) {
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM texamen WHERE codexamen = :codexamen ORDER BY texamen.codexamen desc";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
        $sentencia->execute();
        $resultado = $sentencia->fetchAll();
        Conexion::cerrar_conexion();
        foreach ($resultado as $fila) {

            if (strtotime($fila['fechaejecucion']) > strtotime($fila['fechafin']) || strtotime($hoy) >= strtotime($fila['fechafin'])) {
                $estado = "DESHABILITADO";
            } else {
                $estado = "HABILITADO";
            }
            Conexion::abrir_conexion();
            $sql = "UPDATE texamen SET estado = :estado WHERE codexamen = :codexamen";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":estado", $estado, PDO::PARAM_STR);
            $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
            $sentencia->execute();
            Conexion::cerrar_conexion();

            $json[] = array(
                'descripcion' => $fila['descripcion'],
                'fechaejecucion' => $fila['fechaejecucion'],
                'fechafin' => $fila['fechafin'],
                'numpreguntas' => $fila['numpreguntas'],
                'valoracion' => $fila['valoracion'],
                'estado' => $fila['estado'],
                'fechacreacion' => $fila['fechacreacion'],
                'codexamen' => $fila['codexamen']
            );
        }
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
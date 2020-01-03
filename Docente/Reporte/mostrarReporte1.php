<?php

require '../../app/conexion.php';

if (isset($_POST['coddocentemateria'])) {
    Conexion::abrir_conexion();
    $sql = "SELECT DISTINCT tmateriaexamen.codexamen FROM tmateriaexamen WHERE coddocentemateria = :coddocentemateria ORDER BY tmateriaexamen.codexamen ASC";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":coddocentemateria", $_POST['coddocentemateria'], PDO::PARAM_STR);
    $sentencia->execute();
    $codexamenes = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    $json = array();
    foreach ($codexamenes as $exam) {
        //PROMEDIO DE NOTAS DE CADA EXAMEN
        Conexion::abrir_conexion();
        $sql = "SELECT Count(*) AS cantidad,AVG(nota) AS promedio FROM tnotaexamen INNER JOIN tmateriaexamen "
                . "ON tnotaexamen.codme = tmateriaexamen.codme "
                . "WHERE tmateriaexamen.codexamen = :codexamen";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
        $sentencia->execute();
        $promedio = $sentencia->fetch();
        Conexion::cerrar_conexion();
        
        //CANTIDAD DE ALUMNOS POR CADA EXAMEN
        Conexion::abrir_conexion();
        $sql = "SELECT Count(*) AS alumnos FROM tmateriaexamen WHERE codexamen = :codexamen";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
        $sentencia->execute();
        $alumnos = $sentencia->fetch();
        Conexion::cerrar_conexion();
        
        //DATOS DE CADA EXAMEN
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM texamen WHERE codexamen = :codexamen";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
        $sentencia->execute();
        $examen = $sentencia->fetch();
        Conexion::cerrar_conexion();
        
        $json[] = array(
                'descripcion' => $examen['descripcion'],
                'fechaejecucion' => $examen['fechaejecucion'],
                'alumnos' => $alumnos['alumnos'],
		'cantidad' => $promedio['cantidad'],
                'promedio' => $promedio['promedio'],
                'codexamen' => $examen['codexamen'],
                'coddocentemateria' => $_POST['coddocentemateria']
            );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

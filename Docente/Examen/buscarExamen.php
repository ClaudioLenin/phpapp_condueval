<?php

require '../../app/conexion.php';

$search = $_POST['search'];
$coddocentemateria = $_POST['coddocentemateria'];

if (!empty($search)) {
    Conexion::abrir_conexion();
    $sql = "SELECT DISTINCT texamen.codexamen FROM texamen INNER JOIN tmateriaexamen 
        ON texamen.codexamen = tmateriaexamen.codexamen 
        WHERE tmateriaexamen.coddocentemateria = :coddocentemateria order by texamen.codexamen desc";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":coddocentemateria", $coddocentemateria, PDO::PARAM_STR);
    $sentencia->execute();
    $codexamenes = $sentencia->fetchAll();
    Conexion::cerrar_conexion();

    $json = array();
    foreach ($codexamenes as $code) {
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM texamen WHERE descripcion LIKE :search OR estado LIKE :search AND codexamen = :codexamen";
        $keyword = $search . "%";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":search", $keyword, PDO::PARAM_STR);
        $sentencia->bindParam(":codexamen", $code['codexamen'], PDO::PARAM_STR);
        $sentencia->execute();
        $examen = $sentencia->fetch();
        Conexion::cerrar_conexion();
        if (isset($examen) && $examen != null) {
            $json[] = array(
                'descripcion' => $examen['descripcion'],
                'fechaejecucion' => $examen['fechaejecucion'],
                'fechafin' => $examen['fechafin'],
                'numpreguntas' => $examen['numpreguntas'],
                'valoracion' => $examen['valoracion'],
                'estado' => $examen['estado'],
                'fechacreacion' => $examen['fechacreacion'],
                'codexamen' => $examen['codexamen']
            );
        }
        $examen = null;
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
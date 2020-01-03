<?php
require '../../app/conexion.php';
session_start();
try {
    Conexion::abrir_conexion();
    $sql = "SELECT DISTINCT tmateriaexamen.coddocentemateria FROM tmateriaexamen "
            . "INNER JOIN testudianteparalelo ON tmateriaexamen.codestudiante = testudianteparalelo.codestudianteparalelo "
            . "WHERE testudianteparalelo.codestudiante = :codestudiante";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codestudiante", $_SESSION['codpersona'], PDO::PARAM_STR);
    $sentencia->execute();
    $materias = $sentencia->fetchAll();
    if ($materias != null) {
        $json = array();
        foreach ($materias as $materia) {
            $sql = "SELECT * FROM tmateria INNER JOIN tdocentemateria "
                    . "ON tmateria.codmateria = tdocentemateria.codmateria "
                    . "WHERE tdocentemateria.coddocentemateria = :coddocentemateria";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":coddocentemateria", $materia['coddocentemateria'], PDO::PARAM_STR);
            $sentencia->execute();
            $nombres = $sentencia->fetchAll();
            foreach ($nombres as $nombre) {
                $json[] = array(
                    'coddocentemateria' => $materia['coddocentemateria'],
                    'nommateria' => $nombre['nommateria']
                );
            }
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    } else {
        echo 'NO TIENE EXAMENES ASIGNADOS';
    }
    Conexion::cerrar_conexion();
} catch (Exception $ex) {
    echo 'ERROR ' . $ex->getMessage();
    Conexion::cerrar_conexion();
}
?>
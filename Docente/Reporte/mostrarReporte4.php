<?php
require '../../app/conexion.php';
if (isset($_POST['codexamen'])) {
    Conexion::abrir_conexion();
    $sql = "SELECT tnotaexamen.nota,tmateriaexamen.codme,tmateriaexamen.codestudiante,tpersona.nompersona, tpersona.apepersona, tmateriaexamen.coddocentemateria  FROM tpersona 
        INNER JOIN testudianteparalelo ON tpersona.codpersona = testudianteparalelo.codestudiante 
        INNER JOIN tmateriaexamen ON testudianteparalelo.codestudianteparalelo = tmateriaexamen.codestudiante 
        INNER JOIN tnotaexamen ON tmateriaexamen.codme = tnotaexamen.codme 
        WHERE codexamen = :codexamen";
    
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codexamen", $_POST['codexamen'], PDO::PARAM_STR);
    $sentencia->execute();
    $listaestudiantes = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    $json = array();
    foreach ($listaestudiantes as $list) {
        $json[] = array(
                'nompersona' => $list['nompersona'],
                'apepersona' => $list['apepersona'],
                'nota' => $list['nota'],
                'codme' => $list['codme'],
                'codestudiante' => $list['codestudiante'],
                'coddocentemateria' => $list['coddocentemateria']
            );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}
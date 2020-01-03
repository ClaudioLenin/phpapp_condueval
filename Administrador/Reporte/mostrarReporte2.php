<?php
require '../../app/conexion.php';

if (isset($_POST['codperiodoseccionparalelo'])) {
    Conexion::abrir_conexion();
    $sql = "SELECT tdocentemateria.coddocentemateria,tmateria.nommateria,tpersona.nompersona,tpersona.apepersona FROM tdocentemateria INNER JOIN tmateria ON tdocentemateria.codmateria = tmateria.codmateria INNER JOIN tpersona ON tpersona.codpersona = tdocentemateria.codpersona WHERE codperiodoseccionparalelo = :codperiodoseccionparalelo";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codperiodoseccionparalelo", $_POST['codperiodoseccionparalelo'], PDO::PARAM_STR);    
    $sentencia->execute();
    $materias = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    $json = array();
    
    foreach ($materias as $mat) {
        
        //CANTIDAD DE EXAMENES POR MATERIA
        Conexion::abrir_conexion();
        $sql = "SELECT Count(*) as examenes FROM tmateriaexamen WHERE coddocentemateria = :coddocentemateria";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":coddocentemateria", $mat['coddocentemateria'], PDO::PARAM_STR);
        $sentencia->execute();
        $examenes = $sentencia->fetch();
        Conexion::cerrar_conexion();
        
        $json[] = array(
                'nommateria' => $mat['nommateria'],
                'nompersona' => $mat['nompersona'],
                'apepersona' => $mat['apepersona'],
                'examenes' => $examenes['examenes']
            );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

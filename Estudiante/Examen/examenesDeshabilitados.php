<?php
require '../../app/conexion.php';
session_start();
try {
    date_default_timezone_set('Etc/GMT+5');
    $hoy = array();
    $hoy = date("Y-m-d H:i:00", time());
    if (isset($_POST['coddocentemateria'])) {
        Conexion::abrir_conexion();
        $sql = "SELECT codexamen FROM tmateriaexamen INNER JOIN testudianteparalelo "
                . "ON tmateriaexamen.codestudiante = testudianteparalelo.codestudianteparalelo "
                . "WHERE tmateriaexamen.coddocentemateria = :coddocentemateria AND testudianteparalelo.codestudiante = :codestudiante";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":coddocentemateria", $_POST['coddocentemateria'], PDO::PARAM_STR);
        $sentencia->bindParam(":codestudiante", $_SESSION['codpersona'], PDO::PARAM_STR);
        $sentencia->execute();
        $examenes = $sentencia->fetchAll();
        Conexion::cerrar_conexion();
        if ($examenes != null) {
            $json = array();
            foreach ($examenes as $examen) {
                Conexion::abrir_conexion();
                $sql = "SELECT codexamen,descripcion FROM texamen "
                        . "WHERE codexamen = :codexamen AND estado = 'DESHABILITADO'";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codexamen", $examen['codexamen'], PDO::PARAM_STR);
                $sentencia->execute();
                $hojas = $sentencia->fetchAll();
                Conexion::cerrar_conexion();
                foreach ($hojas as $hoja) {

                    /*if ($hoja['estado'] == 'HABILITADO') {
                        if (strtotime($hoja['fechaejecucion']) > strtotime($hoja['fechafin']) || strtotime($hoy) > strtotime($hoja['fechafin'])) {
                            $estado = "DESHABILITADO";
                        } else {
                            $estado = "HABILITADO";
                        }
                        Conexion::abrir_conexion();
                        $sql = "UPDATE texamen SET estado = :estado WHERE codexamen = :codexamen";
                        $sentencia = Conexion::obtener_conexion()->prepare($sql);
                        $sentencia->bindParam(":estado", $estado, PDO::PARAM_STR);
                        $sentencia->bindParam(":codexamen", $hoja['codexamen'], PDO::PARAM_STR);
                        $sentencia->execute();
                        Conexion::cerrar_conexion();
                    }*/
                         $json[] = array(
                            'codexamen' => $hoja['codexamen'],
                            'coddocentemateria' => $_POST['coddocentemateria'],
                            'descripcion' => $hoja['descripcion']
                        );
                } 
            }
            $jsonstring = json_encode($json);
            echo $jsonstring;
        } else {
            echo 'NO TIENE EXAMENES ASIGNADOS';
        }
    }

    Conexion::cerrar_conexion();
} catch (Exception $ex) {
    echo 'ERROR ' . $ex->getMessage();
    Conexion::cerrar_conexion();
}
?>
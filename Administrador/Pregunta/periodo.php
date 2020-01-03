<?php
require '../../app/conexion.php';
try {
    Conexion::abrir_conexion();
    $sql = "SELECT * FROM tperiodo";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->execute();
    $periodos = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    $json = array();
    if (!empty($periodos)) {
        foreach ($periodos as $periodo) {
            $json[] = array(
                'codperiodo' => $periodo['codperiodo'],
                'nomperiodo' => $periodo['nomperiodo']
            );
        }
        $jsonstring = json_encode($json);
        echo $jsonstring;
    } else {
        Conexion::cerrar_conexion();
    }
} catch (Exception $ex) {
    Conexion::cerrar_conexion();
    echo 'Error de conexion a la base de datos' . $ex->getMessage();
}
?>
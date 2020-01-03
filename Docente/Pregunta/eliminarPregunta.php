<?php
require '../../app/conexion.php';
$cod = $_POST['id'];
$tipo = $_POST['tip'];

if (isset($cod) && isset($tipo)) {
    Conexion::abrir_conexion();
    if ($tipo != "UNIR" && $tipo != "COMPLETAR") {

        $sql = "SELECT * FROM trespuesta INNER JOIN tpreguntarespuesta ON trespuesta.codrespuesta = tpreguntarespuesta.codrespuesta WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $cod, PDO::PARAM_STR);
        $sentencia->execute();
        $respuestas = $sentencia->fetchAll();
        foreach ($respuestas as $respuesta) {
            $sql = "DELETE FROM trespuesta WHERE codrespuesta = :codrespuesta";
            $sentencia1 = Conexion::obtener_conexion()->prepare($sql);
            $sentencia1->bindParam(":codrespuesta", $respuesta['codrespuesta'], PDO::PARAM_STR);
            $sentencia1->execute();
        }
        $sql = "DELETE FROM tpregunta WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $cod, PDO::PARAM_STR);
        $sentencia->execute();
    } else if ($tipo == "UNIR") {
        $sql = "SELECT * FROM tlista INNER JOIN trespuesta ON trespuesta.codrespuesta = tlista.codrespuesta INNER JOIN tlistapregunta ON tlistapregunta.codlista = tlista.codlista WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $cod, PDO::PARAM_STR);
        $sentencia->execute();
        $preguntas_respuestas = $sentencia->fetchAll();
        foreach ($preguntas_respuestas as $pregunta_respuesta) {
            $sql = "DELETE FROM trespuesta WHERE codrespuesta = :codrespuesta";
            $sentencia1 = Conexion::obtener_conexion()->prepare($sql);
            $sentencia1->bindParam(":codrespuesta", $pregunta_respuesta['codrespuesta'], PDO::PARAM_STR);
            $sentencia1->execute();
        }
        $sql = "DELETE FROM tpregunta WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $cod, PDO::PARAM_STR);
        $sentencia->execute();
    } else if ($tipo == "COMPLETAR") {
        $sql = "DELETE FROM tpartes WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $cod, PDO::PARAM_STR);
        $sentencia->execute();
        $sql = "DELETE FROM tpregunta WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $cod, PDO::PARAM_STR);
        $sentencia->execute();
    }
    Conexion::cerrar_conexion();
}
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require '../../app/conexion.php';
//print_r($_POST);

if($_POST['tipo']=='UNIR'){
    mostrarUnir($_POST['codp']);
}else if($_POST['tipo']=='COMPLETAR'){
    mostrarCompletar($_POST['codp']);
}else{
    mostrarRespuesta($_POST['codp']);
}

function mostrarRespuesta($id) {
    Conexion::abrir_conexion();
    $sql = "SELECT * FROM trespuesta INNER JOIN tpreguntarespuesta ON trespuesta.codrespuesta = tpreguntarespuesta.codrespuesta WHERE codpregunta = :codpregunta";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codpregunta", $id, PDO::PARAM_STR);
    $sentencia->execute();
    $respuestas = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    ?><div class="col-lg-12"><?php
    foreach ($respuestas as $respuesta) {

        if ($respuesta['correcta'] == "SI") {
            ?>            
                <div class="col-lg-12" style="padding-bottom: 10px"><b>RESPUESTA CORRECTA:</b><?php echo $respuesta['respuesta']; ?></div>
                <?php
            } else {
                ?>
                <div class="col-lg-12" style="padding-bottom: 10px"><b>OTRA OPCIÓN:</b> <?php echo $respuesta['respuesta']; ?></div>
                <?php
            }
        }
        ?></div><?php
}

function mostrarUnir($id) {
    Conexion::abrir_conexion();
    //UNIR
    $sql = "SELECT * FROM tlista INNER JOIN trespuesta ON trespuesta.codrespuesta = tlista.codrespuesta INNER JOIN tlistapregunta ON tlistapregunta.codlista = tlista.codlista WHERE codpregunta = :codpregunta";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codpregunta", $id, PDO::PARAM_STR);
    $sentencia->execute();
    $preguntas_respuestas = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    ?><div class="col-lg-12" ><?php
        foreach ($preguntas_respuestas as $pregunta_respuesta) {

            if ($pregunta_respuesta['correcta'] == "SI") {
                ?>            
                <div class="col-lg-5" style="padding-bottom: 10px;text-align: center;"><?php echo $pregunta_respuesta['enunciado']; ?></div>
                <div class="col-lg-1" style="padding-bottom: 10px;text-align: center;">&harr;</div>
                <div class="col-lg-5" style="padding-bottom: 10px;text-align: center;"><?php echo $pregunta_respuesta['respuesta']; ?></div>
                <div class="col-lg-12" style="padding-bottom: 10px;text-align: center;"></div>
                <?php
            } else {
                ?>
                <div class="col-lg-12" style="padding-bottom: 10px"><b>OTRA OPCIÓN:</b> <?php echo $pregunta_respuesta['enunciado']; ?></div>
                <?php
            }
        }
        ?></div><?php
}

function mostrarCompletar($id) {
    Conexion::abrir_conexion();
    //COMPLETAR
    $sql = "SELECT * FROM tpartes WHERE codpregunta = :codpregunta ORDER BY numero ASC";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codpregunta", $id, PDO::PARAM_STR);
    $sentencia->execute();
    $frases = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    ?><div class="col-lg-12" ><b>RESPUESTA CORRECTA:</b><?php
        foreach ($frases as $frase) {
            if ($frase['tipo'] == "TEXTO") {
                ?>            
                &nbsp;<?php echo $frase['cadena']; ?>
                <?php
            } else {
                ?>
                &nbsp;<b><u><?php echo $frase['cadena']; ?></u></b>
                <?php
            }
        }
        ?></div><?php
}

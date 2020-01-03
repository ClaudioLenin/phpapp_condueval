<?php

require '../../app/conexion.php';
require '../../app/ControlSesion.php';
session_start();
if (ControlSesion::sesion_iniciada() && $_SESSION['tippersona'] == 'Estudiante') {
    if (isset($_COOKIE['establecido'])) {//&&$_POST['codexamen']==$_COOKIE['establecido']){
        echo 'volver';
    } else {

        try {
            Conexion::abrir_conexion();
            $sql = "SELECT * FROM texamen WHERE clave = :clave AND codexamen = :codexamen";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":clave", $_POST['contrasenia'], PDO::PARAM_STR);
            $sentencia->bindParam(":codexamen", $_POST['codexamen'], PDO::PARAM_STR);
            $sentencia->execute();
            $quiz = $sentencia->fetchAll();
            Conexion::cerrar_conexion();
            $idexam = null;

            $fe = 0;
            $ff = 0;
            foreach ($quiz as $cod) {
                $fe = $cod['fechaejecucion'];
                $ff = $cod['fechafin'];
            }
            date_default_timezone_set('Etc/GMT+5');
            $hoy = array();
            $hoy = date("Y-m-d H:i:00", time());

            if (Count($quiz) <= 0 || strtotime($hoy) < strtotime($fe) || strtotime($ff) <= strtotime($hoy)) {
                echo 'volver';
            } else {
                echo 'continuar';
            }
        } catch (Exception $ex) {
            Conexion::cerrar_conexion();
            echo 'ERROR ' . $ex->getMessage();
        }
    }
} else {
    //ControlSesion::cerrar_sesion();
    header("Location:../../autentificar.php");
}
<?php

class Respuestas {

    function obtenerNotaTotal($codme) {
        try {
            Conexion::abrir_conexion();
            $sql = "SELECT * FROM tnotaexamen WHERE codme = :codme";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
            $sentencia->execute();
            $notaexamen = $sentencia->fetchAll();
            Conexion::cerrar_conexion();
            $nota = 0;
            foreach ($notaexamen as $n){
                $nota = $n['nota'];
            }
            return $nota;
        } catch (Exception $ex) {
            Conexion::cerrar_conexion();
            return 'ERROR ' . $ex->getMessage();
        }
    }

    function seleccionarExamen($codexamen) {
        try {
            Conexion::abrir_conexion();
            $sql = "SELECT * FROM tpregunta INNER JOIN texamenpregunta "
                    . "ON tpregunta.codpregunta = texamenpregunta.codpregunta "
                    . "WHERE codexamen = :codexamen";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":codexamen", $codexamen, PDO::PARAM_STR);
            $sentencia->execute();
            $examen = $sentencia->fetchAll();
            Conexion::cerrar_conexion();
            return $examen;
        } catch (Exception $ex) {
            Conexion::cerrar_conexion();
            return 'ERROR ' . $ex->getMessage();
        }
    }

    function respuestasEstudiante($codexamen) {
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM testudianterespuesta WHERE codme = :codme";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codme", $codexamen, PDO::PARAM_STR); 
        $sentencia->execute();
        $examen = $sentencia->fetchAll();
        Conexion::cerrar_conexion();
        return $examen;
    }

    function notaPregunta($array, $codpreg) {
        $nota = 0;
        foreach ($array as $notapregunta) {
            if ($notapregunta['codpregunta'] == $codpreg) {
                $nota += $notapregunta['valor'];
            }
        }
        return $nota;
    }

    function mostrarUnir($codpregunta) {
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM tlista "
                . "INNER JOIN trespuesta "
                . "ON trespuesta.codrespuesta = tlista.codrespuesta "
                . "INNER JOIN tlistapregunta "
                . "ON tlistapregunta.codlista = tlista.codlista "
                . "WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $codpregunta, PDO::PARAM_STR);
        $sentencia->execute();
        $preguntas_respuestas = $sentencia->fetchAll();
        Conexion::cerrar_conexion();
        return $preguntas_respuestas;
    }

    function mostrarSimple($codpregunta) {
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM trespuesta INNER JOIN tpreguntarespuesta ON trespuesta.codrespuesta = tpreguntarespuesta.codrespuesta WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $codpregunta, PDO::PARAM_STR);
        $sentencia->execute();
        $respuestas = $sentencia->fetchAll();
        Conexion::cerrar_conexion();
        return $respuestas;
    }

    function mostrarCompletar($codpregunta) {
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM tpartes WHERE codpregunta = :codpregunta ORDER BY numero asc";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $codpregunta, PDO::PARAM_STR);
        $sentencia->execute();
        $completar = $sentencia->fetchAll();
        Conexion::cerrar_conexion();
        return $completar;
    }

    function buscarCompletar($array, $numero, $codpregunta) {
        $cadena = null;
        foreach ($array as $re) {
            if ($re['numero'] == $numero && $re['codpregunta'] == $codpregunta) {
                $cadena = $re['respuesta'];
            }
        }
        return $cadena;
    }

    function buscarUnir($array, $preg, $resp) {

        foreach ($array as $pr) {
            if ($pr['enunciado'] == $preg && $pr['respuesta'] == $resp)
                return true;
        }
        return false;
    }

}

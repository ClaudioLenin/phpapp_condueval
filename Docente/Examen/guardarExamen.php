<?php
require '../../app/conexion.php';
session_start();
date_default_timezone_set('Etc/GMT+5');
$hoy = array();
$hoy = date("Y-m-d H:i:00", time());

$calificacion = 0;
$cantidad = 0;
if (isset($_POST['preguntas']) && isset($_SESSION['codperiodo']) && isset($_POST['coddocentemateria']) && isset($_POST['codseccion']) && isset($_POST['codparalelo'])) {
    Conexion::abrir_conexion();
    $preguntas = $_POST['preguntas'];

    foreach ($preguntas as $pregunta) { //recorre el vector de las preguntas seleccionadas
        $sql = "SELECT valor FROM tpregunta WHERE codpregunta = :codpregunta";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpregunta", $pregunta, PDO::PARAM_STR);
        $sentencia->execute();
        $preg = $sentencia->fetchAll();
        foreach ($preg as $p) {
            $calificacion += $p['valor'];
        }
        $cantidad++;
    }

    Conexion::cerrar_conexion();
    if ($calificacion == 20) {
        //RECUPERAR codseccion
        Conexion::abrir_conexion();
        $sql = "SELECT codseccion FROM tperiodoseccion WHERE codperiodoseccion = :codperiodoseccion";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codperiodoseccion", $_POST['codseccion'], PDO::PARAM_STR);
        $sentencia->execute();
        $codseccion = $sentencia->fetch();
        Conexion::cerrar_conexion();
        //RECUPERAR codparalelo
        Conexion::abrir_conexion();
        $sql = "SELECT codparalelo FROM tperiodoseccionparalelo WHERE codperiodoseccionparalelo = :codperiodoseccionparalelo";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codperiodoseccionparalelo", $_POST['codparalelo'], PDO::PARAM_STR);
        $sentencia->execute();
        $codparalelo = $sentencia->fetch();
        Conexion::cerrar_conexion();
        
        echo $codseccion['codseccion'];
        echo $codparalelo['codparalelo'];
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM testudianteparalelo WHERE codperiodo = :codperiodo AND codseccion = :codseccion AND codparalelo = :codparalelo";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codperiodo", $_SESSION['codperiodo'], PDO::PARAM_STR);
        $sentencia->bindParam(":codseccion", $codseccion['codseccion'], PDO::PARAM_STR);
        $sentencia->bindParam(":codparalelo", $codparalelo['codparalelo'], PDO::PARAM_STR);
        $sentencia->execute();
        $estudiantes = $sentencia->fetchAll();
        $sql = "INSERT INTO texamen(descripcion,fechaejecucion,fechafin,numpreguntas,valoracion,clave,estado,fechacreacion) VALUES(:descripcion,:fechaejecucion,:fechafin,:numpreguntas,:valoracion,:clave,:estado,:fechacreacion)";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":descripcion", $_POST['nombre-examen'], PDO::PARAM_STR);
        $sentencia->bindParam(":fechaejecucion", $_POST['fechaejecucion'], PDO::PARAM_STR);
        $sentencia->bindParam(":fechafin", $_POST['fechafin'], PDO::PARAM_STR);
        $sentencia->bindParam(":numpreguntas", $cantidad, PDO::PARAM_STR);
        $sentencia->bindParam(":valoracion", $calificacion, PDO::PARAM_STR);
        $sentencia->bindParam(":clave", $_POST['contrasenia'], PDO::PARAM_STR); //password_hash($_POST['contrasenia'], PASSWORD_DEFAULT)
        if (strtotime($hoy) > strtotime($_POST['fechafin']) || strtotime($_POST['fechaejecucion']) > strtotime($_POST['fechafin'])) {
            $estado = "DESHABILITADO";
            $sentencia->bindParam(":estado", $estado, PDO::PARAM_STR); //
        } else {
            $estado = "HABILITADO";
            $sentencia->bindParam(":estado", $estado, PDO::PARAM_STR); //
        }
        $sentencia->bindParam(":fechacreacion", $hoy, PDO::PARAM_STR);
        $sentencia->execute();
        Conexion::cerrar_conexion();

        $ultimoExamen = obtenerultimoExamen();

        foreach ($preguntas as $pregunta) { //recorre el vector de las preguntas seleccionadas
            enlazarPreguntaExamen($pregunta, $ultimoExamen);
        }
        foreach ($estudiantes as $estudiante) {
            enlazarMateriaExamen($_POST['coddocentemateria'], $ultimoExamen, $estudiante['codestudianteparalelo']);
        }
    }else{
        echo 'NOT';
    }
}else{
    echo 'MATERIA';
}



function obtenerultimoExamen() {
    Conexion::abrir_conexion();
    $sql = "SELECT codexamen FROM texamen ORDER BY codexamen ASC";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();
    foreach ($resultado as $ultimo) {
        $final = $ultimo['codexamen'];
    }
    Conexion::cerrar_conexion();
    return $final;
}

function enlazarPreguntaExamen($ultimaPregunta, $ultimoExamen) {
    Conexion::abrir_conexion();
    $sql = "INSERT INTO texamenpregunta(codexamen,codpregunta) VALUES(:codexamen,:codpregunta)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codexamen", $ultimoExamen, PDO::PARAM_STR);
    $sentencia->bindParam(":codpregunta", $ultimaPregunta, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
}

function enlazarMateriaExamen($materia, $ultimoExamen,$estudiante){
    Conexion::abrir_conexion();
    $sql = "INSERT INTO tmateriaexamen(coddocentemateria,codexamen,codestudiante) VALUES(:coddocentemateria,:codexamen,:codestudiante)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":coddocentemateria", $materia, PDO::PARAM_STR);
    $sentencia->bindParam(":codexamen", $ultimoExamen, PDO::PARAM_STR);
    $sentencia->bindParam(":codestudiante", $estudiante, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
}

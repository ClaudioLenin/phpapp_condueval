<?php

require '../../app/conexion.php';

print_r($_POST);

if (isset($_POST['tipo'])) {
    $tipo = $_POST['tipo'];
}
if ($tipo == "UNIR") {
    insertarPregunta($_POST['enunciado']);
    $ultimaPregunta = obtenerultimaPregunta();
    $respuestas = array();
    $respuestas = $_POST['respuestas'];
    $codResp = array();
    $i = 0;
    foreach ($respuestas as $respuesta => $resp) {
        insertarRespuesta("SI", $resp);
        $codResp[$i] = obtenerultimaRespuesta();
        $i++;
    }
    $preguntas = array();
    $preguntas = $_POST['preguntas'];
    $i = 0;
    foreach ($preguntas as $pregunta => $preg) {
        ingresarEnunciado($preg, $codResp[$i]);
        enlazarPreguntaEnunciado(obtenerultimoEnunciado(), $ultimaPregunta);
        $i++;
    }
} else if ($tipo == "COMPLETAR") {
    insertarPregunta($_POST['enunciado']);
    $ultimaPregunta = obtenerultimaPregunta();

    $completar = array();
    $texto = array();
    $cadena = array();
    $cadena = $_POST['cadena'];
    $k = 1;
    foreach ($cadena as $posicion => $frase) {
        if (isset($_POST['ct']))
            for ($j = 0; $j <= $_POST['ct']; $j++) {
                if ($posicion == 'texto' . $j) {
                    insertarParte($ultimaPregunta, $k, $frase, "TEXTO");
                }
            }
        if (isset($_POST['cc']))
            for ($i = 0; $i <= $_POST['cc']; $i++) {
                if ($posicion == 'completar' . $i) {
                    insertarParte($ultimaPregunta, $k, $frase, "COMPLETAR");
                }
            }
        $k++;
    }
} else if ($tipo == "RESPUESTA SIMPLE") {
    //echo "hola".$_SESSION['codmateria'].$_SESSION['materia'];
    insertarPregunta($_POST['pregunta']);
    insertarRespuesta("SI", $_POST['respuesta']);
    echo $ultimaPregunta = obtenerultimaPregunta();
    echo $ultimaRespuesta = obtenerultimaRespuesta();
    enlazarPreguntaRespuesta($ultimaPregunta, $ultimaRespuesta);
} else if ($tipo == "SELECCION SIMPLE") {
    insertarPregunta($_POST['pregunta']);
    insertarRespuesta("SI", $_POST['correcta']);
    $ultimaPregunta = obtenerultimaPregunta();
    $ultimaRespuesta = obtenerultimaRespuesta();
    enlazarPreguntaRespuesta($ultimaPregunta, $ultimaRespuesta);
    $otras = array();
    $otras = $_POST['otras'];
    foreach ($otras as $respuestas => $respuesta) {
        insertarRespuesta("NO", $respuesta);
        $ultimaRespuesta = obtenerultimaRespuesta();
        enlazarPreguntaRespuesta($ultimaPregunta, $ultimaRespuesta);
    }
} else if ($tipo == "SELECCION MULTIPLE") {
    insertarPregunta($_POST['pregunta']);
    $ultimaPregunta = obtenerultimaPregunta();

    $correctas = array();
    $correctas = $_POST['correctas'];
    foreach ($correctas as $respuestas => $respuesta) {
        insertarRespuesta("SI", $respuesta);
        $ultimaRespuesta = obtenerultimaRespuesta();
        enlazarPreguntaRespuesta($ultimaPregunta, $ultimaRespuesta);
    }

    $otras = array();
    $otras = $_POST['otras'];
    foreach ($otras as $respuestas1 => $respuesta1) {
        insertarRespuesta("NO", $respuesta1);
        $ultimaRespuesta = obtenerultimaRespuesta();
        enlazarPreguntaRespuesta($ultimaPregunta, $ultimaRespuesta);
    }
} else if ($tipo == "VERDADERO FALSO") {
    insertarPregunta($_POST['pregunta']);
    if ($_POST['seleccion'] === 'verdadero') {
        insertarRespuesta("SI", $_POST['seleccion']);
    } else {
        insertarRespuesta("NO", $_POST['seleccion']);
    }
    $ultimaPregunta = obtenerultimaPregunta();
    $ultimaRespuesta = obtenerultimaRespuesta();
    enlazarPreguntaRespuesta($ultimaPregunta, $ultimaRespuesta);
}

//****************************************************************************************************************************************************************************************************************************
//FUNCIONES DE BASE DE DATOS
//****************************************************************************************************************************************************************************************************************************
function insertarPregunta($pregunta) { //INSERTAR IMAGEN
    $ruta = "../../assets/img";
    $archivo = $_FILES['imagen']['tmp_name'];
    $nombreArchivo = $_FILES['imagen']['name'];
    move_uploaded_file($archivo, $ruta . "/" . $nombreArchivo);
    $ruta = $ruta . "/" . $nombreArchivo;

    //$imagen = addslashes(file_get_contents($_FILES['imagen']['tmp_name']))
    Conexion::abrir_conexion();
    if ($archivo != null && $nombreArchivo != null) {
        $sql = "INSERT INTO tpregunta(pregunta,valor,tipo,coddocentemateria,imagen) VALUES(:pregunta,:valor,:tipo,:coddocentemateria,:imagen)"; //INGRESAR IMAGEN AQUI
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":pregunta", $pregunta, PDO::PARAM_STR);
        $sentencia->bindParam(":valor", $_POST['valor'], PDO::PARAM_STR);
        $sentencia->bindParam(":tipo", $_POST['tipo'], PDO::PARAM_STR);
        $sentencia->bindParam(":imagen", $ruta, PDO::PARAM_STR);
        $sentencia->bindParam(":coddocentemateria", $_POST['coddocentemateria'], PDO::PARAM_STR);
        $sentencia->execute();
    } else {
        $sql = "INSERT INTO tpregunta(pregunta,valor,tipo,coddocentemateria) VALUES(:pregunta,:valor,:tipo,:coddocentemateria)"; //INGRESAR IMAGEN AQUI
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":pregunta", $pregunta, PDO::PARAM_STR);
        $sentencia->bindParam(":valor", $_POST['valor'], PDO::PARAM_STR);
        $sentencia->bindParam(":tipo", $_POST['tipo'], PDO::PARAM_STR);
        $sentencia->bindParam(":coddocentemateria", $_POST['coddocentemateria'], PDO::PARAM_STR);
        $sentencia->execute();
    }

    Conexion::cerrar_conexion();
}

function insertarRespuesta($respuesta, $vRespuesta) {
    Conexion::abrir_conexion();
    $sql = "INSERT INTO trespuesta(respuesta,correcta) VALUES(:respuesta,:correcta)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":respuesta", $vRespuesta, PDO::PARAM_STR);
    $sentencia->bindParam(":correcta", $respuesta, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
}

function obtenerultimaPregunta() {
    Conexion::abrir_conexion();
    $sql = "SELECT codpregunta FROM tpregunta ORDER BY codpregunta ASC";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();
    foreach ($resultado as $ultimo) {
        $final = $ultimo['codpregunta'];
    }
    Conexion::cerrar_conexion();
    return $final;
}

function obtenerultimaRespuesta() {
    Conexion::abrir_conexion();
    $sql = "SELECT codrespuesta FROM trespuesta ORDER BY codrespuesta ASC";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();
    foreach ($resultado as $ultimo) {
        $final = $ultimo['codrespuesta'];
    }
    Conexion::cerrar_conexion();
    return $final;
}

function enlazarPreguntaRespuesta($ultimaPregunta, $ultimaRespuesta) {
    Conexion::abrir_conexion();
    $sql = "INSERT INTO tpreguntarespuesta(codrespuesta,codpregunta) VALUES(:codrespuesta,:codpregunta)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codrespuesta", $ultimaRespuesta, PDO::PARAM_STR);
    $sentencia->bindParam(":codpregunta", $ultimaPregunta, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
}

function ingresarEnunciado($enunciado, $codResp) { //INSERTAR IMAGEN
    Conexion::abrir_conexion();
    $sql = "INSERT INTO tlista(enunciado,codrespuesta) VALUES(:enunciado,:codrespuesta)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":enunciado", $enunciado, PDO::PARAM_STR);
    $sentencia->bindParam(":codrespuesta", $codResp, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
}

function obtenerultimoEnunciado() {
    Conexion::abrir_conexion();
    $sql = "SELECT codlista FROM tlista ORDER BY codlista ASC";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->execute();
    $resultado = $sentencia->fetchAll();
    foreach ($resultado as $ultimo) {
        $final = $ultimo['codlista'];
    }
    Conexion::cerrar_conexion();
    return $final;
}

function enlazarPreguntaEnunciado($enunciado, $ultimaPregunta) {
    Conexion::abrir_conexion();
    $sql = "INSERT INTO tlistapregunta(codpregunta,codlista) VALUES(:codpregunta,:codlista)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codpregunta", $ultimaPregunta, PDO::PARAM_STR);
    $sentencia->bindParam(":codlista", $enunciado, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
}

function insertarParte($ultimaPregunta, $numero, $cadena, $tipo) {
    Conexion::abrir_conexion();
    $sql = "INSERT INTO tpartes(codpregunta,numero,cadena,tipo) VALUES(:codpregunta,:numero,:cadena,:tipo)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codpregunta", $ultimaPregunta, PDO::PARAM_STR);
    $sentencia->bindParam(":numero", $numero, PDO::PARAM_STR);
    $sentencia->bindParam(":cadena", $cadena, PDO::PARAM_STR);
    $sentencia->bindParam(":tipo", $tipo, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
}

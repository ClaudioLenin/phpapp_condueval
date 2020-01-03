<?php

require '../../app/conexion.php';
session_start();

if ($_POST['guardar'] && !isset($_COOKIE['establecido'])) { //ESTABLECER EL TIEMPO QUE DURA EL EXAMEN Y EL ID DE LA PRUEBA
    Conexion::abrir_conexion();
    $sql = "SELECT * FROM texamen WHERE codexamen = :codexamen";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codexamen", $_POST['codexamen'], PDO::PARAM_STR);
    $sentencia->execute();
    $tiempo_examen = $sentencia->fetchAll();
    Conexion::cerrar_conexion();


    foreach ($tiempo_examen as $tiempo) {
        $strEnd = $tiempo['fechafin'];
    }

    date_default_timezone_set('Etc/GMT+5');
    $strStart = array();
    $strStart = date("Y-m-d H:i:00", time());
    $dteStart = new DateTime($strStart);
    $dteEnd = new DateTime($strEnd);
    $dteDiff = $dteStart->diff($dteEnd);


    $horas = $dteDiff->format("%H");
    $minutos = $dteDiff->format("%I");
    $segundos = $dteDiff->format("%S");

    $segundos += ($minutos * 60);
    $segundos += ($horas * 60 * 60);
    $segundos += 60;

    setcookie('establecido', $_POST['codexamen'], time() + $segundos);
}

try {
    Conexion::abrir_conexion();
    $sql = "SELECT * FROM tpregunta INNER JOIN texamenpregunta "
            . "ON tpregunta.codpregunta = texamenpregunta.codpregunta "
            . "WHERE codexamen = :codexamen";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codexamen", $_POST['codexamen'], PDO::PARAM_STR);
    $sentencia->execute();
    $examen = $sentencia->fetchAll();
    Conexion::cerrar_conexion();

    $codme = null;
    foreach (obtenerMateriaExamen() as $exma) {
        $codme = $exma['codme'];
    }
    //echo $codme;

    $nota_total_examen = 0;
    foreach ($examen as $preg) {
        //echo round(1.97,1);
        switch ($preg['tipo']) {
            case 'SELECCION SIMPLE':
                $respuestas = SeleccionSimple($preg['codpregunta']);
                $bandera = 0;
                $seleccion_simple = $_POST['seleccion_simple']; //obtengo toda la lista de respuestas de tipo seleccion simple
                if (isset($seleccion_simple) && $seleccion_simple != null)
                    foreach ($seleccion_simple as $simple => $frase) {//recorrer el array de la lista de respuestas de tipo seleccion simple
                        if ($simple == $preg['codpregunta']) //verificar si el codigo de pregunta obtenida del formulario coincide con el de la base de datos
                            foreach ($respuestas as $opciones) { //recorrer el array de respuestas correspondientes a la pregunta respectivca de la base de datos
                                if ($opciones['correcta'] == "SI") { //obtener la respuesta correcta
                                    if (trim(mb_strtoupper($frase, 'utf-8')) == trim(mb_strtoupper($opciones['respuesta'], 'utf-8'))) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                        $bandera = $preg['valor'];
                                        $nota_total_examen += $preg['valor'];
                                    } else {
                                        $bandera = 0;
                                    }
                                    if ($frase != null) {
                                        Conexion::abrir_conexion();
                                        $sql = "INSERT INTO testudianterespuesta(respuesta,valor,codpregunta,codme) VALUES(:respuesta,:valor,:codpregunta,:codme)";
                                        $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                        $sentencia->bindParam(":respuesta", $frase, PDO::PARAM_STR);
                                        $sentencia->bindParam(":valor", $bandera, PDO::PARAM_STR);
                                        $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                        $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
                                        $sentencia->execute();
                                        Conexion::cerrar_conexion();
                                    }
                                }
                            }
                    }
                break;
            case 'SELECCION MULTIPLE':
                $respuestas = SeleccionSimple($preg['codpregunta']);

                $seleccion_multiple = $_POST['seleccion_multiple'];

                $todas = 0;
                foreach ($respuestas as $opciones) { //recorrer el array de respuestas correspondientes a la pregunta respectivca de la base de datos
                    if ($opciones['correcta'] == "SI") { //obtener la respuesta correcta
                        $todas++;
                    }
                }

                $nota = 0;
                if (isset($seleccion_multiple) && $seleccion_multiple != null) {
                    $bien = 0;
                    $mal = 0;
                    for ($i = 0; $i < Count($respuestas); $i++) {
                        foreach ($seleccion_multiple as $multiple => $frase) {
                            if ($multiple == $i . $preg['codpregunta']) //verificar si el codigo de pregunta obtenida del formulario coincide con el de la base de datos
                                foreach ($respuestas as $opciones) { //recorrer el array de respuestas correspondientes a la pregunta respectivca de la base de datos
                                    if ($opciones['correcta'] == "SI") { //obtener la respuesta correcta
                                        if ($frase == $opciones['respuesta']) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                            $bien++;
                                        }
                                    } else if ($opciones['correcta'] == 'NO')
                                        if ($frase == $opciones['respuesta']) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                            $mal++;
                                        }
                                }
                        }
                    }

                    $buenas = 0;
                    foreach ($respuestas as $opciones) {
                        if ($opciones['correcta'] == 'SI')
                            $buenas++;
                    }


                    $nota_parcial = 0;
                    if ($todas > 0) {
                        $nota_parcial = round($preg['valor'] / $todas, 2); //nota parcial para cada subitem
                        $nota = $nota_parcial * $bien - $nota_parcial * $mal; //SU CALIFICACION ES DUDOSA <-------------------------------------------------------*******************************
                    }

                    if ($nota >= $preg['valor'])
                        $nota = $preg['valor'];
                    else if ($nota <= 0)
                        $nota = 0;

                    if ((($bien + $mal) == Count($respuestas) / 2) && ($bien == $mal))
                        $nota = $preg['valor'] / 2;


                    for ($i = 0; $i < Count($respuestas); $i++) {
                        foreach ($seleccion_multiple as $multiple => $frase) {
                            if ($multiple == $i . $preg['codpregunta']) //verificar si el codigo de pregunta obtenida del formulario coincide con el de la base de datos
                                foreach ($respuestas as $opciones) { //recorrer el array de respuestas correspondientes a la pregunta respectivca de la base de datos
                                    if ($opciones['correcta'] == "SI") { //obtener la respuesta correcta
                                        if ($frase == $opciones['respuesta']) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                            if ($frase != null) {
                                                Conexion::abrir_conexion();
                                                $sql = "INSERT INTO testudianterespuesta(respuesta,valor,codpregunta,codme) VALUES(:respuesta,:valor,:codpregunta,:codme)";
                                                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                                $sentencia->bindParam(":respuesta", $frase, PDO::PARAM_STR);
                                                $sentencia->bindParam(":valor", $nota_parcial, PDO::PARAM_STR);
                                                $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                                $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
                                                $sentencia->execute();
                                                Conexion::cerrar_conexion();
                                            }
                                        }
                                    } else if ($opciones['correcta'] == 'NO')
                                        if ($frase == $opciones['respuesta']) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                            $cero = 0;
                                            if ($frase != null) {
                                                Conexion::abrir_conexion();
                                                $sql = "INSERT INTO testudianterespuesta(respuesta,valor,codpregunta,codme) VALUES(:respuesta,:valor,:codpregunta,:codme)";
                                                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                                $sentencia->bindParam(":respuesta", $frase, PDO::PARAM_STR);
                                                $sentencia->bindParam(":valor", $cero, PDO::PARAM_STR);
                                                $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                                $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
                                                $sentencia->execute();
                                                Conexion::cerrar_conexion();
                                            }
                                        }
                                }
                        }
                    }
                }
                $nota_total_examen += $nota;
                echo $nota_total_examen;
                break;
            case 'RESPUESTA SIMPLE':
                $respuestas = SeleccionSimple($preg['codpregunta']);

                $respuesta_simple = $_POST['respuesta_simple']; //obtengo toda la lista de respuestas de tipo seleccion simple
                $bandera = 0;
                if (isset($respuesta_simple) && $respuesta_simple != null) {
                    foreach ($respuesta_simple as $rsimple => $frase) {//recorrer el array de la lista de respuestas de tipo seleccion simple
                        if ($rsimple == $preg['codpregunta']) //verificar si el codigo de pregunta obtenida del formulario coincide con el de la base de datos
                            foreach ($respuestas as $opciones) { //recorrer el array de respuestas correspondientes a la pregunta respectivca de la base de datos
                                if ($opciones['correcta'] == "SI") { //obtener la respuesta correcta
                                    if (trim(mb_strtoupper($frase, 'utf-8')) == trim(mb_strtoupper($opciones['respuesta'], 'utf-8'))) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                        $bandera = $preg['valor'];
                                        $nota_total_examen += $preg['valor'];
                                    } else {
                                        $bandera = 0;
                                    }
                                    if ($frase != null) {
                                        Conexion::abrir_conexion();
                                        $sql = "INSERT INTO testudianterespuesta(respuesta,valor,codpregunta,codme) VALUES(:respuesta,:valor,:codpregunta,:codme)";
                                        $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                        $sentencia->bindParam(":respuesta", $frase, PDO::PARAM_STR);
                                        $sentencia->bindParam(":valor", $bandera, PDO::PARAM_STR);
                                        $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                        $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
                                        $sentencia->execute();
                                        Conexion::cerrar_conexion();
                                    }
                                }
                            }
                    }
                }
                break;
            case 'VERDADERO FALSO':
                $respuestas = SeleccionSimple($preg['codpregunta']);
                $bandera = 0;
                $verdadero_falso = $_POST['verdadero_falso']; //obtengo toda la lista de respuestas de tipo seleccion simple
                if (isset($verdadero_falso) && $verdadero_falso != null) {
                    foreach ($verdadero_falso as $vf => $frase) {//recorrer el array de la lista de respuestas de tipo seleccion simple
                        if ($vf == $preg['codpregunta']) //verificar si el codigo de pregunta obtenida del formulario coincide con el de la base de datos
                            foreach ($respuestas as $opciones) { //recorrer el array de respuestas correspondientes a la pregunta respectivca de la base de datos
                                if ($opciones['correcta'] == "SI") { //obtener la respuesta correcta
                                    if (trim(mb_strtoupper($frase, 'utf-8')) == trim(mb_strtoupper($opciones['respuesta'], 'utf-8'))) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                        $bandera = $preg['valor'];
                                        $nota_total_examen += $preg['valor'];
                                    } else {
                                        $bandera = 0;
                                    }
                                    if ($frase != null) {
                                        Conexion::abrir_conexion();
                                        $sql = "INSERT INTO testudianterespuesta(respuesta,valor,codpregunta,codme) VALUES(:respuesta,:valor,:codpregunta,:codme)";
                                        $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                        $sentencia->bindParam(":respuesta", $frase, PDO::PARAM_STR);
                                        $sentencia->bindParam(":valor", $bandera, PDO::PARAM_STR);
                                        $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                        $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
                                        $sentencia->execute();
                                        Conexion::cerrar_conexion();
                                    }
                                }
                            }
                    }
                }
                break;
            case 'UNIR':
                Conexion::abrir_conexion();
                $sql = "SELECT * FROM tlista "
                        . "INNER JOIN trespuesta "
                        . "ON trespuesta.codrespuesta = tlista.codrespuesta "
                        . "INNER JOIN tlistapregunta "
                        . "ON tlistapregunta.codlista = tlista.codlista "
                        . "WHERE codpregunta = :codpregunta";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                $sentencia->execute();
                $respuestas = $sentencia->fetchAll();
                Conexion::cerrar_conexion();

                $columna1 = $_POST['lista_enunciados'];
                $columna2 = $_POST['lista_respuestas'];
                $columnaizquierda = array();
                $columnaizquierda = null;
                $columnaderecha = array();
                $columnaderecha = null;
                $columnasunidas = array();
                $columnasunidas = null;



                for ($i = 0; $i < Count($respuestas); $i++) {
                    foreach ($columna1 as $identificador => $frase1) {
                        if ($identificador == $i . $preg['codpregunta']) {
                            $columnaizquierda[$i] = $frase1;
                        }
                    }
                    foreach ($columna2 as $identificador => $frase2) {
                        if ($identificador == $i . $preg['codpregunta']) {
                            $columnaderecha[$i] = $frase2;
                        }
                    }
                    $columnasunidas[$i] = $columnaizquierda[$i] . $columnaderecha[$i];
                }
                $columnasunidasunicas = array_unique($columnasunidas);
                $bien = 0;
                $mal = 0;
                for ($i = 0; $i < Count($columnasunidas); $i++) {
                    $count = 0;
                    foreach ($respuestas as $respuesta)
                        if (isset($columnasunidasunicas[$i]) && ($columnasunidasunicas[$i] == $respuesta['enunciado'] . $respuesta['respuesta'])) {
                            $count++;
                        }
                    if ($count >= 1) {
                        echo '<br>UNIR La respuesta ' . $columnasunidasunicas[$i] . " IGUAL puntaje: " . $preg['valor'] . " " . $columnaizquierda[$i] . $columnaderecha[$i]; //guardar en bd
                        $bien++;
                    } else {
                        echo '<br>UNIR La respuesta ' . $columnasunidas[$i] . " DISTINTO puntaje: 0 " . $columnaizquierda[$i] . $columnaderecha[$i]; //guardar en bd
                        $mal++;
                    }
                }

                $nota = 0;
                $nota_parcial = 0;
                if (Count($respuestas) > 0) {
                    $nota_parcial = round($preg['valor'] / Count($respuestas), 2); //nota parcial para cada subitem
                    $nota = $nota_parcial * $bien; //nota para el examen
                }

                if ($nota >= $preg['valor'])
                    $nota = $preg['valor'];
                else if ($nota <= 0)
                    $nota = 0;

                for ($i = 0; $i < Count($columnasunidas); $i++) {
                    $count = 0;
                    foreach ($respuestas as $respuesta)
                        if (isset($columnasunidasunicas[$i]) && ($columnasunidasunicas[$i] == $respuesta['enunciado'] . $respuesta['respuesta'])) {
                            $count++;
                        }
                    if ($count >= 1) {
                        Conexion::abrir_conexion();
                        $sql = "INSERT INTO testudianterespuesta(pregunta,respuesta,valor,codpregunta,codme) VALUES(:pregunta,:respuesta,:valor,:codpregunta,:codme)";
                        $sentencia = Conexion::obtener_conexion()->prepare($sql);
                        $sentencia->bindParam(":pregunta", $columnaizquierda[$i], PDO::PARAM_STR);
                        $sentencia->bindParam(":respuesta", $columnaderecha[$i], PDO::PARAM_STR);
                        $sentencia->bindParam(":valor", $nota_parcial, PDO::PARAM_STR);
                        $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                        $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
                        $sentencia->execute();
                        Conexion::cerrar_conexion();
                    } else {
                        $cero = 0;
                        Conexion::abrir_conexion();
                        $sql = "INSERT INTO testudianterespuesta(pregunta,respuesta,valor,codpregunta,codme) VALUES(:pregunta,:respuesta,:valor,:codpregunta,:codme)";
                        $sentencia = Conexion::obtener_conexion()->prepare($sql);
                        $sentencia->bindParam(":pregunta", $columnaizquierda[$i], PDO::PARAM_STR);
                        $sentencia->bindParam(":respuesta", $columnaderecha[$i], PDO::PARAM_STR);
                        $sentencia->bindParam(":valor", $cero, PDO::PARAM_STR);
                        $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                        $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
                        $sentencia->execute();
                        Conexion::cerrar_conexion();
                    }
                }
                $nota_total_examen += $nota;
                break;
            case 'COMPLETAR':
                Conexion::abrir_conexion();
                $sql = "SELECT * FROM tpartes WHERE codpregunta = :codpregunta ORDER BY numero asc";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                $sentencia->execute();
                $respuestas = $sentencia->fetchAll();
                Conexion::cerrar_conexion();
                $completar = $_POST['completar'];
                $contar = 0;
                $bien = 0;

                foreach ($completar as $complete => $frase) {
                    foreach ($respuestas as $opciones) { //recorrer el array de respuestas correspondientes a la pregunta respectivca de la base de datos
                        if ($complete == $opciones['numero'] . $preg['codpregunta']) //verificar si el codigo de pregunta obtenida del formulario coincide con el de la base de datos
                            if ($opciones['tipo'] == "COMPLETAR") { //obtener la respuesta correcta
                                if (trim(mb_strtoupper($frase, 'utf-8')) == trim(mb_strtoupper($opciones['cadena'], 'utf-8'))) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                    $bien++;
                                }
                                $contar++;
                            }
                    }
                }
                $nota = 0;
                $nota_parcial = 0;
                if ($contar > 0) {
                    $nota_parcial = round($preg['valor'] / $contar, 2); //nota parcial para cada subitem
                    $nota = $nota_parcial * $bien; //nota para el examen
                }

                if ($nota >= $preg['valor'])
                    $nota = $preg['valor'];
                else if ($nota <= 0)
                    $nota = 0;


                foreach ($completar as $complete => $frase) {
                    foreach ($respuestas as $opciones) { //recorrer el array de respuestas correspondientes a la pregunta respectivca de la base de datos
                        if ($complete == $opciones['numero'] . $preg['codpregunta']) //verificar si el codigo de pregunta obtenida del formulario coincide con el de la base de datos
                            if ($opciones['tipo'] == "COMPLETAR") { //obtener la respuesta correcta
                                $bandera = 0;
                                if (trim(mb_strtoupper($frase, 'utf-8')) == trim(mb_strtoupper($opciones['cadena'], 'utf-8'))) { //verificar si el seleccionado por el estudiante es igual al de la base de datos
                                    $bandera = $nota_parcial;
                                } else {
                                    $bandera = 0;
                                }
                                if ($frase != null) {
                                    Conexion::abrir_conexion();
                                    $sql = "INSERT INTO testudianterespuesta(respuesta,valor,codpregunta,codme,numero) VALUES(:respuesta,:valor,:codpregunta,:codme,:numero)";
                                    $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                    $sentencia->bindParam(":respuesta", $frase, PDO::PARAM_STR);
                                    $sentencia->bindParam(":valor", $bandera, PDO::PARAM_STR);
                                    $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                    $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
                                    $sentencia->bindParam(":numero", $opciones['numero'], PDO::PARAM_STR);
                                    $sentencia->execute();
                                    Conexion::cerrar_conexion();
                                }
                            }
                    }
                }
                $nota_total_examen += $nota;

                break;
        }
    }
    echo 'Su nota final es: ' . $nota_total_examen;

    Conexion::abrir_conexion();
    $sql = "INSERT INTO tnotaexamen(nota,codme) VALUES(:nota,:codme)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":nota", $nota_total_examen, PDO::PARAM_STR);
    $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
    $sentencia->execute();
    Conexion::cerrar_conexion();
    //header("Location:http://evaluaciones.conduespoch.com/Estudiante/Examen.php");
    //exit;
    ?><script>
        window.location = "../Examen.php";
    </script><?php
} catch (Exception $ex) {
    echo 'ERROR ' . $ex->getMessage();
}

function SeleccionSimple($codPregunta) {
    Conexion::abrir_conexion();
    $sql = "SELECT * FROM trespuesta INNER JOIN tpreguntarespuesta ON trespuesta.codrespuesta = tpreguntarespuesta.codrespuesta WHERE codpregunta = :codpregunta";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codpregunta", $codPregunta, PDO::PARAM_STR);
    $sentencia->execute();
    $respuestas = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    return $respuestas;
}

function obtenerMateriaExamen() {
    Conexion::abrir_conexion();
    $sql = "SELECT * FROM tmateriaexamen WHERE codexamen = :codexamen AND codestudiante = (SELECT codestudianteparalelo FROM testudianteparalelo WHERE codestudiante = :codestudiante)";
    $sentencia = Conexion::obtener_conexion()->prepare($sql);
    $sentencia->bindParam(":codexamen", $_POST['codexamen'], PDO::PARAM_STR);
    $sentencia->bindParam(":codestudiante", $_SESSION['codpersona'], PDO::PARAM_STR);
    $sentencia->execute();
    $consulta = $sentencia->fetchAll();
    Conexion::cerrar_conexion();
    return $consulta;
}

//echo '<br>El examen es: ' . $_POST['idexamen'];


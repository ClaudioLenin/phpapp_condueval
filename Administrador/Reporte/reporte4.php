<?php

require '../../app/ControlSesion.php';
require '../../app/conexion.php';
require 'respuestas.php';
require 'consultas.php';
require('fpdf/fpdf.php');
session_start();

class PDF extends FPDF {

    // Cabecera de página


    function Header() {
        // Logo
        $this->Image('../../assets/img/logo/conduespoch_sinletras.png', 100, 8, 15);
        // Arial bold 15
        $this->SetFont('Times', 'B', 16);
        // Movernos a la derecha
        $this->Cell(40);
        // Título
        $this->Cell(110, 40, 'ESCUELA DE CONDUCCION PROFESIONAL CONDUESPOCH E.P.', 0, 1, 'C');
        $this->SetFont('Times', '', 14);
        //SELECCIONAR DESCRIPCION DE EXAMEN
        $descripcion = Consultas::seleccionarDescripcionexamen($_POST['codme']);
        $this->Cell(190, -15, utf8_decode('Evaluación: ' . $descripcion['descripcion']), 0, 0, 'C');
        $this->Ln(7);
    }

// Pie de página
    function Footer() {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Times', 'I', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

// Tabla coloreada
    function FancyTable() {
        $this->Cell(190, 0, '', 'T');
        $this->Ln(10);
        //SELECCIONAR EXAMEN DEL ESTUDIANTE
        $quiz = array();
        $periodoseccionparalelomateria = Consultas::seleccionarPeriodoSeccionParaleloMateria($_POST['coddocentemateria']);
        //PERIODO
        $this->Cell(190, -10, utf8_decode('Periodo: ' . $periodoseccionparalelomateria['nomperiodo']), 0, 0, '');
        $this->Ln(7);

        //SECCION
        $this->Cell(190, -10, utf8_decode('Sección: ' . $periodoseccionparalelomateria['nomseccion']), 0, 0, '');
        $this->Ln(7);

        //PARALELO
        $this->Cell(190, -10, utf8_decode('Paralelo: ' . $periodoseccionparalelomateria['codparalelo']), 0, 0, '');
        $this->Ln(7);

        //MATERIA
        $this->Cell(190, -10, utf8_decode('Materia: ' . $periodoseccionparalelomateria['nommateria']), 0, 0, '');
        $this->Ln(7);

        //DOCENTE
        $this->Cell(190, -10, utf8_decode('Docente: ' . $periodoseccionparalelomateria['nompersona'] . " " . $periodoseccionparalelomateria['apepersona']), 0, 0, '');
        $this->Ln(7);
        //SELECCIONAR EXAMEN DEL ESTUDIANTE
        $quiz = array();
        $quiz = Consultas::seleccionarExamen();
        //SELECCIONAR NOMBRE DEL ESTUDIANTE
        $estudiante = Consultas::seleccionarEstudiante($_POST['codestudiante']);
        $this->Cell(190, -10, utf8_decode('Estudiante: ' . $estudiante['nompersona'] . " " . $estudiante['apepersona']), 0, 0, '');
        $this->Ln(7);

        //SELECCIONAR NOTA DE EXAMEN
        $nota = Consultas::seleccionarNota($_POST['codme']);
        $this->Cell(190, -10, utf8_decode('Calificación: ' . $nota['nota']), 0, 0, '');
        $this->Ln(1);
        // Colores, ancho de línea y fuente en negrita
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B', 14);
        // Cabecera
        $w = array(40, 35, 45, 40);
        // Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('Times', '', 12);
        // Datos
        $fill = false;
        $this->Cell(190, 7, '', 'T');
        $this->Ln();
        $examen = Consultas::seleccionarExamen();
        $codexamen = $examen['codexamen'];
        $respuestas_estudiante = Respuestas::respuestasEstudiante($_POST['codme']);
        if (isset($codexamen)) {
            Conexion::abrir_conexion();
            $sql = "SELECT * FROM tpregunta INNER JOIN texamenpregunta 
                ON tpregunta.codpregunta = texamenpregunta.codpregunta 
                WHERE texamenpregunta.codexamen = :codexamen order by tpregunta.codpregunta asc";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":codexamen", $codexamen, PDO::PARAM_STR);
            $sentencia->execute();
            $preguntas = $sentencia->fetchAll();
            Conexion::cerrar_conexion();
            $i = 0;
            foreach ($preguntas as $preg) {
                $i++;
                $this->MultiCell(190, 5, utf8_decode($i . ".- " . $preg['pregunta'] . " (" . $preg['valor'] . ",00 Puntos)"), 0, '', $fill);
                if ($preg['tipo'] == 'UNIR') {
                    $preguntas_respuestas = Respuestas::mostrarUnir($preg['codpregunta']);
                    $enunciado = array();
                    $respuesta = array();
                    $j = 0;
                    foreach ($preguntas_respuestas as $pregunta_respuesta) {
                        $enunciado[$j] = $pregunta_respuesta['enunciado'];
                        $respuesta[$j] = $pregunta_respuesta['respuesta'];
                        $j++;
                    }
                    $valor_p = 0;
                    foreach ($respuestas_estudiante as $re) {
                        if ($preg['codpregunta'] == $re['codpregunta']) {
                            if (Respuestas::buscarUnir($preguntas_respuestas, $re['pregunta'], $re['respuesta']) && $re['valor'] > 0) {
                                $valor_p += $re['valor'];
                            }
                        }
                    }
                    $this->MultiCell(190, 5, utf8_decode("Calificación: " . $valor_p . "pts."), 0, 'R', $fill);
                    foreach ($respuestas_estudiante as $re) {
                        if ($preg['codpregunta'] == $re['codpregunta']) {
                            if (Respuestas::buscarUnir($preguntas_respuestas, $re['pregunta'], $re['respuesta']) && $re['valor'] > 0) {
                                $this->MultiCell(190, 5, utf8_decode("      " . $re['pregunta'] . "       <=>       " . $re['respuesta'] . " (Correcto)"), 0, 'U', $fill);
                            } else {
                                $this->MultiCell(190, 5, utf8_decode("      " . $re['pregunta'] . "       <=>       " . $re['respuesta'] . " (Incorrecto)"), 0, 'U', $fill);
                            }
                        }
                    }
                    $this->SetTextColor(0, 0, 0);
                } else if ($preg['tipo'] == 'COMPLETAR') {
                    $respuestas_completar = Respuestas::mostrarCompletar($preg['codpregunta']);

                    $valor_p = 0;
                    foreach ($respuestas_estudiante as $re) {
                        if ($preg['codpregunta'] == $re['codpregunta']) {
                            $respuesta_e = $re['respuesta'];
                            $valor_p = $re['valor'];
                        }
                    }
                    $fragmentos = "";
                    $this->MultiCell(190, 5, utf8_decode("Calificación: " . $valor_p . "pts."), 0, 'R', $fill);
                    foreach ($respuestas_completar as $frase) {
                        if ($frase['tipo'] == "TEXTO") {
                            $fragmentos .= $frase['cadena'];
                        } else {
                            $buscar = Respuestas::buscarCompletar($respuestas_estudiante, $frase['numero'], $preg['codpregunta']);
                            if (isset($buscar) && $buscar != null && trim(mb_strtoupper($buscar, 'utf-8')) == trim(mb_strtoupper($frase['cadena'], 'utf-8'))) {
                                $fragmentos .= " ....." . $buscar . ".....(Correcto) ";
                            } else {
                                if ($buscar == '') {
                                    $fragmentos .= " ......................................., ";
                                } else {
                                    $fragmentos .= " ....." . $buscar . ".....(Incorrecto) ";
                                }
                            }
                        }
                        $buscar = null;
                    }
                    $this->MultiCell(190, 5, utf8_decode("      " . $fragmentos), 0, '', $fill);
                } else if ($preg['tipo'] == 'VERDADERO FALSO') {

                    $respuestas_simple = Respuestas::mostrarSimple($preg['codpregunta']);

                    $j = 0;
                    $correcta = null;
                    foreach ($respuestas_simple as $respuesta) {
                        $correcta = $respuesta['respuesta'];
                    }
                    $valor_p = 0;
                    foreach ($respuestas_estudiante as $re) {
                        if ($preg['codpregunta'] == $re['codpregunta']) {
                            $respuesta_e = $re['respuesta'];
                            $valor_p = $re['valor'];
                        }
                    }
                    $this->MultiCell(190, 5, utf8_decode("Calificación: " . $valor_p . "pts."), 0, 'R', $fill);
                    if ($valor_p == 0 || !isset($valor_p)) {
                        if (isset($respuesta_e)) {
                            if ($respuesta_e == 'verdadero') {
                                $this->MultiCell(190, 5, utf8_decode("      Verdadero (Incorrecto)"), 0, '', $fill);
                            } else {
                                $this->MultiCell(190, 5, utf8_decode("      Falso (Incorrecto)"), 0, '', $fill);
                            }

                            if ($respuesta_e == 'verdadero') {
                                $this->MultiCell(190, 5, utf8_decode("      Falso"), 0, '', $fill);
                            } else {
                                $this->MultiCell(190, 5, utf8_decode("      Verdadero"), 0, '', $fill);
                            }
                        } else {
                            $this->MultiCell(190, 5, utf8_decode("      Falso"), 0, '', $fill);
                            $this->MultiCell(190, 5, utf8_decode("      Verdadero"), 0, '', $fill);
                        }
                    } else if ($respuesta_e == $correcta) {
                        if ($respuesta_e == 'verdadero') {
                            $this->MultiCell(190, 5, utf8_decode("      Verdadero (Correcto)"), 0, '', $fill);
                        } else {
                            $this->MultiCell(190, 5, utf8_decode("      Falso (Correcto)"), 0, '', $fill);
                        }

                        if ($respuesta_e == 'verdadero') {
                            $this->MultiCell(190, 5, utf8_decode("      Falso"), 0, '', $fill);
                        } else {
                            $this->MultiCell(190, 5, utf8_decode("      Verdadero"), 0, '', $fill);
                        }
                    } else {
                        $this->MultiCell(190, 5, utf8_decode("      Falso"), 0, '', $fill);
                        $this->MultiCell(190, 5, utf8_decode("      Verdadero"), 0, '', $fill);
                    }
                } else if ($preg['tipo'] == 'SELECCION SIMPLE') {
                    $respuestas_simple = Respuestas::mostrarSimple($preg['codpregunta']);
                    $opciones = array();
                    $j = 0;
                    $correcta = null;
                    foreach ($respuestas_simple as $respuesta) {
                        $opciones[$j] = $respuesta['respuesta'];
                        $j++;
                        if ($respuesta['correcta'] == 'SI') {
                            $correcta = $respuesta['respuesta'];
                        }
                    }
                    $valor_p = 0;
                    foreach ($respuestas_estudiante as $re) {
                        if ($preg['codpregunta'] == $re['codpregunta']) {
                            $respuesta_e = $re['respuesta'];
                            $valor_p = $re['valor'];
                        }
                    }
                    $this->MultiCell(190, 5, utf8_decode("Calificación: " . $valor_p . "pts."), 0, 'R', $fill);
                    for ($j = 0; $j < Count($opciones); $j++) {
                        if ($valor_p == 0 || !isset($valor_p)) {
                            if ($opciones[$j] == $respuesta_e) {
                                $this->MultiCell(190, 5, utf8_decode("      " . $opciones[$j] . " (Incorrecto)"), 0, '', $fill);
                            } else {
                                $this->MultiCell(190, 5, utf8_decode("      " . $opciones[$j]), 0, '', $fill);
                            }
                        } else {
                            if ($respuesta_e == $correcta && $respuesta_e == $opciones[$j]) {
                                $this->MultiCell(190, 5, utf8_decode("      " . $opciones[$j] . " (Correcto)"), 0, '', $fill);
                            } else {
                                $this->MultiCell(190, 5, utf8_decode("      " . $opciones[$j]), 0, '', $fill);
                            }
                        }
                    }
                } else if ($preg['tipo'] == 'RESPUESTA SIMPLE') {
                    $respuesta_simple = Respuestas::mostrarSimple($preg['codpregunta']);
                    foreach ($respuesta_simple as $r) {
                        $respuesta = $r['respuesta'];
                    }
                    $valor_p = 0;
                    foreach ($respuestas_estudiante as $re) {
                        if ($preg['codpregunta'] == $re['codpregunta']) {
                            $respuesta_e = $re['respuesta'];
                            $valor_p = $re['valor'];
                        }
                    }
                    $this->MultiCell(190, 5, utf8_decode("Calificación: " . $valor_p . "pts."), 0, 'R', $fill);
                    if ($valor_p == 0 || !isset($valor_p)) {
                        $this->MultiCell(190, 5, utf8_decode("      " . $respuesta_e . " (Incorrecto) "), 0, '', $fill);
                    } else {
                        if ($respuesta_e == $respuesta) {
                            $this->MultiCell(190, 5, utf8_decode("      " . $respuesta_e . " (Correcto) "), 0, '', $fill);
                        } else {
                            $this->MultiCell(190, 5, utf8_decode("      " . $respuesta_e . " (Incorrecto) "), 0, '', $fill);
                        }
                    }
                } else if ($preg['tipo'] == 'SELECCION MULTIPLE') {
                    $respuestas = Respuestas::mostrarSimple($preg['codpregunta']);
                    $rmultiples = array();
                    $rmultiples = null;
                    $nota = array();
                    $nota = null;
                    $j = 0;
                    $valor_p = 0;
                    foreach ($respuestas_estudiante as $re) {
                        if ($preg['codpregunta'] == $re['codpregunta']) {
                            $rmultiples[$j] = $re['respuesta'];
                            $nota[$j] = $re['valor'];
                            $valor_p += $re['valor'];
                            $j++;
                        }
                    }
                    $this->MultiCell(190, 5, utf8_decode("Calificación: " . $valor_p . "pts."), 0, 'R', $fill);
                    foreach ($respuestas as $respuesta) {
                        $n = false;
                        $v = 0;
                        if ($respuesta['correcta'] == 'SI') {
                            for ($k = 0; $k < $j; $k++) {
                                if ($respuesta['respuesta'] == $rmultiples[$k]) {
                                    $n = true;
                                    $v = $nota[$k];
                                    break;
                                } else {
                                    $n = false;
                                }
                            }
                            if ($n == true && $v > 0) {
                                $this->MultiCell(190, 5, utf8_decode("      " . $respuesta['respuesta'] . " (Correcto) "), 0, '', $fill);
                            } else {
                                $this->MultiCell(190, 5, utf8_decode("      " . $respuesta['respuesta']), 0, '', $fill);
                            }
                        } else {
                            for ($k = 0; $k < $j; $k++) {
                                if ($respuesta['respuesta'] == $rmultiples[$k]) {
                                    $n = true;
                                    $v = $nota[$k];
                                    break;
                                } else {
                                    $n = false;
                                }
                            }
                            if ($n == true) {
                                $this->MultiCell(190, 5, utf8_decode("      " . $respuesta['respuesta'] . " (Incorrecto) "), 0, '', $fill);
                            } else {
                                $this->MultiCell(190, 5, utf8_decode("      " . $respuesta['respuesta']), 0, '', $fill);
                            }
                        }
                    }
                }
                $this->Ln();
                $fill = !$fill;
            }
        }

        $estudiante = Consultas::seleccionarEstudiante($_POST['codestudiante']);
        $this->Cell(190, 50, utf8_decode('Firma:_________________________________________ Cédula:___________________________________'), 0, 0, '');
        $this->Ln(10);
    }

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
// Títulos de las columnas
// Carga de datos
$pdf->FancyTable();
$pdf->Output();

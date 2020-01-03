<?php

require '../../app/conexion.php';
require('fpdf/fpdf.php');
require 'consultas.php';
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
        Conexion::abrir_conexion();
        $sql = "SELECT descripcion FROM texamen WHERE codexamen = :codexamen";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codexamen", $_POST['codexamen'], PDO::PARAM_STR);
        $sentencia->execute();
        $descripcion = $sentencia->fetch();
        Conexion::cerrar_conexion();
        $this->Cell(190, -15, utf8_decode('Evaluación: ' . $descripcion['descripcion'] . " (Cuestionario)"), 0, 0, 'C');
        // Salto de línea
        $this->Ln(3);
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
        $this->Cell(190, 0, '', 'T');
        $this->Ln();

        if (isset($_POST['codexamen'])) {


            $periodoseccionparalelomateria = Consultas::seleccionarPeriodoSeccionParaleloMateria($_POST['coddocentemateria']);
            //PERIODO
            $this->Cell(190, 10, utf8_decode('Periodo: ' . $periodoseccionparalelomateria['nomperiodo']), 0, 0, '');
            $this->Ln(7);

            //SECCION
            $this->Cell(190, 10, utf8_decode('Sección: ' . $periodoseccionparalelomateria['nomseccion']), 0, 0, '');
            $this->Ln(7);

            //PARALELO
            $this->Cell(190, 10, utf8_decode('Paralelo: ' . $periodoseccionparalelomateria['codparalelo']), 0, 0, '');
            $this->Ln(7);

            //MATERIA
            $this->Cell(190, 10, utf8_decode('Materia: ' . $periodoseccionparalelomateria['nommateria']), 0, 0, '');
            $this->Ln(7);

            //DOCENTE
            $this->Cell(190, 10, utf8_decode('Docente: ' . $periodoseccionparalelomateria['nompersona'] . " " . $periodoseccionparalelomateria['apepersona']), 0, 0, '');
            $this->Ln(10);
            $this->Cell(190, 0, '', 'T');
            $this->Ln(7);


            Conexion::abrir_conexion();
            $sql = "SELECT * FROM tpregunta INNER JOIN texamenpregunta 
                ON tpregunta.codpregunta = texamenpregunta.codpregunta 
                WHERE texamenpregunta.codexamen = :codexamen order by tpregunta.codpregunta asc";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":codexamen", $_POST['codexamen'], PDO::PARAM_STR);
            $sentencia->execute();
            $preguntas = $sentencia->fetchAll();
            Conexion::cerrar_conexion();

            $i = 0;
            foreach ($preguntas as $preg) {
                $i++;
                $this->MultiCell(190, 5, utf8_decode($i . ".- " . $preg['pregunta'] . " (" . $preg['valor'] . ",00 Puntos)"), 0, '', $fill);
                if ($preg['tipo'] == 'UNIR') {
                    //mostrarUnir($preg['codpregunta']);
                    Conexion::abrir_conexion();
                    //UNIR
                    $sql = "SELECT * FROM tlista INNER JOIN trespuesta ON trespuesta.codrespuesta = tlista.codrespuesta INNER JOIN tlistapregunta ON tlistapregunta.codlista = tlista.codlista WHERE codpregunta = :codpregunta";
                    $sentencia = Conexion::obtener_conexion()->prepare($sql);
                    $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                    $sentencia->execute();
                    $preguntas_respuestas = $sentencia->fetchAll();
                    Conexion::cerrar_conexion();
                    $preguntas = array();
                    $respuestas = array();
                    foreach ($preguntas_respuestas as $pregunta_respuesta) {
                        $preguntas[] = $pregunta_respuesta['enunciado'];
                        $respuestas[] = $pregunta_respuesta['respuesta'];
                    }
                    shuffle($preguntas);
                    shuffle($respuestas);
                    $preg_resp = array();
                    $this->MultiCell(190, 5, utf8_decode("Unir con lineas"), 0, 'R', $fill);
                    for ($j = 0; $j < Count($preguntas); $j++) {
                        $this->MultiCell(190, 5, utf8_decode("      " . $preguntas[$j] . "              " . $respuestas[$j]), 0, '', $fill);
                    }
                } else if ($preg['tipo'] == 'COMPLETAR') {
                    $this->MultiCell(190, 5, utf8_decode("Completar espacios vacíos"), 0, 'R', $fill);
                    Conexion::abrir_conexion();
                    //COMPLETAR
                    $sql = "SELECT * FROM tpartes WHERE codpregunta = :codpregunta ORDER BY numero ASC";
                    $sentencia = Conexion::obtener_conexion()->prepare($sql);
                    $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                    $sentencia->execute();
                    $frases = $sentencia->fetchAll();
                    Conexion::cerrar_conexion();

                    $f = null;
                    foreach ($frases as $frase) {
                        if ($frase['tipo'] == "TEXTO") {
                            $f .= $frase['cadena'];
                        } else {
                            $f .= "............................................";
                        }
                    }
                    $this->MultiCell(190, 5, utf8_decode("      " . $f), 0, '', $fill);
                } else if ($preg['tipo'] == 'VERDADERO FALSO') {
                    $this->MultiCell(190, 5, utf8_decode("Seleccionar verdadero o falso"), 0, 'R', $fill);
                    $this->MultiCell(190, 5, utf8_decode("      - Verdadero"), 0, '', $fill);
                    $this->MultiCell(190, 5, utf8_decode("      - Falso"), 0, '', $fill);
                } else if ($preg['tipo'] == 'SELECCION MULTIPLE' || $preg['tipo'] == 'SELECCION SIMPLE') {
                    Conexion::abrir_conexion();
                    $sql = "SELECT * FROM trespuesta INNER JOIN tpreguntarespuesta ON trespuesta.codrespuesta = tpreguntarespuesta.codrespuesta WHERE codpregunta = :codpregunta";
                    $sentencia = Conexion::obtener_conexion()->prepare($sql);
                    $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                    $sentencia->execute();
                    $respuestas = $sentencia->fetchAll();
                    Conexion::cerrar_conexion();
                    shuffle($respuestas);
                    $n = 0;
                    foreach ($respuestas as $respuesta) {
                        if ($respuesta['correcta'] == 'SI') {
                            $n++;
                        }
                    }
                    $this->MultiCell(190, 5, utf8_decode("      Seleccionar " . $n . " respuesta(s)"), 0, 'R', $fill);
                    foreach ($respuestas as $respuesta) {
                        $this->MultiCell(190, 5, utf8_decode("      " . $respuesta['respuesta']), 0, '', $fill);
                    }
                } else if ($preg['tipo'] == 'RESPUESTA SIMPLE') {
                    $this->MultiCell(190, 5, utf8_decode("      Respuesta:......................"
                                    . "................................................................."
                                    . "................................................................."
                                    . "................................................................."
                                    . "................................................................."
                                    . "................................................................."
                                    . "................................................................."
                                    . "......................................................................................................"), 0, '', $fill);
                }
                $this->Ln();
                $fill = !$fill;
            }
            // Línea de cierre
            $this->Cell(190, 0, '', 'T');
        }
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

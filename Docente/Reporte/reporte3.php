<?php

require '../../app/ControlSesion.php';
require '../../app/conexion.php';
require 'respuestas.php';
require 'consultas.php';
require('fpdf/fpdf.php');
session_start();

class PDF extends FPDF {

    // Cabecera de p�gina


function Header() {
        // Logo
        $this->Image('../../assets/img/logo/conduespoch_sinletras.png', 100, 8, 15);
        // Arial bold 15
        $this->SetFont('Times', 'B', 16);
        // Movernos a la derecha
        $this->Cell(40);
        // T�tulo
        $this->Cell(110, 40, 'ESCUELA DE CONDUCCION PROFESIONAL CONDUESPOCH E.P.', 0, 1, 'C');
        $this->SetFont('Times', '', 14);
        //ULTIMAS PALABRAS
        $this->Cell(190, -15, utf8_decode('EVALUACIONES POR MATERIA'), 0, 0, 'C');
        $this->Ln(7);
    }
// Pie de p�gina
    function Footer() {
        // Posici�n: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Times', 'I', 8);
        // N�mero de p�gina
        $this->Cell(0, 10, utf8_decode('P�gina ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

// Tabla coloreada
    function FancyTable() {
        
        $this->Cell(190, 0, '', 'T');
        $this->Ln(10);
        //SELECCIONAR EXAMEN DEL ESTUDIANTE
        $quiz = array();
        $periodoseccionparalelomateria = Consultas::seleccionarPeriodoSeccionParaleloMateria($_POST['coddocentemateriareporte3']);
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
        $this->Cell(190, -10, utf8_decode('Docente: ' . $periodoseccionparalelomateria['nompersona']." ".$periodoseccionparalelomateria['apepersona']), 0, 0, '');
        $this->Ln(1);
        $this->Cell(190, 0, '', 'T');
        $this->Ln(7);
        
        // Colores, ancho de línea y fuente en negrita
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B', 14);
        // Cabecera
        $w = array(40, 35, 45, 40);	
	$this->Cell(70, 7, utf8_decode('EVALUACIÓN'), 1, 0, 'C', true);
        $this->Cell(55, 7, utf8_decode('FECHA EVALUACIÓN'), 1, 0, 'C', true);
        $this->Cell(37, 7, utf8_decode('ESTUDIANTES'), 1, 0, 'C', true);
        $this->Cell(28, 7, utf8_decode('PROMEDIO'), 1, 0, 'C', true);

        $this->Ln();
        // Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('Times', '', 12);
        // Datos
        $fill = false;
	
	if (isset($_POST['coddocentemateriareporte3'])) {
            Conexion::abrir_conexion();
            $sql = "SELECT DISTINCT tmateriaexamen.codexamen FROM tmateriaexamen WHERE coddocentemateria = :coddocentemateria ORDER BY tmateriaexamen.codexamen ASC";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":coddocentemateria", $_POST['coddocentemateriareporte3'], PDO::PARAM_STR);
            $sentencia->execute();
            $codexamenes = $sentencia->fetchAll();
            Conexion::cerrar_conexion();
            $json = array();
            foreach ($codexamenes as $exam) {
                //PROMEDIO DE NOTAS DE CADA EXAMEN
                Conexion::abrir_conexion();
                $sql = "SELECT Count(*) AS cantidad, AVG(nota) AS promedio FROM tnotaexamen INNER JOIN tmateriaexamen "
                        . "ON tnotaexamen.codme = tmateriaexamen.codme "
                        . "WHERE tmateriaexamen.codexamen = :codexamen";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
                $sentencia->execute();
                $promedio = $sentencia->fetch();
                Conexion::cerrar_conexion();

                //CANTIDAD DE ALUMNOS POR CADA EXAMEN
                Conexion::abrir_conexion();
                $sql = "SELECT Count(*) AS alumnos FROM tmateriaexamen WHERE codexamen = :codexamen";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
                $sentencia->execute();
                $alumnos = $sentencia->fetch();
                Conexion::cerrar_conexion();

                //DATOS DE CADA EXAMEN
                Conexion::abrir_conexion();
                $sql = "SELECT * FROM texamen WHERE codexamen = :codexamen";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
                $sentencia->execute();
                $examen = $sentencia->fetch();
                Conexion::cerrar_conexion();
                $this->Cell(70, 10, utf8_decode($examen['descripcion']), 'LR', 0, 'C', $fill);
                $this->Cell(55, 10, utf8_decode($examen['fechaejecucion']), 'LR', 0, 'C', $fill);
                $this->Cell(37, 10, utf8_decode($promedio['cantidad']." de ".$alumnos['alumnos']), 'LR', 0, 'C', $fill);
                $this->Cell(28, 10, utf8_decode($promedio['promedio']), 'LR', 0, 'C', $fill);

                $this->Ln();
                $fill = !$fill;
            }
            foreach ($codexamenes as $exam) {
                //PROMEDIO DE NOTAS DE CADA EXAMEN
                Conexion::abrir_conexion();
                $sql = "SELECT Count(*) AS cantidad, AVG(nota) AS promedio FROM tnotaexamen INNER JOIN tmateriaexamen "
                        . "ON tnotaexamen.codme = tmateriaexamen.codme "
                        . "WHERE tmateriaexamen.codexamen = :codexamen";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
                $sentencia->execute();
                $promedio = $sentencia->fetch();
                Conexion::cerrar_conexion();

                //CANTIDAD DE ALUMNOS POR CADA EXAMEN
                Conexion::abrir_conexion();
                $sql = "SELECT Count(*) AS alumnos FROM tmateriaexamen WHERE codexamen = :codexamen";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
                $sentencia->execute();
                $alumnos = $sentencia->fetch();
                Conexion::cerrar_conexion();

                //DATOS DE CADA EXAMEN
                Conexion::abrir_conexion();
                $sql = "SELECT * FROM texamen WHERE codexamen = :codexamen";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":codexamen", $exam['codexamen'], PDO::PARAM_STR);
                $sentencia->execute();
                $examen = $sentencia->fetch();
                Conexion::cerrar_conexion();
                $this->Cell(70, 10, utf8_decode($examen['descripcion']), 'LR', 0, 'C', $fill);
                $this->Cell(55, 10, utf8_decode($examen['fechaejecucion']), 'LR', 0, 'C', $fill);
                $this->Cell(37, 10, utf8_decode($promedio['cantidad']." de ".$alumnos['alumnos']), 'LR', 0, 'C', $fill);
                $this->Cell(28, 10, utf8_decode($promedio['promedio']), 'LR', 0, 'C', $fill);

                $this->Ln();
                $fill = !$fill;
            }
            // L�nea de cierre
            $this->Cell(190, 0, '', 'T');
        }
        
    }



}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 12);
// T�tulos de las columnas
// Carga de datos
$pdf->FancyTable();
$pdf->Output();

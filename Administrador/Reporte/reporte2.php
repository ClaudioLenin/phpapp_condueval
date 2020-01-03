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
        $this->Cell(190, -15, utf8_decode('CANTIDAD DE EVALUACIONES POR MATERIA'), 0, 0, 'C');
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
        //SELECCIONAR EXAMEN DEL ESTUDIANTE
        $quiz = array();
        $periodoseccionparalelo = Consultas::seleccionarPeriodoSeccionParalelo($_POST['codperiodoseccionparalelo']);
        $this->Cell(190, 0, '', 'T');
        $this->Ln(7);
        //PERIODO
        $this->Cell(190, 10, utf8_decode('Periodo: ' . $periodoseccionparalelo['nomperiodo']), 0, 0, '');
        $this->Ln(7);

        //SECCION
        $this->Cell(190, 10, utf8_decode('Sección: ' . $periodoseccionparalelo['nomseccion']), 0, 0, '');
        $this->Ln(7);

        //PARALELO
        $this->Cell(190, 10, utf8_decode('Paralelo: ' . $periodoseccionparalelo['codparalelo']), 0, 0, '');
        $this->Ln(10);
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
        $this->Cell(87, 7, "MATERIA", 1, 0, 'C', true);
        $this->Cell(88, 7, "DOCENTE", 1, 0, 'C', true);
        $this->Cell(15, 7, utf8_decode('No'), 1, 0, 'C', true);
        $this->Ln();
        // Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('Times', '', 12);
        // Datos
        $fill = false;

        if (isset($_POST['codperiodoseccionparalelo'])) {
            Conexion::abrir_conexion();
            $sql = "SELECT * FROM tdocentemateria "
                    . "INNER JOIN tmateria ON tdocentemateria.codmateria = tmateria.codmateria "
                    . "INNER JOIN tpersona ON tpersona.codpersona = tdocentemateria.codpersona "
                    . "WHERE codperiodoseccionparalelo = :codperiodoseccionparalelo";
            $sentencia = Conexion::obtener_conexion()->prepare($sql);
            $sentencia->bindParam(":codperiodoseccionparalelo", $_POST['codperiodoseccionparalelo'], PDO::PARAM_STR);
            //$sentencia->bindParam(":codpersona", $_SESSION['codpersona'], PDO::PARAM_STR);
            $sentencia->execute();
            $materias = $sentencia->fetchAll();
            Conexion::cerrar_conexion();
            $json = array();

            foreach ($materias as $mat) {

                //CANTIDAD DE EXAMENES POR MATERIA
                Conexion::abrir_conexion();
                $sql = "SELECT Count(DISTINCT codexamen) as examenes FROM tmateriaexamen WHERE coddocentemateria = :coddocentemateria";
                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                $sentencia->bindParam(":coddocentemateria", $mat['coddocentemateria'], PDO::PARAM_STR);
                $sentencia->execute();
                $examenes = $sentencia->fetch();
                Conexion::cerrar_conexion();

                $this->Cell(87, 10, utf8_decode($mat['nommateria']), 'LR', 0, 'C', $fill);
                $this->Cell(88, 10, utf8_decode($mat['nompersona'] . " " . $mat['apepersona']), 'LR', 0, 'C', $fill);
                $this->Cell(15, 10, $examenes['examenes'], 'LR', 0, 'C', $fill);
                $this->Ln();
                $fill = !$fill;
                // Línea de cierre
            }
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

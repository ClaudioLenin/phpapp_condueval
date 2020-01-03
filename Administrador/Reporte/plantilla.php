<?php
require('fpdf/fpdf.php');
class PDF extends FPDF {

// Cabecera de página
    function Header() {
        // Logo
        $this->Image('../../assets/img/logo/conduespoch_sinletras.png', 10, 8, 15);
        // Arial bold 15
        $this->SetFont('Arial', 'B', 18);
        // Movernos a la derecha
        $this->Cell(40);
        // Título
        $this->Cell(110, 10, 'LISTADO GENERAL DE EVALUACIONES', 0, 0, 'C');
        // Salto de línea
        $this->Ln(20);
        //Cabeceras de las tablas
        $this->Cell(110,10,'Nombre Materia',1,0,'C',0);
        $this->Cell(80,10,utf8_decode('Cantidad Evaluaciones'),1,0,'C',0);
    }

// Pie de página
    function Footer() {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10,  utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

}
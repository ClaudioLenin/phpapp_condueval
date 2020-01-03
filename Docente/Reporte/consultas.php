<?php
class Consultas{
    function seleccionarExamen(){
        //SELECCIONAR EXAMEN DEL ESTUDIANTE
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM texamen INNER JOIN tmateriaexamen ON tmateriaexamen.codexamen = texamen.codexamen "
                . "WHERE tmateriaexamen.codme = :codme AND tmateriaexamen.codestudiante = :codestudiante";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codme", $_POST['codme'], PDO::PARAM_STR);
        $sentencia->bindParam(":codestudiante", $_POST['codestudiante'], PDO::PARAM_STR);
        $sentencia->execute();
        $quiz = $sentencia->fetch();
        Conexion::cerrar_conexion();
        return $quiz;
    }
    function seleccionarMateria($coddocentemateria){
        //SELECCIONAR MATERIA
        Conexion::abrir_conexion();
        $sql = "SELECT nommateria,codpersona FROM tmateria "
                . "INNER JOIN tdocentemateria ON tdocentemateria.codmateria = tmateria.codmateria "
                . " WHERE tdocentemateria.coddocentemateria = :coddocentemateria";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":coddocentemateria", $coddocentemateria, PDO::PARAM_STR);
        $sentencia->execute();
        $materia = $sentencia->fetch();
        //$codpersona = $materia['codpersona'];
        Conexion::cerrar_conexion();
        return $materia;
    }
    function seleccionarDocente($codpersona){
        //SELECCIONAR MATERIA
        Conexion::abrir_conexion();
        $sql = "SELECT nompersona,apepersona FROM tpersona WHERE codpersona = :codpersona";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codpersona", $codpersona, PDO::PARAM_STR);
        $sentencia->execute();
        $docente = $sentencia->fetch();
        Conexion::cerrar_conexion();
        return $docente;
    }
    function seleccionarEstudiante($codestudiante){
        //SELECCIONAR MATERIA
        Conexion::abrir_conexion();
        $sql = "SELECT tpersona.nompersona,tpersona.apepersona FROM tpersona "
                . "INNER JOIN testudianteparalelo ON testudianteparalelo.codestudiante = tpersona.codpersona "
                . "WHERE testudianteparalelo.codestudianteparalelo = :codestudianteparalelo";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codestudianteparalelo", $codestudiante, PDO::PARAM_STR);
        $sentencia->execute();
        $estudiante = $sentencia->fetch();
        Conexion::cerrar_conexion();
        return $estudiante;
    }
    function seleccionarDescripcionexamen($codme){
        Conexion::abrir_conexion();
        $sql = "SELECT descripcion FROM texamen INNER JOIN tmateriaexamen "
                . "ON tmateriaexamen.codexamen = texamen.codexamen "
                . "WHERE tmateriaexamen.codme = :codme";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
        $sentencia->execute();
        $descripcion = $sentencia->fetch();
        Conexion::cerrar_conexion();
        return $descripcion;
    }
	function seleccionarNota($codme){
        Conexion::abrir_conexion();
        $sql = "SELECT nota FROM tnotaexamen WHERE codme = :codme";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codme", $codme, PDO::PARAM_STR);
        $sentencia->execute();
        $nota = $sentencia->fetch();
        Conexion::cerrar_conexion();
        return $nota;
    }
	function seleccionarPeriodoSeccionParalelo($codperiodoseccionparalelo){
        Conexion::abrir_conexion();
        $sql = "SELECT tperiodoseccionparalelo.codparalelo,tseccion.nomseccion,tperiodo.nomperiodo "
                . "FROM tperiodoseccionparalelo INNER JOIN tperiodoseccion "
                . "ON tperiodoseccionparalelo.codperiodoseccion = tperiodoseccion.codperiodoseccion "
                . "INNER JOIN tseccion ON tperiodoseccion.codseccion = tseccion.codseccion "
                . "INNER JOIN tperiodo ON tperiodo.codperiodo = tperiodoseccion.codperiodo WHERE "
                . "tperiodoseccionparalelo.codperiodoseccionparalelo = :codperiodoseccionparalelo";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codperiodoseccionparalelo", $codperiodoseccionparalelo, PDO::PARAM_STR);
        $sentencia->execute();
        $periodoseccionparalelo = $sentencia->fetch();
        Conexion::cerrar_conexion();
        return $periodoseccionparalelo;
    }

	function seleccionarPeriodoSeccionParaleloMateria($coddocentemateria){
        Conexion::abrir_conexion();
        $sql =  "SELECT tperiodoseccionparalelo.codparalelo,tseccion.nomseccion,tperiodo.nomperiodo,tmateria.nommateria,tpersona.nompersona,tpersona.apepersona FROM tperiodoseccionparalelo "
                . "INNER JOIN tperiodoseccion ON tperiodoseccionparalelo.codperiodoseccion = tperiodoseccion.codperiodoseccion "
                . "INNER JOIN tseccion ON tperiodoseccion.codseccion = tseccion.codseccion "
                . "INNER JOIN tperiodo ON tperiodo.codperiodo = tperiodoseccion.codperiodo "
                . "INNER JOIN tdocentemateria ON tdocentemateria.codperiodoseccionparalelo = tperiodoseccionparalelo.codperiodoseccionparalelo "
                . "INNER JOIN tmateria ON tmateria.codmateria = tdocentemateria.codmateria "
                . "INNER JOIN tpersona ON tpersona.codpersona = tdocentemateria.codpersona "
                . "WHERE tdocentemateria.coddocentemateria = :coddocentemateria";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":coddocentemateria", $coddocentemateria, PDO::PARAM_STR);
        $sentencia->execute();
        $periodoseccionparalelomateria = $sentencia->fetch();
        Conexion::cerrar_conexion();
        return $periodoseccionparalelomateria;
    }


}

<?php
require '../../app/ControlSesion.php';
require '../../app/conexion.php';
require 'respuestas.php';
session_start();
if (ControlSesion::sesion_iniciada() && $_SESSION['tippersona'] == 'Estudiante') {
    if (isset($_COOKIE['establecido'])) {//&&$_GET['idexamen']==$_COOKIE['establecido']){
      header("Location:../Reporte.php");
      } 
    try {
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM texamen WHERE codexamen = :codexamen";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codexamen", $_GET['ce'], PDO::PARAM_STR);
        $sentencia->execute();
        $quiz = $sentencia->fetchAll();
        Conexion::cerrar_conexion();
        
    } catch (Exception $ex) {
        Conexion::cerrar_conexion();
        echo 'ERROR ' . $ex->getMessage();
    }
    ?>
    <!DOCTYPE html>
    <html class="app-ui">

        <head>
            <!-- Meta -->
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

            <!-- Document title -->
            <title>Estudiante</title>

            <meta name="description" content="AppUI - Student Dashboard Template & UI Framework" />
            <meta name="author" content="rustheme" />
            <meta name="robots" content="noindex, nofollow" />

            <!-- Favicons -->
            <link rel="icon" href="http://conduespoch.com/sites/default/files/cLogoMini.png"/>

            <!-- Google fonts -->
            <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,900%7CRoboto+Slab:300,400%7CRoboto+Mono:400" />

            <!-- Page JS Plugins CSS -->
            <link rel="stylesheet" href="../../assets/js/plugins/slick/slick.min.css" />
            <link rel="stylesheet" href="../../assets/js/plugins/slick/slick-theme.min.css" />

            <!-- AppUI CSS stylesheets -->
            <link rel="stylesheet" id="css-font-awesome" href="../../assets/css/font-awesome.css" />
            <link rel="stylesheet" id="css-ionicons" href="../../assets/css/ionicons.css" />
            <link rel="stylesheet" id="css-bootstrap" href="../../assets/css/bootstrap.css" />
            <link rel="stylesheet" id="css-app" href="../../assets/css/app.css" />
            <link rel="stylesheet" id="css-app-custom" href="../../assets/css/app-custom.css" />
            <!-- End Stylesheets -->

            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <script type="text/javascript" src="examen.js"></script>
            <link rel="stylesheet" id="css-app" href="../../assets/css/estilos.css" />



        </head>

        <body class="app-ui layout-has-drawer layout-has-fixed-header">
            <div class="app-layout-canvas">
                <div class="app-layout-container">

                    <!-- Drawer -->
                    <aside class="app-layout-drawer">

                        <!-- Drawer scroll area -->
                        <div class="app-layout-drawer-scroll">
                            <!-- Drawer logo -->
                            <div class="col" style="width: 160px;padding-left: 60px" >
                                <a href="../index.php"><img class="img-responsive" src="../../assets/img/logo/logo conduespoch.png" title="AppUI" alt="AppUI" /></a>
                            </div>
                           
                            <!-- End drawer navigation -->

                            <nav class="drawer-main">
                                <ul class="nav nav-drawer">
                                    <li class="nav-item nav-drawer-header"> Panel de Control</li>
                                    <li class="nav-item"><a href="../../Estudiante/Examen.php"><i class="fa fa-desktop"></i> Inicio</a></li>
                                    <li class="nav-item nav-drawer-header"> CUESTIONARIO</li>
                                    <li><a href="#"><i class="fa fa-bookmark"></i> Calificación: <?php echo Respuestas::obtenerNotaTotal($_SESSION['codpersona'],$_GET['ce']);?></a></li>
                                </ul>
                                <div style="padding-left: 50px;padding-top: 20px">
                                    <form method="post" action="../Reporte.php">
                                        <input type="hidden" name="cm" id="cm" value="<?php echo $_GET['cm'];?>">
                                        <input type="submit" class="btn btn-danger" value="Finalizar revisión">
                                    </form>
                                </div>
                            </nav>
                        </div>
                        <!-- End drawer scroll area -->
                    </aside>
                    <!-- End drawer -->

                    <!-- Header -->
                    <header class="app-layout-header"> 
                        <nav class="navbar navbar-default">
                            <div class="container-fluid">
                                <div class="navbar-header">
                                    <button class="pull-left hidden-lg hidden-md navbar-toggle " type="button" data-toggle="layout" data-action="sidebar_toggle">
                                        <span class="sr-only">Toggle drawer</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                </div>

                                <div class="collapse navbar-collapse" id="header-navbar-collapse">
                                    <span class="navbar-page-title" style="color: #000;text-transform: uppercase">
                                        <?php
                                        Conexion::abrir_conexion();
                                        $sql = "SELECT nommateria FROM tmateria INNER JOIN tdocentemateria ON tmateria.codmateria = tdocentemateria.codmateria "
                                                . "WHERE tdocentemateria.coddocentemateria = :coddocentemateria";
                                        $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                        $sentencia->bindParam(":coddocentemateria", $_GET['cm'], PDO::PARAM_STR);
                                        $sentencia->execute();
                                        $nommateria = $sentencia->fetch();
                                        Conexion::cerrar_conexion();

                                        echo $_SESSION['nomperiodo'] . " - " . $nommateria['nommateria']
                                        ?>
                                    </span>
                                    <ul class="nav navbar-right navbar-toolbar hidden-sm hidden-xs">
                                        <li class="dropdown dropdown-profile">
                                            <a href="javascript:void(0)" data-toggle="dropdown">
                                                <span class="m-r-sm" style="color: #000;text-transform: uppercase"><?php
                                                    echo $_SESSION['nompersona'];
                                                    echo " " . $_SESSION['apepersona'];
                                                    ?><span class="caret"></span></span>
                                                <img class="img-avatar img-avatar-48" src="../../assets/img/avatars/teacher.png" alt="User profile pic" />
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li class="dropdown-header">
                                                    Opciones
                                                </li>
                                                <li>
                                                    <a href="../../cerrar.php">Salir</a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>


                                    <!-- .navbar-right -->
                                </div>
                            </div>
                            <!-- .container-fluid -->
                        </nav>
                        <!-- .navbar-default -->
                    </header>
                    <!-- End header -->
                    <main class="app-layout-content">
                        <div class="container-fluid p-y-md">
                            <div class="row">
                                <div class="container-fluid p-y-md estructura color-estructura">
                                    <section class="section margen-interno">
                                        <article class="article" >
                                            <div class="jumbotron">
                                                <form method="POST" action="../Reporte.php" enctype="multipart/form-data" onsubmit="return validaciones();">
                                                    <div style="overflow-x:auto;">

                                                        <?php
                                                        if (isset($_GET['ce'])) {
                                                            $examen = Respuestas::seleccionarExamen($_GET['ce']); //todas las preguntas del examen
                                                            $respuestas_estudiante = Respuestas::respuestasEstudiante($_SESSION['codpersona'], $_GET['ce']); //todas las respuestas del estudiante
                                                            if ($examen != null) {
                                                                $x = 0;
                                                                foreach ($examen as $preg) {
                                                                    $respuesta = null;
                                                                    $respuesta_e = null;
                                                                    $valor_p = 0;
                                                                    $nota_pregunta = Respuestas::notaPregunta($respuestas_estudiante, $preg['codpregunta']);
                                                                    $x++;
                                                                    ?>
                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4" style="background-color:#eceff1">
                                                                        <div class="card" style="width: 100%;">
                                                                            <div class="card-body" style="padding-top: 3px;padding-bottom: 3px;padding-left: 15px;margin-top: 20px">
                                                                                <h5 style="font-size: 12px">Pregunta <b style="font-size: 15px"><?php echo $x; ?></b></h5>
                                                                                <h5 style="font-size: 12px">Puntúa <?php
                                                                                    if (is_int($nota_pregunta) || $nota_pregunta == 0)
                                                                                        echo $nota_pregunta . ".00" . " sobre " . $preg['valor'] . ".00";
                                                                                    else {
                                                                                        echo $nota_pregunta . " sobre " . $preg['valor'] . ".00";
                                                                                    }
                                                                                    ?></h5>
                                                                                <?php
                                                                                echo '<i class="fa fa-edit"></i>';
                                                                                if ($preg['tipo'] == 'UNIR') {
                                                                                    echo " Emparejar opciones";
                                                                                }
                                                                                if ($preg['tipo'] == 'COMPLETAR') {
                                                                                    echo " Completar espacios vacíos";
                                                                                }
                                                                                if ($preg['tipo'] == 'RESPUESTA SIMPLE') {
                                                                                    echo " Responder pregunta";
                                                                                }
                                                                                if ($preg['tipo'] == 'SELECCION SIMPLE' || $preg['tipo'] == 'VERDADERO FALSO') {
                                                                                    echo " Marcar respuesta";
                                                                                }
                                                                                if ($preg['tipo'] == 'SELECCION MULTIPLE') {
                                                                                    echo " Marcar respuestas";
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-lg-10 col-md-12 col-sm-12 col-xs-12">
                                                                        <div class="card">
                                                                            <div class="card-body" style="background-color:#e1f5fe;min-height: 300px">
                                                                                <blockquote class="blockquote mb-0">
                                                                                    <nav class="drawer-main">

                                                                                        <ul class="nav nav-drawer">
                                                                                            <?php
                                                                                            try {
                                                                                                if ($examen != null) {
                                                                                                    echo "<b style='font-size:15px'>" . $x . "</b>.- " . $preg['pregunta'] . "<br><br>";

                                                                                                    if (isset($preg['imagen'])) {
                                                                                                        ?>   
                                                                                                        <div class="col-lg-12 justificartexto" style="justify-content: center">
                                                                                                            <div class="col-lg-4 justificartexto">
                                                                                                                <img class="pimagen" src="<?php echo $preg['imagen']; ?>" alt="Recurso imagen">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <?php
                                                                                                    }
                                                                                                    ?>
                                                                                                    <div class="col-lg-12 justificartexto" style="padding-top: 10px">
                                                                                                        <?php
                                                                                                        switch ($preg['tipo']) {
                                                                                                            case 'UNIR':
                                                                                                                $preguntas_respuestas = Respuestas::mostrarUnir($preg['codpregunta']);
                                                                                                                ?><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" ><?php
                                                                                                                $enunciado = array();
                                                                                                                $respuesta = array();
                                                                                                                $i = 0;
                                                                                                                foreach ($preguntas_respuestas as $pregunta_respuesta) {
                                                                                                                    $enunciado[$i] = $pregunta_respuesta['enunciado'];
                                                                                                                    $respuesta[$i] = $pregunta_respuesta['respuesta'];
                                                                                                                    $i++;
                                                                                                                }
                                                                                                                foreach ($respuestas_estudiante as $re) {
                                                                                                                    if ($preg['codpregunta'] == $re['codpregunta']) {
                                                                                                                        if (Respuestas::buscarUnir($preguntas_respuestas, $re['pregunta'], $re['respuesta']) && $re['valor'] > 0) {
                                                                                                                            ?>
                                                                                                                                
                                                                                                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding-bottom: 5px;padding-top: 7px;text-align: center;">
                                                                                                                                    <?php echo $re['pregunta']; ?>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="padding-bottom: 5px;padding-top: 5px;text-align: center;">&harr;</div>
                                                                                                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding-bottom: 5px;padding-top: 5px;text-align: center;background-color: #e6ee9c;">
                                                                                                                                    <select id="pregunta" class="form-control" disabled style="background-color: #f0f4c3">
                                                                                                                                        <option><?php echo $re['respuesta']; ?></option>
                                                                                                                                    </select>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="padding-bottom: 5px;padding-top: 5px;text-align: center;background-color: #e6ee9c;">
                                                                                                                                    <p style="color: green;" style="width: 20%"><i class="fa fa-check"></i></p>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-12" style="padding-bottom: 5px;padding-top: 5px;text-align: center;"></div>

                                                                                                                                <?php                                                                                                                            
															} else {
																?>
																<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding-bottom: 5px;padding-top: 7px;text-align: center;">
                                                                                                                                    <?php echo $re['pregunta']; ?>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="padding-bottom: 5px;padding-top: 5px;text-align: center;">&harr;</div>
                                                                                                                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding-bottom: 5px;padding-top: 5px;text-align: center;background-color: #ffcdd2;">
                                                                                                                                    <select id="pregunta" class="form-control" disabled style="background-color: #ffebee">
                                                                                                                                        <option ><?php echo $re['respuesta']; ?></option>
                                                                                                                                    </select>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1" style="padding-bottom: 5px;padding-top: 5px;text-align: center;background-color: #ffcdd2;">
                                                                                                                                    <p style="color: red;" style="width: 20%"><i class="fa fa-times"></i></p>
                                                                                                                                </div>

                                                                                                                                <div class="col-lg-12" style="padding-bottom: 5px;padding-top: 5px;text-align: center;"></div>

                                                                                                                                <?php

                                                                                                                            }
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                    <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12" style="background-color: #fff9c4;margin-top: 20px">
                                                                                                                        <label style="color:#9e9e9e">La respuesta correcta es: </label>
                                                                                                                        <?php
                                                                                                                        foreach ($preguntas_respuestas as $frase) {
                                                                                                                            echo '<label style="color:#9e9e9e">' . $frase['enunciado'] . " &harr; " . $frase['respuesta'] . ',</label>';
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                    </div> 
                                                                                                                </div><?php
                                                                                                                break;
                                                                                                            case 'COMPLETAR':
                                                                                                                $respuestas_completar = Respuestas::mostrarCompletar($preg['codpregunta']);
                                                                                                                $resp = array();
                                                                                                                $note = array();
                                                                                                                ?><div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12"><?php
                                                                                                                foreach ($respuestas_completar as $frase) {
                                                                                                                    if ($frase['tipo'] == "TEXTO") {
                                                                                                                        ?>            
                                                                                                                            &nbsp;<?php echo $frase['cadena']; ?>&nbsp;
                                                                                                                            <?php
                                                                                                                        } else {
                                                                                                                            $buscar = Respuestas::buscarCompletar($respuestas_estudiante, $frase['numero'], $preg['codpregunta']);
                                                                                                                            if (isset($buscar) && $buscar != null && trim(mb_strtoupper($buscar, 'utf-8')) == trim(mb_strtoupper($frase['cadena'], 'utf-8'))) {
                                                                                                                                ?>
                                                                                                                                &nbsp;<input type='text' style="margin-top: 15px;background-color: #f0f4c3" value="<?php echo $buscar ?>" disabled/>&nbsp;,<i class="fa fa-check" style="color: green;font-size: 18px"></i>&nbsp;
                                                                                                                                <?php
                                                                                                                            } else {
                                                                                                                                ?>
                                                                                                                                &nbsp;<input type='text' style="margin-top: 15px;background-color: #ffebee" disabled/>&nbsp;,<i class="fa fa-times" style="color: red;font-size: 18px"></i>&nbsp;
                                                                                                                                <?php
                                                                                                                            }
                                                                                                                        }
                                                                                                                        $buscar = null;
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                    <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12" style="background-color: #fff9c4;margin-top: 20px">
                                                                                                                        <label style="color:#9e9e9e">La respuesta correcta es: <?php echo $respuesta ?></label>
                                                                                                                        <?php
                                                                                                                        foreach ($respuestas_completar as $frase) {
                                                                                                                            if ($frase['tipo'] == "COMPLETAR") {
                                                                                                                                ?>            
                                                                                                                                &nbsp;<?php echo '<label style="color:#9e9e9e"><u><b>' . $frase['cadena'] . '</b></u></label>'; ?>&nbsp;
                                                                                                                                <?php
                                                                                                                            }else{
                                                                                                                                ?>            
                                                                                                                                &nbsp;<?php echo '<label style="color:#9e9e9e">' . $frase['cadena'] . '</label>'; ?>&nbsp;
                                                                                                                                <?php
                                                                                                                            }
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                    </div> 
                                                                                                                </div><?php
                                                                                                                break;
                                                                                                            case 'RESPUESTA SIMPLE':
                                                                                                                $respuesta_simple = Respuestas::mostrarSimple($preg['codpregunta']);


                                                                                                                foreach ($respuesta_simple as $r) {
                                                                                                                    $respuesta = $r['respuesta'];
                                                                                                                }
                                                                                                                foreach ($respuestas_estudiante as $re) {
                                                                                                                    if ($preg['codpregunta'] == $re['codpregunta']) {
                                                                                                                        $respuesta_e = $re['respuesta'];
                                                                                                                        $valor_p = $re['valor'];
                                                                                                                    }
                                                                                                                }
                                                                                                                ?>
                                                                                                                <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12">
                                                                                                                    <label for="respuesta" id="nombre"><b>Respuesta</b></label>
                                                                                                                    <?php
                                                                                                                    if ($valor_p == 0 || !isset($valor_p)) {
                                                                                                                        ?>    
                                                                                                                        <div  style="background-color: #ffcdd2; width: 100%;height: 50%;padding: 2%;"> 
                                                                                                                            <div class="col-lg-11 col-md-10 col-sm-10 col-xs-10">
                                                                                                                                <input style="background-color: #ffebee" type='text' class='form-control name_list' value="<?php echo $respuesta_e; ?>" disabled>
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-1">
                                                                                                                                <p style="color: red;" style="width: 20%"><i class="fa fa-times"></i></p>
                                                                                                                            </div>
                                                                                                                        </div>  

                                                                                                                        <?php
                                                                                                                    } else {
                                                                                                                        if ($respuesta_e == $respuesta) {
                                                                                                                            ?>
                                                                                                                            <div  style="background-color: #e6ee9c; width: 100%;height: 10%;padding: 2% "> 
                                                                                                                                <div class="col-lg-11 col-md-10 col-sm-10 col-xs-10">
                                                                                                                                    <input style="background-color: #f0f4c3" type='text' class='form-control name_list' value="<?php echo $respuesta_e; ?>" disabled>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-1">
                                                                                                                                    <p style="color: green;" style="width: 20%"><i class="fa fa-check"></i></p>
                                                                                                                                </div>
                                                                                                                            </div>  
                                                                                                                            <?php
                                                                                                                        } else {
                                                                                                                            ?>
                                                                                                                            <div  style=" width: 100%;height: 10%;padding: 2%"> 
                                                                                                                                <div class="col-lg-12 col-md-10 col-sm-10 col-xs-10">
                                                                                                                                    <input  type='text' class='form-control name_list' value="<?php echo $respuesta_e; ?>" disabled>
                                                                                                                                </div>
                                                                                                                            </div>  
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                    <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12" style="background-color: #fff9c4;margin-top: 20px">
                                                                                                                        <label style="color:#9e9e9e">La respuesta correcta es: <?php echo $respuesta ?></label>
                                                                                                                    </div> 
                                                                                                                </div>

                                                                                                                <?php
                                                                                                                break;
                                                                                                            case 'SELECCION SIMPLE':
                                                                                                                $respuestas_simple = Respuestas::mostrarSimple($preg['codpregunta']);

                                                                                                                $opciones = array();
                                                                                                                $i = 0;
                                                                                                                $correcta = null;
                                                                                                                foreach ($respuestas_simple as $respuesta) {
                                                                                                                    $opciones[$i] = $respuesta['respuesta'];
                                                                                                                    $i++;
                                                                                                                    if ($respuesta['correcta'] == 'SI') {
                                                                                                                        $correcta = $respuesta['respuesta'];
                                                                                                                    }
                                                                                                                }
                                                                                                                foreach ($respuestas_estudiante as $re) {
                                                                                                                    if ($preg['codpregunta'] == $re['codpregunta']) {
                                                                                                                        $respuesta_e = $re['respuesta'];
                                                                                                                        $valor_p = $re['valor'];
                                                                                                                    }
                                                                                                                }
                                                                                                                ?>
                                                                                                                <div class="col-lg-12">
                                                                                                                    <div class='form-check'><?php
                                                                                                                        for ($i = 0; $i < Count($opciones); $i++) {
                                                                                                                            if ($valor_p == 0 || !isset($valor_p)) {
                                                                                                                                if ($opciones[$i] == $respuesta_e) {
                                                                                                                                    ?>    
                                                                                                                                    <div class="col-lg-11 col-md-8 col-sm-8 col-xs-9" style="background-color: #ffcdd2;">
                                                                                                                                        <input class='form-check-input' checked type='radio' value='<?php echo $opciones[$i]; ?>' disabled>
                                                                                                                                        <label class='form-check-label'><?php echo $opciones[$i]; ?></label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-1" style="background-color: #ffcdd2;">
                                                                                                                                        <p style="color: red;" ><i class="fa fa-times"></i></p>
                                                                                                                                    </div>
                                                                                                                                    <?php
                                                                                                                                } else {
                                                                                                                                    ?>
                                                                                                                                    <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                                                                                                                        <input class='form-check-input' type='radio' value='<?php echo $opciones[$i]; ?>' disabled>
                                                                                                                                        <label class='form-check-label'><?php echo $opciones[$i]; ?></label>
                                                                                                                                    </div>

                                                                                                                                    <?php
                                                                                                                                }
                                                                                                                            } else if ($respuesta_e == $correcta && $respuesta_e == $opciones[$i]) {
                                                                                                                                ?>
                                                                                                                                <div class="col-lg-11 col-md-10 col-sm-10 col-xs-10" style="background-color: #e6ee9c;">
                                                                                                                                    <input class='form-check-input' checked type='radio' value='<?php echo $opciones[$i]; ?>' disabled>
                                                                                                                                    <label class='form-check-label'><?php echo $opciones[$i]; ?></label>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-1" style="background-color: #e6ee9c;">
                                                                                                                                    <p style="color: green;"><i class="fa fa-check"></i></p>
                                                                                                                                </div>

                                                                                                                                <?php
                                                                                                                            } else {
                                                                                                                                ?>
                                                                                                                                <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                                                                                                                    <input class='form-check-input' type='radio' value='<?php echo $opciones[$i]; ?>' disabled>
                                                                                                                                    <label class='form-check-label'><?php echo $opciones[$i]; ?></label>
                                                                                                                                </div>

                                                                                                                                <?php
                                                                                                                            }
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12" style="background-color: #fff9c4;margin-top: 20px">
                                                                                                                            <label style="color:#9e9e9e">La respuesta correcta es: <?php echo $correcta ?></label>
                                                                                                                        </div> 



                                                                                                                    </div>
                                                                                                                </div><?php
                                                                                                                break;
                                                                                                            case 'SELECCION MULTIPLE':
                                                                                                                $respuestas = Respuestas::mostrarSimple($preg['codpregunta']);
                                                                                                                $rmultiples = array();
                                                                                                                $rmultiples = null;
                                                                                                                $nota = array();
                                                                                                                $nota = null;
                                                                                                                $i = 0;
                                                                                                                foreach ($respuestas_estudiante as $re) {
                                                                                                                    if ($preg['codpregunta'] == $re['codpregunta']) {
                                                                                                                        $rmultiples[$i] = $re['respuesta'];
                                                                                                                        $nota[$i] = $re['valor'];
                                                                                                                        $i++;
                                                                                                                    }
                                                                                                                }
                                                                                                                ?>
                                                                                                                <div class="col-lg-12">
                                                                                                                    <div class='form-check'><?php
                                                                                                                        foreach ($respuestas as $respuesta) {
                                                                                                                            $n = false;
                                                                                                                            $v = 0;
                                                                                                                            if ($respuesta['correcta'] == 'SI') {
                                                                                                                                for ($j = 0; $j < $i; $j++) {
                                                                                                                                    if ($respuesta['respuesta'] == $rmultiples[$j]) {
                                                                                                                                        $n = true;
                                                                                                                                        $v = $nota[$j];
                                                                                                                                        break;
                                                                                                                                    } else {
                                                                                                                                        $n = false;
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                if ($n == true && $v > 0) {
                                                                                                                                    ?>    
                                                                                                                                    <div class="col-lg-11 col-md-8 col-sm-8 col-xs-9" style="background-color: #e6ee9c;">
                                                                                                                                        <input class='form-check-input' checked type='radio' disabled>
                                                                                                                                        <label class='form-check-label'>
                                                                                                                                            <?php
                                                                                                                                            echo $respuesta['respuesta'];
                                                                                                                                            ?>
                                                                                                                                        </label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-1" style="background-color: #e6ee9c;">
                                                                                                                                        <p style="color: green;" ><i class="fa fa-check"></i></p>
                                                                                                                                    </div>
                                                                                                                                    <?php
                                                                                                                                } else {
                                                                                                                                    ?>
                                                                                                                                    <div class="col-lg-12 col-md-8 col-sm-8 col-xs-9">
                                                                                                                                        <input class='form-check-input' type='radio' disabled>
                                                                                                                                        <label class='form-check-label'>
                                                                                                                                            <?php
                                                                                                                                            echo $respuesta['respuesta'];
                                                                                                                                            ?>
                                                                                                                                        </label>
                                                                                                                                    </div>

                                                                                                                                    <?php
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                for ($j = 0; $j < $i; $j++) {
                                                                                                                                    if ($respuesta['respuesta'] == $rmultiples[$j]) {
                                                                                                                                        $n = true;
                                                                                                                                        $v = $nota[$j];
                                                                                                                                        break;
                                                                                                                                    } else {
                                                                                                                                        $n = false;
                                                                                                                                    }
                                                                                                                                }
                                                                                                                                if ($n == true) {
                                                                                                                                    ?>    
                                                                                                                                    <div class="col-lg-11 col-md-8 col-sm-8 col-xs-9" style="background-color: #ffcdd2;">
                                                                                                                                        <input class='form-check-input' checked type='radio' disabled>
                                                                                                                                        <label class='form-check-label'>
                                                                                                                                            <?php
                                                                                                                                            echo $respuesta['respuesta'];
                                                                                                                                            ?>
                                                                                                                                        </label>
                                                                                                                                    </div>
                                                                                                                                    <div class="col-lg-1" style="background-color: #ffcdd2;">
                                                                                                                                        <p style="color: red;" ><i class="fa fa-times"></i></p>
                                                                                                                                    </div>
                                                                                                                                    <?php
                                                                                                                                } else {
                                                                                                                                    ?>
                                                                                                                                    <div class="col-lg-12 col-md-8 col-sm-8 col-xs-9">
                                                                                                                                        <input class='form-check-input' type='radio' disabled>
                                                                                                                                        <label class='form-check-label'>
                                                                                                                                            <?php
                                                                                                                                            echo $respuesta['respuesta'];
                                                                                                                                            ?>
                                                                                                                                        </label>
                                                                                                                                    </div>
                                                                                                                                    <?php
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                    </div>
                                                                                                                    <br><br>

                                                                                                                    <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12" style="background-color: #fff9c4;margin-top: 20px">
                                                                                                                        <label style="color:#9e9e9e">La respuesta correcta es: </label>
                                                                                                                        <?php
                                                                                                                        foreach ($respuestas as $respuesta) {
                                                                                                                            if ($respuesta['correcta'] == 'SI') {
                                                                                                                                ?>            
                                                                                                                                &nbsp;<?php echo '<label style="color:#9e9e9e">' . $respuesta['respuesta'] . ',</label>'; ?>&nbsp;
                                                                                                                                <?php
                                                                                                                            }
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                    </div> 
                                                                                                                </div>
                                                                                                                <?php
                                                                                                                break;
                                                                                                            case 'VERDADERO FALSO':
                                                                                                                $respuestas_simple = Respuestas::mostrarSimple($preg['codpregunta']);

                                                                                                                $i = 0;
                                                                                                                $correcta = null;
                                                                                                                foreach ($respuestas_simple as $respuesta) {
                                                                                                                    $correcta = $respuesta['respuesta'];
                                                                                                                }
                                                                                                                foreach ($respuestas_estudiante as $re) {
                                                                                                                    if ($preg['codpregunta'] == $re['codpregunta']) {
                                                                                                                        $respuesta_e = $re['respuesta'];
                                                                                                                        $valor_p = $re['valor'];
                                                                                                                    }
                                                                                                                }
                                                                                                                ?>
                                                                                                                <div class="col-lg-12">
                                                                                                                    <div class='form-check'><?php
                                                                                                                        if ($valor_p == 0 || !isset($valor_p)) {
                                                                                                                            if (isset($respuesta_e)) {
                                                                                                                                ?>    
                                                                                                                                <div class="col-lg-11 col-md-8 col-sm-8 col-xs-9" style="background-color: #ffcdd2;">
                                                                                                                                    <input class='form-check-input' checked type='radio' disabled>
                                                                                                                                    <label class='form-check-label'>
                                                                                                                                        <?php
                                                                                                                                        if ($respuesta_e == 'verdadero')
                                                                                                                                            echo 'Verdadero';
                                                                                                                                        else
                                                                                                                                            echo 'Falso';
                                                                                                                                        ?>
                                                                                                                                    </label>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-1" style="background-color: #ffcdd2;">
                                                                                                                                    <p style="color: red;" ><i class="fa fa-times"></i></p>
                                                                                                                                </div>
                                                                                                                                <div class="col-lg-11 col-md-8 col-sm-8 col-xs-9">
                                                                                                                                    <input class='form-check-input' type='radio' disabled>
                                                                                                                                    <label class='form-check-label'>
                                                                                                                                        <?php
                                                                                                                                        if ($respuesta_e == 'verdadero')
                                                                                                                                            echo 'Falso';
                                                                                                                                        else
                                                                                                                                            echo 'Verdadero';
                                                                                                                                        ?>
                                                                                                                                    </label>
                                                                                                                                </div>

                                                                                                                                <?php
                                                                                                                            }else {
                                                                                                                                ?>
                                                                                                                                <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                                                                                                                    <input class='form-check-input' type='radio' disabled>
                                                                                                                                    <label class='form-check-label'>Verdadero</label><br>
                                                                                                                                    <input class='form-check-input' type='radio' disabled>
                                                                                                                                    <label class='form-check-label'>Falso</label>
                                                                                                                                </div>

                                                                                                                                <?php
                                                                                                                            }
                                                                                                                        } else if ($respuesta_e == $correcta) {
                                                                                                                            ?>
                                                                                                                            <div class="col-lg-11 col-md-10 col-sm-10 col-xs-10" style="background-color: #e6ee9c;">
                                                                                                                                <input class='form-check-input' checked type='radio' disabled>
                                                                                                                                <label class='form-check-label'><?php
                                                                                                                                    if ($respuesta_e == 'verdadero')
                                                                                                                                        echo 'Verdadero';
                                                                                                                                    else
                                                                                                                                        echo 'Falso';
                                                                                                                                    ?></label>
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-1" style="background-color: #e6ee9c;">
                                                                                                                                <p style="color: green;"><i class="fa fa-check"></i></p>
                                                                                                                            </div>
                                                                                                                            <div class="col-lg-11 col-md-8 col-sm-8 col-xs-9">
                                                                                                                                <input class='form-check-input' type='radio' disabled>
                                                                                                                                <label class='form-check-label'><?php
                                                                                                                                    if ($respuesta_e == 'verdadero')
                                                                                                                                        echo 'Falso';
                                                                                                                                    else
                                                                                                                                        echo 'Verdadero';
                                                                                                                                    ?></label>
                                                                                                                            </div>
                                                                                                                            <?php
                                                                                                                        }else {
                                                                                                                            ?>
                                                                                                                            <div class="col-lg-11 col-md-10 col-sm-10 col-xs-12">
                                                                                                                                <input class='form-check-input' type='radio' disabled>
                                                                                                                                <label class='form-check-label'>Verdadero</label><br>
                                                                                                                                <input class='form-check-input' type='radio' disabled>
                                                                                                                                <label class='form-check-label'>Falso</label>
                                                                                                                            </div>
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                        <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12" style="background-color: #fff9c4;margin-top: 20px">
                                                                                                                            <label style="color:#9e9e9e">La respuesta correcta es: <?php echo $correcta ?></label>
                                                                                                                        </div> 
                                                                                                                    </div>
                                                                                                                </div><?php
                                                                                                                break;
                                                                                                        }
                                                                                                        ?>
                                                                                                    </div>
                                                                                                    <?php
                                                                                                } else {
                                                                                                    echo 'NO TIENE ASIGNATURAS ASIGNADAS';
                                                                                                }
                                                                                            } catch (Exception $ex) {
                                                                                                echo 'ERROR ' . $ex->getMessage();
                                                                                                Conexion::cerrar_conexion();
                                                                                            }
                                                                                            ?>
                                                                                        </ul>

                                                                                    </nav>
                                                                                </blockquote>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </form>
                                            </div>
                                        </article>
                                    </section>
                                </div>
                            </div>
                        </div>
                        <!-- Button trigger modal -->

                    </main>
                </div>
                <!-- .app-layout-container -->
            </div>
            <!-- .app-layout-canvas -->
            <!-- End Apps Modal -->
            <!-- Button trigger modal -->

            <div class="app-ui-mask-modal"></div>

            <!-- AppUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock and App.js -->
            <script src="../../assets/js/core/jquery.min.js"></script>
            <script src="../../assets/js/core/bootstrap.min.js"></script>
            <script src="../../assets/js/core/jquery.slimscroll.min.js"></script>
            <script src="../../assets/js/core/jquery.scrollLock.min.js"></script>
            <script src="../../assets/js/core/jquery.placeholder.min.js"></script>
            <script src="../../assets/js/app.js"></script>
            <script src="../../assets/js/app-custom.js"></script>

            <!-- Page Plugins -->
            <script src="../../assets/js/plugins/slick/slick.min.js"></script>
            <script src="../../assets/js/plugins/chartjs/Chart.min.js"></script>
            <script src="../../assets/js/plugins/flot/jquery.flot.min.js"></script>
            <script src="../../assets/js/plugins/flot/jquery.flot.pie.min.js"></script>
            <script src="../../assets/js/plugins/flot/jquery.flot.stack.min.js"></script>
            <script src="../../assets/js/plugins/flot/jquery.flot.resize.min.js"></script>

            <!-- Page JS Code -->
            <script src="../../assets/js/pages/index.js"></script>
            <script src="countdown.js"></script>
            <script>
                                                    $(function ()
                                                    {
                                                        // Init page helpers (Slick Slider plugin)
                                                        App.initHelpers('slick');
                                                    });
            </script>

        </body>

    </html>
    <?php
} else {
    //ControlSesion::cerrar_sesion();
    header("Location:../../index.php");
}
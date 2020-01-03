<?php
require '../../app/ControlSesion.php';
require '../../app/conexion.php';
session_start();
if (ControlSesion::sesion_iniciada() && $_SESSION['tippersona'] == 'Estudiante') {
    if (isset($_COOKIE['establecido'])) {//&&$_GET['idexamen']==$_COOKIE['establecido']){
        header("Location:../Examen.php");
    }
    try {
        Conexion::abrir_conexion();
        $sql = "SELECT * FROM texamen WHERE codexamen = :codexamen";
        $sentencia = Conexion::obtener_conexion()->prepare($sql);
        $sentencia->bindParam(":codexamen", $_GET['ce'], PDO::PARAM_STR);
        $sentencia->execute();
        $quiz = $sentencia->fetchAll();
        Conexion::cerrar_conexion();
        $idexam = null;

        $fe = 0;
        $ff = 0;
        foreach ($quiz as $cod) {
            $idexam = $cod['codexamen'];
            $fe = $cod['fechaejecucion'];
            $ff = $cod['fechafin'];
        }
        date_default_timezone_set('Etc/GMT+5');
        $hoy = array();
        $hoy = date("Y-m-d H:i:00", time());

        if (Count($quiz) <= 0 || strtotime($hoy) < strtotime($fe) || strtotime($ff) <= strtotime($hoy)) {
          header("../Examen.php");
          } 
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
                            <div style="padding-left: 75px">
                                <div id="logo" class="drawer-header" >
                                    <a href="../../index.php"><img class="img-responsive" src="../../assets/img/logo/logo conduespoch.png" title="AppUI" alt="AppUI" /></a>
                                </div>    
                            </div>

                            <!-- Drawer navigation -->
                            <?php
                            $date = new DateTime($ff);
                            ?>
                            <div style="visibility: hidden"> 
                                <p id="reloj"><?php echo '"' . $date->format("M j Y H:i:s") . ' GMT-0500' . '"'; ?></p>
                            </div>

                            <!-- Drawer navigation -->
                            <nav class="drawer-main">
                                <ul class="nav nav-drawer">
                                    <li class="nav-item nav-drawer-header"> Panel de Control</li>
                                    <li class="nav-item"><a href="../Examen.php"><i class="fa fa-desktop"></i> Inicio</a></li>
                                    <li class="nav-item nav-drawer-header"> CUESTIONARIO</li>
                                    <li><a href="#"><i class="fa fa-stopwatch"></i> Tiempo restante: <label style="font-size: 20px">&nbsp;&nbsp;<b><span id="clock"></span></b></label></a></li>
                                </ul>
                            </nav>

                            <!-- End drawer navigation -->

                            <div class="drawer-footer">
                                <p class="copyright">Conduespoch &copy;</p>
                            </div>
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
                                                <form method="POST" action="guardarexamen.php" enctype="multipart/form-data" >
                                                    <div style="overflow-x:auto;">

                                                        <?php
                                                        try {
                                                            Conexion::abrir_conexion();
                                                            $sql = "SELECT * FROM tpregunta INNER JOIN texamenpregunta "
                                                                    . "ON tpregunta.codpregunta = texamenpregunta.codpregunta "
                                                                    . "WHERE codexamen = :codexamen;";
                                                            $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                                            $sentencia->bindParam(":codexamen", $_GET['ce'], PDO::PARAM_STR);
                                                            $sentencia->execute();
                                                            $examen = $sentencia->fetchAll();
                                                            Conexion::cerrar_conexion();
                                                        } catch (Exception $ex) {
                                                            Conexion::cerrar_conexion();
                                                            echo 'ERROR ' . $ex->getMessage();
                                                        }

                                                        if (isset($_GET['ce']) && isset($_GET['cm']) && $idexam == $_GET['ce']) {
                                                            if ($examen != null) {
                                                                $x = 0;
                                                                foreach ($examen as $preg) {
                                                                    $x++;
                                                                    ?>
                                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-4" style="background-color:#eceff1">
                                                                        <div class="card" style="width: 100%;">
                                                                            <div class="card-body" style="padding-top: 3px;padding-bottom: 3px;padding-left: 15px;margin-top: 20px">
                                                                                <h5 style="font-size: 12px">Pregunta <b style="font-size: 15px"><?php echo $x; ?></b></h5>
                                                                                <h5 style="font-size: 12px">Puntúa como <?php echo $preg['valor']; ?>,00</h5>
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
                                                                                                                $preguntas_respuestas = $sentencia->fetchAll();
                                                                                                                Conexion::cerrar_conexion();
                                                                                                                ?><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" ><?php
                                                                                                                $enunciado = array();
                                                                                                                $respuesta = array();
                                                                                                                $i = 0;
                                                                                                                foreach ($preguntas_respuestas as $pregunta_respuesta) {
                                                                                                                    $enunciado[$i] = $pregunta_respuesta['enunciado'];
                                                                                                                    $respuesta[$i] = $pregunta_respuesta['respuesta'];
                                                                                                                    $i++;
                                                                                                                }
                                                                                                                shuffle($enunciado);
                                                                                                                for ($i = 0; $i < Count($enunciado); $i++) {
                                                                                                                    ?>
                                                                                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding-bottom: 10px;text-align: center;">
                                                                                                                            <?php echo $enunciado[$i]; ?>
                                                                                                                            <input type="hidden" name="lista_enunciados[<?php echo $i . $preg['codpregunta']; ?>]" value="<?php echo $enunciado[$i]; ?>">
                                                                                                                        </div>
                                                                                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2" style="padding-bottom: 10px;text-align: center;">&harr;</div>
                                                                                                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5" style="padding-bottom: 10px;text-align: center;">
                                                                                                                            <select id="pregunta" name="lista_respuestas[<?php echo $i . $preg['codpregunta']; ?>]" class="form-control">
                                                                                                                                <?php
                                                                                                                                for ($j = Count($respuesta) - 1; $j >= 0; $j--) {
                                                                                                                                    ?>
                                                                                                                                    <option><?php echo $respuesta[$j]; ?></option>
                                                                                                                                    <?php
                                                                                                                                }
                                                                                                                                ?>
                                                                                                                            </select>
                                                                                                                        </div>
                                                                                                                        <div class="col-lg-12" style="padding-bottom: 10px;text-align: center;"></div>

                                                                                                                        <?php
                                                                                                                    }
                                                                                                                    ?>

                                                                                                                </div><?php
                                                                                                                break;
                                                                                                            case 'COMPLETAR':
                                                                                                                Conexion::abrir_conexion();
                                                                                                                $sql = "SELECT * FROM tpartes WHERE codpregunta = :codpregunta ORDER BY numero asc";
                                                                                                                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                                                                                                $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                                                                                                $sentencia->execute();
                                                                                                                $completar = $sentencia->fetchAll();
                                                                                                                Conexion::cerrar_conexion();
                                                                                                                ?><div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12"><?php
                                                                                                                foreach ($completar as $frase) {
                                                                                                                    if ($frase['tipo'] == "TEXTO") {
                                                                                                                        ?>            
                                                                                                                            &nbsp;<?php echo $frase['cadena']; ?>&nbsp;
                                                                                                                            <?php
                                                                                                                        } else {
                                                                                                                            ?>
                                                                                                                            &nbsp;<input type='text' name='completar[<?php echo $frase['numero'] . $preg['codpregunta']; ?>]' style="margin-top: 15px"/>&nbsp;,&nbsp;
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                    }
                                                                                                                    ?></div><?php
                                                                                                                break;
                                                                                                            case 'RESPUESTA SIMPLE':
                                                                                                                ?>
                                                                                                                <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12">
                                                                                                                    <label for="respuesta" id="nombre"><b>Respuesta</b></label>
                                                                                                                    <input type='text' name='respuesta_simple[<?php echo $preg['codpregunta']; ?>]' class='form-control name_list'/><br><br>
                                                                                                                </div>
                                                                                                                <?php
                                                                                                                break;
                                                                                                            case 'SELECCION SIMPLE':
                                                                                                                Conexion::abrir_conexion();
                                                                                                                $sql = "SELECT * FROM trespuesta INNER JOIN tpreguntarespuesta ON trespuesta.codrespuesta = tpreguntarespuesta.codrespuesta WHERE codpregunta = :codpregunta";
                                                                                                                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                                                                                                $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                                                                                                $sentencia->execute();
                                                                                                                $respuestas = $sentencia->fetchAll();
                                                                                                                Conexion::cerrar_conexion();

                                                                                                                $opciones = array();
                                                                                                                $i = 0;
                                                                                                                foreach ($respuestas as $respuesta) {
                                                                                                                    $opciones[$i] = $respuesta['respuesta'];
                                                                                                                    $i++;
                                                                                                                }
                                                                                                                shuffle($opciones);
                                                                                                                ?>
                                                                                                                <div class="col-lg-12">
                                                                                                                    <div class='form-check'><?php
                                                                                                                        for ($i = 0; $i < Count($opciones); $i++) {
                                                                                                                            ?>
                                                                                                                            <input class='form-check-input' type='radio' name='seleccion_simple[<?php echo $preg['codpregunta']; ?>]' id='seleccion_simple' value='<?php echo $opciones[$i]; ?>'>
                                                                                                                            <label class='form-check-label' for='verdadero'><?php echo $opciones[$i]; ?></label>
                                                                                                                            <br>
                                                                                                                            <?php
                                                                                                                        }
                                                                                                                        ?>
                                                                                                                    </div>
                                                                                                                </div><?php
                                                                                                                break;
                                                                                                            case 'SELECCION MULTIPLE':
                                                                                                                Conexion::abrir_conexion();
                                                                                                                $sql = "SELECT * FROM trespuesta INNER JOIN tpreguntarespuesta ON trespuesta.codrespuesta = tpreguntarespuesta.codrespuesta WHERE codpregunta = :codpregunta";
                                                                                                                $sentencia = Conexion::obtener_conexion()->prepare($sql);
                                                                                                                $sentencia->bindParam(":codpregunta", $preg['codpregunta'], PDO::PARAM_STR);
                                                                                                                $sentencia->execute();
                                                                                                                $respuestas = $sentencia->fetchAll();
                                                                                                                Conexion::cerrar_conexion();
                                                                                                                $opciones = array();
                                                                                                                $i = 0;
                                                                                                                foreach ($respuestas as $respuesta) {
                                                                                                                    $opciones[$i] = $respuesta['respuesta'];
                                                                                                                    $i++;
                                                                                                                }
                                                                                                                shuffle($opciones);
                                                                                                                ?>
                                                                                                                <div class="col-lg-12">
                                                                                                                    <?php
                                                                                                                    for ($i = 0; $i < Count($opciones); $i++) {
                                                                                                                        ?>
                                                                                                                        <input type="checkbox" name="seleccion_multiple[<?php echo $i . $preg['codpregunta']; ?>]" value="<?php echo $opciones[$i]; ?>">
                                                                                                                        <?php echo $opciones[$i]; ?>
                                                                                                                        <br>
                                                                                                                        <?php
                                                                                                                    }
                                                                                                                    ?>
                                                                                                                    <br><br>
                                                                                                                </div><?php
                                                                                                                break;
                                                                                                            case 'VERDADERO FALSO':
                                                                                                                ?>
                                                                                                                <div class='form-check'>
                                                                                                                    <input class='form-check-input' type='radio' name='verdadero_falso[<?php echo $preg['codpregunta']; ?>]' id='verdadero' value='verdadero'>
                                                                                                                    <label class='form-check-label' for='verdadero'>Verdadero</label>
                                                                                                                    <br>
                                                                                                                    <input class='form-check-input' type='radio' name='verdadero_falso[<?php echo $preg['codpregunta']; ?>]' id='falso' value='falso'>
                                                                                                                    <label class='form-check-label' for='falso'>Falso</label>
                                                                                                                </div>
                                                                                                                <?php
                                                                                                                break;
                                                                                                            default:
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
                                                        <div class="col-lg-12 my-1 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="col-lg-10 my-1 col-md-10 col-sm-10 col-xs-9">
                                                            </div>
                                                            <div class="col-lg-2 my-1 col-md-2 col-sm-2 col-xs-2">
                                                                <input type="hidden" value="<?php echo $_GET['ce']; ?>" name="codexamen">
                                                                <input type="submit" class="btn btn-danger" id="guardar" name="guardar" value="Finalizar cuestionario">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                                <!--Aqui va la condicion de revisión-->
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
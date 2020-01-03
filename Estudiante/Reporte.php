<?php
require '../app/ControlSesion.php';
session_start();
if (ControlSesion::sesion_iniciada() && $_SESSION['tippersona'] == 'Estudiante') {
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
            <link rel="stylesheet" href="../assets/js/plugins/slick/slick.min.css" />
            <link rel="stylesheet" href="../assets/js/plugins/slick/slick-theme.min.css" />

            <!-- AppUI CSS stylesheets -->
            <link rel="stylesheet" id="css-font-awesome" href="../assets/css/font-awesome.css" />
            <link rel="stylesheet" id="css-ionicons" href="../assets/css/ionicons.css" />
            <link rel="stylesheet" id="css-bootstrap" href="../assets/css/bootstrap.css" />
            <link rel="stylesheet" id="css-app" href="../assets/css/app.css" />
            <link rel="stylesheet" id="css-app-custom" href="../assets/css/app-custom.css" />
            <!-- End Stylesheets -->

            <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
            <script type="text/javascript" src="reporte.js"></script>
            <link rel="stylesheet" id="css-app" href="../assets/css/estilos.css" />



        </head>

        <body class="app-ui layout-has-drawer layout-has-fixed-header">
            <div class="app-layout-canvas">
                <div class="app-layout-container">

                    <!-- Drawer -->
                    <aside class="app-layout-drawer">

                        <!-- Drawer scroll area -->
                        <div class="app-layout-drawer-scroll">
                            <div class="col" style="width: 160px;padding-left: 60px" >
                                <a href="../Reporte.php"><img class="img-responsive" src="../assets/img/logo/logo conduespoch.png" title="AppUI" alt="AppUI" /></a>
                            </div>     


                            <!-- Drawer navigation --> 
                            <nav class="drawer-main">

                                <ul class="nav nav-drawer">
                                    <li class="nav-item ">
                                        <a href="Examen.php"><i class="ion-ios-compose-outline"></i> Evaluaciones Pendientes</a>
                                    </li>
                                    <li class="nav-item active">
                                        <a href="Reporte.php"><i class="ion-ios-browsers-outline"></i> Reportes</a>                                    
                                    </li>
                                    <li class="nav-item">
                                        <a href="https://www.ant.gob.ec/index.php/licencias/1815-banco-de-preguntas-licencias#.W3bmy7hOmHv" target="_blank"><i class="ion-document"></i> Banco de preguntas</a>                                    
                                    </li>
                                    <li class="nav-item">
                                        <a href="https://www.ant.gob.ec/ant-simulador/" target="_blank"><i class="ion-speedometer"></i> Simuladores</a>                                    
                                    </li>
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
                                        <?php echo "PERIODO: "; ?>
                                    </span>
                                    <span class="" style="text-transform: uppercase">
                                        <?php echo $_SESSION['nomperiodo']; ?>
                                    </span>

                                    <ul class="nav navbar-right navbar-toolbar hidden-sm hidden-xs">
                                        <li class="dropdown dropdown-profile">
                                            <a href="javascript:void(0)" data-toggle="dropdown">
                                                <span class="navbar-page-title" style="color: #000;text-transform: uppercase"><?php
                                                    echo $_SESSION['tippersona'] . ":";
                                                    ?></span>

                                                <span style="color:#9e9e9e ;text-transform: uppercase"><?php
                                                    echo $_SESSION['nompersona'];
                                                    echo " " . $_SESSION['apepersona'];
                                                    ?><span class="caret"></span></span>
                                                <img class="img-avatar img-avatar-48" src="../assets/img/avatars/estudent.png" alt="User profile pic" />
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li class="dropdown-header">
                                                    Opciones
                                                </li>
                                                <li>
                                                    <a href="../cerrar.php">Salir</a>
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

                                    <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-color-header" style="text-align: center;padding-top:20px">
                                                ASIGNATURAS
                                            </div>
                                            <div class="card-body">
                                                <nav class="drawer-main">
                                                    <ul class="nav nav-drawer" id="asignaturas">
							<li id="materia" style="text-align:center"> NO HAY ASIGNATURAS</li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12">
                                        <div class="card">
                                            <div class="card-color-header" id="identificador-asignatura" style="text-align: center;padding-top:20px">
                                                LISTADO DE EVALUACIONES
                                            </div>
                                            <div class="card-body">
                                                <nav class="drawer-main">
                                                    <ul class="nav nav-drawer" id="examenes">
							<li id="materia" style="text-align:center"> NO HAY EVALUACIONES</li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>        
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
            <script src="../assets/js/core/jquery.min.js"></script>
            <script src="../assets/js/core/bootstrap.min.js"></script>
            <script src="../assets/js/core/jquery.slimscroll.min.js"></script>
            <script src="../assets/js/core/jquery.scrollLock.min.js"></script>
            <script src="../assets/js/core/jquery.placeholder.min.js"></script>
            <script src="../assets/js/app.js"></script>
            <script src="../assets/js/app-custom.js"></script>

            <!-- Page Plugins -->
            <script src="../assets/js/plugins/slick/slick.min.js"></script>
            <script src="../assets/js/plugins/chartjs/Chart.min.js"></script>
            <script src="../assets/js/plugins/flot/jquery.flot.min.js"></script>
            <script src="../assets/js/plugins/flot/jquery.flot.pie.min.js"></script>
            <script src="../assets/js/plugins/flot/jquery.flot.stack.min.js"></script>
            <script src="../assets/js/plugins/flot/jquery.flot.resize.min.js"></script>

            <!-- Page JS Code -->
            <script src="../assets/js/pages/index.js"></script>
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
    header("Location:../index.php");
}
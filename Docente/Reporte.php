<?php
require '../app/ControlSesion.php';
session_start();
if (ControlSesion::sesion_iniciada() && $_SESSION['tippersona'] == 'Docente') {
    ?>
    <!DOCTYPE html>
    <html class="app-ui">

        <head>
            <!-- Meta -->
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

            <!-- Document title -->
            <title>Docente</title>

            <meta name="description" content="AppUI - Admin Dashboard Template & UI Framework" />
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
            <script type="text/javascript" src="../assets/js/vistas.js"></script>
            <script type="text/javascript" src="../assets/js/preguntas.js"></script>
            <script type="text/javascript" src="reporte.js"></script>
            <link rel="stylesheet" id="css-app" href="../assets/css/estilos.css" />
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


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
                                <a href="../index.php"><img class="img-responsive" src="../assets/img/logo/logo conduespoch.png" title="AppUI" alt="AppUI" /></a>
                            </div> 


                            <!-- Drawer navigation -->
                            <nav class="drawer-main">
                                <ul class="nav nav-drawer">
				    <li class="nav-item">
                                        <a href="Pregunta.php"><i class="ion-ios-list-outline"></i> Ingreso de Preguntas</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="Examen.php"><i class="ion-ios-compose-outline"></i> Crear Exámen</a>
                                    </li>
                                    <li class="nav-item active">
                                        <a href="Reporte.php"><i class="ion-ios-browsers-outline"></i> Reportes</a>                                    
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
							echo $_SESSION['tippersona'].":";
                                                    ?></span>

                                                <span style="color:#9e9e9e ;text-transform: uppercase"><?php
                                                    echo $_SESSION['nompersona'];
                                                    echo " " . $_SESSION['apepersona'];
                                                    ?><span class="caret"></span></span>
                                                <img class="img-avatar img-avatar-48" src="../assets/img/avatars/teacher.png" alt="User profile pic" />
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
                                    <div class="card">
                                        <div id="collapsible-panels">
                                            <div class="card-header">
                                                <a href="#" id="reporte1"><h4 class="x">EVALUACIONES POR MATERIAS</h4></a>
                                            </div>
                                            <div class="card-body" id="contenido-1">
                                                <div class="row col-lg-12" style="padding-bottom: 30px">
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <input type="hidden" id="periodo" value="<?php echo $_SESSION['codperiodo']; ?>">
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Sección</label>
                                                                <select id="seccion1" name="seccion1" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Paralelo</label>
                                                                <select id="paralelo1" name="paralelo1" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Materia</label>
                                                                <select id="materia1" name="materia1" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead style="text-align: center;background-color: #000;color: #FFF;text-transform: uppercase">
                                                        <tr>
                                                            <th scope="col">Nombre Evaluación</th>
                                                            <th scope="col">Fecha Evaluación</th>
                                                            <th scope="col">Número Estudiantes</th>
                                                            <th scope="col">Promedio Evaluación</th>
                                                            <th scope="col">Evaluación</th>
                                                            <th scope="col">Solucionario</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="container1" style="text-align: center">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div id="collapsible-panels">
                                            <div class="card-header">
                                                <a href="#"><h4 class="x">LISTADO GENERAL DE EVALUACIONES</h4></a>
                                            </div>
                                            <div class="card-body" id="contenido-2">
                                                <div class="row col-lg-12" style="padding-bottom: 30px">
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <input type="hidden" id="periodo" value="<?php echo $_SESSION['codperiodo']; ?>">
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Sección</label>
                                                                <select id="seccion2" name="seccion2" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Paralelo</label>
                                                                <select id="paralelo2" name="paralelo2" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead style="text-align: center;background-color: #000;color: #FFF;text-transform: uppercase">
                                                        <tr>
                                                            <th scope="col">Materia</th>
                                                            <th scope="col">Numero Evaluaciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="container2" style="text-align: center">
                                                    </tbody>
                                                </table>
                                                <div class="col-lg-12 descarga">
                                                    <form method="post" action="Reporte/reporte2.php" target="_blank">
                                                        <input type="hidden" id="codperiodoseccionparalelo" name="codperiodoseccionparalelo" value="">
                                                        <input class="btn btn-danger" type="submit" value="Imprimir" id="Imprimir">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div id="collapsible-panels">
                                            <div class="card-header">
                                                <a href="#"><h4 class="x">LISTADO DETALLADO DE EVALUACIONES POR MATERIA</h4></a>
                                            </div>
                                            <div class="card-body" id="contenido-3">
                                                <div class="row col-lg-12" style="padding-bottom: 30px">
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <input type="hidden" id="periodo" value="<?php echo $_SESSION['codperiodo']; ?>">
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Sección</label>
                                                                <select id="seccion3" name="seccion3" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Paralelo</label>
                                                                <select id="paralelo3" name="paralelo3" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Materia</label>
                                                                <select id="materia3" name="materia3" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead style="text-align: center;background-color: #000;color: #FFF;text-transform: uppercase">
                                                        <tr>
                                                            <th scope="col">Nombre Evaluación</th>
                                                            <th scope="col">Fecha Evaluación</th>
                                                            <th scope="col">Número Estudiantes</th>
                                                            <th scope="col">Promedio Evaluación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="container3" style="text-align: center">
                                                    </tbody>
                                                </table>
                                                <div class="col-lg-12 descarga">
                                                    <form method="post" action="Reporte/reporte3.php" target="_blank">
                                                        <input type="hidden" id="coddocentemateriareporte3" name="coddocentemateriareporte3" value="">
                                                        <input class="btn btn-danger" type="submit" value="Imprimir" id="Imprimir">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div id="collapsible-panels">
                                            <div class="card-header">
                                                <a href="#"><h4 class="x">LISTADO DE ESTUDIANTES</h4></a>
                                            </div>
                                            <div class="card-body" id="contenido-4">
                                                <div class="row col-lg-12" style="padding-bottom: 30px">
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <input type="hidden" id="periodo" value="<?php echo $_SESSION['codperiodo']; ?>">
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Sección</label>
                                                                <select id="seccion4" name="seccion4" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Paralelo</label>
                                                                <select id="paralelo4" name="paralelo4" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <form>
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Materia</label>
                                                                <select id="materia4" name="materia4" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <div class="col-sm-12">
                                                        <form>
                                                            <div style="padding-left: 10px;padding-right: 10px">
                                                                <label>Seleccionar Exámen</label>
                                                                <select id="listaexamenes" name="listaexamenes" class="form-control">
                                                                </select>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <table class="table table-bordered">
                                                    <thead style="text-align: center;background-color: #000;color: #FFF;text-transform: uppercase">
                                                        <tr>
                                                            <th scope="col">Nombre Estudiante</th>
                                                            <th scope="col">Nota Evaluación</th>
                                                            <th scope="col">PDF Evaluación</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="container4" style="text-align: center">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
                <!-- .app-layout-container -->
            </div>
            <!-- .app-layout-canvas -->
            <!-- End Apps Modal -->

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
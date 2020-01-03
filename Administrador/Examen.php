<?php
require '../app/ControlSesion.php';
session_start();
if (ControlSesion::sesion_iniciada() && $_SESSION['tippersona'] == 'Administrativo') {
    ?>
    <!DOCTYPE html>
    <html class="app-ui">

        <head>
            <!-- Meta -->
            <meta charset="UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" />
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

            <!-- Document title -->
            <title>Administrador</title>

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
            <script type="text/javascript" src="examen.js"></script>
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
			    	    <li class="nav-item active">
                                        <a href="Examen.php"><i class="ion-ios-compose-outline"></i> Crear Exámen</a>
                                    </li>
                                    <li class="nav-item">
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
                                                <img class="img-avatar img-avatar-48" src="../assets/img/avatars/admin.png" alt="User profile pic" />
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
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="card ">
                                                <div class="card-header cabecera-card">
                                                    <h4 class="x">GESTIÓN DE EXÁMENES</h4>
                                                </div>
                                                <!-- .card-header -->
                                                <div class="card-block cuerpo-card">
                                                    <div class="row col-lg-12">
                                                        <div class="col-sm-6 my-1">
                                                            <form>
                                                                <div style="padding-left: 10px;padding-right: 10px">
                                                                    <label>Seleccionar Periodo</label>
                                                                    <select id="periodo" name="periodo" class="form-control">
                                                                    </select>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-6 my-1">
                                                            <form>
                                                                <div style="padding-left: 10px;padding-right: 10px">
                                                                    <label>Seleccionar Sección</label>
                                                                    <select id="seccion" name="seccion" class="form-control">
                                                                    </select>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-6 my-1">
                                                            <form>
                                                                <div style="padding-left: 10px;padding-right: 10px">
                                                                    <label>Seleccionar Paralelo</label>
                                                                    <select id="paralelo" name="paralelo" class="form-control">
                                                                    </select>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        <div class="col-sm-6 my-1">
                                                            <form>
                                                                <div style="padding-left: 10px;padding-right: 10px">
                                                                    <label>Seleccionar Materia</label>
                                                                    <select id="materia" name="materia" class="form-control">
                                                                    </select>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <div class="row col-lg-12">
                                                        <form method="POST" id="examen" enctype="multipart/form-data" onsubmit="return validaciones();">
                                                                <input type="hidden" id="coddocentemateria" name="coddocentemateria" value="">
                                                                <input type="hidden" id="codseccion" name="codseccion" value="">
                                                                <input type="hidden" id="codparalelo" name="codparalelo" value="">
                                                                    
                                                                        <br>
                                                                        <div class="col-sm-6 my-1">
									    <div style="padding-left: 10px;padding-right: 10px">
                                                                            	<label for="nombre-examen">Tipo de Exámen</label>
                                                                            	<input type="text" class="form-control" id="nombre-examen" name="nombre-examen"  placeholder="EJ. PARCIAL, FINAL, GRADO, SUPLETORIO" required>
									    </div>
                                                                        </div>
                                                                        <div class="col-sm-6 my-1">
									    <div style="padding-left: 10px;padding-right: 10px">
        	                                                                <label for="contrasenia">Contraseña</label>
	                                                                        <input type="password" class="form-control" id="contrasenia" name="contrasenia" placeholder="Password" required>
									    </div>
                                                                        </div>
                                                                        <div class="col-sm-6 my-1">
									    <div style="padding-left: 10px;padding-right: 10px">
	                                                                        <label for="fechaejecucion">Fecha y Hora de Inicio</label>
                                                                                <input type="datetime-local" class="form-control" name="fechaejecucion" placeholder="08/05/2019" required>
									    </div>
                                                                        </div>
                                                                        <div class="col-sm-6 my-1">
									    <div style="padding-left: 10px;padding-right: 10px">
                                                                                <label for="fecha-ejecucion">Fecha y Hora de Culminación</label>
                                                                                <input type="datetime-local" class="form-control" name="fechafin" placeholder="08/05/2019" required>
									    </div>
                                                                        </div>
                                                                        <div class="col-lg-12 my-1">
                                                                            <br>
                                                                            <br>

                                                                            <label for="preguntas">Seleccionar Preguntas</label>
                                                                            <br>
                                                                            <I><b>Nota:</b> El total de preguntas que seleccione debe dar una calificación de 20 puntos</I>
                                                                            <br><br>
                                                                            <div style="overflow-x:auto;max-height: 50rem;">
                                                                                <div class="container" style="width: 100%;max-width: 1200px">
                                                                                    <div class="col-sm-12" id="task-result">
                                                                                        <div class="card-body">

                                                                                            <table class="table table-striped">
                                                                                                <thead>
                                                                                                    <tr>
                                                                                                        <th scope="col" class="th-tabla">PREGUNTA</th>
                                                                                                        <th scope="col" class="th-tabla">RESPUESTA</th>
                                                                                                        <th scope="col" class="th-tabla">IMAGEN</th>
                                                                                                        <th scope="col" class="th-tabla">TIPO</th>
                                                                                                        <th scope="col" class="th-tabla">CALIFICACIÓN</th>
                                                                                                        <th scope="col" class="th-tabla">SELECCIONAR</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody id="container">
                                                                                                </tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        
                                                                    </div>
                                                                    <div class="form-group col-lg-12">
                                                                        <div class="col-lg-12" style="display: flex; justify-content: center; align-items: center; text-align: justify;padding-top: 20px">
                                                                            <button type="submit" class="btn btn-primary btn-separacion" name="Guardar">Guardar</button>
                                                                            <button type="reset" class="btn btn-danger btn-separacion">Anular</button>
                                                                        </div>
                                                                    </div>
                                                            </form>
                                                    </div>
                                                </div>
                                                <!-- .card-block -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="card ">
                                                <div class="card-header cabecera-card">
                                                    <h4 class="x">LISTADO DE EX&Aacute;MENES</h4>
                                                </div>
                                                <!-- .card-header -->
                                                <div class="card-block cuerpo-card">
                                                    <div class="row">
                                                        <div class="col-sm-12" id="task-exam">
                                                            <div class="card-body">

                                                                <table class="table table-striped">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col" class="th-tabla">DESCRIPCION</th>
                                                                            <th scope="col" class="th-tabla">FECHA INICIO</th>
                                                                            <th scope="col" class="th-tabla">FECHA FIN</th>
                                                                            <th scope="col" class="th-tabla">NÚMERO PREGUNTAS</th>
                                                                            <th scope="col" class="th-tabla">CALIFICACIÓN</th>
                                                                            <th scope="col" class="th-tabla">ESTADO</th>
                                                                            <th scope="col" class="th-tabla">FECHA CREACIÓN</th>
                                                                            <th scope="col" class="th-tabla"></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id="examenes">
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- .card-block -->
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
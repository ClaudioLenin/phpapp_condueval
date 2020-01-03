<?php
include 'app/ControlSesion.php';
include 'app/conexion.php';
if (!ControlSesion::sesion_iniciada()) {
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8"/>        
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Evaluaciones Digitales</title>

        <!--estilos-->
        <link href="assets/css/estilos.css" rel="stylesheet">
        <script type="text/javascript" src="assets/js/login.js"></script>
        <!--bootstrap-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!--iconos-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lora|Roboto+Mono" rel="stylesheet">
        <link rel="icon" href="http://conduespoch.com/sites/default/files/cLogoMini.png" />

    </head>
    <body>
        <nav class="navbar navbar-light " style="background-color: #dcedc8;">
            <div class="col-lg-6 d-flex justify-content-start" style="background-color: #dcedc8;">
                <a class="navbar-brand" href="http://conduespoch.com/">
                    <img src="assets/img/logo/conduespoch_sinletras.png" width="30" height="30">
                    ESCUELA DE CONDUCCION PROFESIONAL CONDUESPOCH E.P.
                </a>    
            </div>
            <div class="col-lg-6 d-flex justify-content-end" style="background-color: #dcedc8;">
                <a class="navbar-brand" href="#">
                    EVALUACIONES DIGITALES
                </a>
            </div>
        </nav>

        <section class="section" style="padding: 10px 20px 0px 20px">
            <article class="article" >

                <div class="jumbotron">
                    <div class="row" style="display: flex; justify-content: center; align-items: center; text-align: justify">
                        <div class="col-lg-6 col-sm-10 col-xs-10">
                            <div class="card">
                                <div class="card-body" style="padding: 50px 50px 50px 50px">
                                    <h5 class="card-title">Iniciar Sesi&oacute;n</h5>
                                    <br>
                                        <form id="loginform" class="form-horizontal" role="form" action="login.php" method="post">
                                            <p id="user">Usuario</p>
                                            <div style="margin-bottom: 25px" class="input-group flex-nowrap">

                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="addon-wrapping"><i class="fas fa-user-alt" aria-hidden="true"></i></span>
                                                </div>

                                                <input type="text" class="form-control" placeholder="C&eacute;dula" aria-label="CÈdula" aria-describedby="addon-wrapping" name="cedula" id="cedula" required autofocus >
                                            </div>
                                            <p id="pass">Contrase&ntilde;a</p>
                                            <div style="margin-bottom: 25px" class="input-group flex-nowrap">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="addon-wrapping"><i class="fas fa-lock"></i></span>

                                                </div>
                                                <input type="password" class="form-control" placeholder="Contrase&ntilde;a" aria-label="ContraseÒa" aria-describedby="addon-wrapping" id="contrasenia" name="contrasenia" required autofocus>
                                            </div>
                                            <br>
                                            <div style="margin-top:10px" class="form-group">
                                                <div class="col-sm-12 controls">
                                                    <input id="btn-login" type="submit" class="btn btn-success" name="enviar" value="Iniciar Sesi&oacute;n" onclick="validaciones()">
                                                </div>
                                            </div>
                                        </form>
                                 </div>
                            </div>
                        </div>

                    </div>

                </div>

            </article>
        </section>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>
<?php
}else{
$tippersona = $_SESSION['tippersona'];
?>
<!DOCTYPE html>
<html class="app-ui">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Evaluaciones Digitales</title>

        <!--estilos-->
        <link href="assets/css/estilos.css" rel="stylesheet">
        <script type="text/javascript" src="assets/js/login.js"></script>
        <!--bootstrap-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <!--iconos-->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Lora|Roboto+Mono" rel="stylesheet">
        <link rel="icon" href="http://conduespoch.com/sites/default/files/cLogoMini.png" />

    </head>
    <body>
        <nav class="navbar navbar-light " style="background-color: #dcedc8;">
            <div class="col-lg-6 d-flex justify-content-start" style="background-color: #dcedc8;">
                <a class="navbar-brand" href="http://conduespoch.com/">
                    <img src="assets/img/logo/conduespoch_sinletras.png" width="30" height="30">
                    ESCUELA DE CONDUCCION PROFESIONAL CONDUESPOCH E.P.
                </a>    
            </div>
            <div class="col-lg-6 d-flex justify-content-end" style="background-color: #dcedc8;">
                <a class="navbar-brand" href="#">
                    EVALUACIONES DIGITALES
                </a>
            </div>
        </nav>

        <section class="section" style="padding: 10px 20px 0px 20px">
            <article class="article" >

                <div class="jumbotron">
                    <div class="row" style="display: flex; justify-content: center; align-items: center; text-align: justify">
                        <div class="col-lg-6 col-sm-10 col-xs-10">
                            <div class="card">
                                <div class="card-body" style="padding: 50px 50px 50px 50px">
                                    <h5 class="card-title">Iniciar Sesi&oacute;n</h5>
                                    <br>
                                        <form id="loginform" class="form-horizontal" role="form" action="cerrar.php" method="post">
                                            <p>Usted se ha autentificado como </p><?php
                                            echo $_SESSION['nompersona'];
                                            echo " " . $_SESSION['apepersona'];
                                            ?>
                                            <div style="margin-top:10px" class="form-group">
                                                <div class="col-sm-12 controls">
                                                    <input id="btn-login" type="submit" class="btn btn-success" name="enviar" value="Cerrar Sesi√≥n">
                                                    <?php
                                                    if ($tippersona == 'Administrativo') {
                                                        ?><a class="seguir" href="Administrador/Examen.php" style="color:#FFFFFF;background-color: #ff5722">Ir a Administrar</a><?php
                                                    } else if ($tippersona == 'Estudiante') {
                                                        ?><a href="Estudiante/Examen.php" style="color:#FFFFFF;background-color: #ff5722">Ir a Ex√°menes</a><?php
                                                    } else if ($tippersona == 'Docente') {
                                                        ?><a href="Docente/Examen.php" style="color:#FFFFFF;background-color: #ff5722">Ir a Cursos</a><?php
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </form>
                                 </div>
                            </div>
                        </div>

                    </div>

                </div>

            </article>
        </section>
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>
<?php
}
?>
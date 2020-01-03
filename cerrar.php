<?php
include_once 'app/ControlSesion.php';
ControlSesion::cerrar_sesion();
header('Location:index.php');

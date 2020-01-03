<?php
class ControlSesion{
    public static function iniciar_sesion($codpersona,$nompersona,$apepersona,$cedpersona,$tippersona,$nomperiodo,$codperiodo){
        if(session_id() == ''){
            session_start();
        }
        $_SESSION['codpersona']=$codpersona;
        $_SESSION['nompersona']=$nompersona;
        $_SESSION['apepersona']=$apepersona;
        $_SESSION['cedpersona']=$cedpersona;
        $_SESSION['tippersona']=$tippersona;
        $_SESSION['nomperiodo']=$nomperiodo;
        $_SESSION['codperiodo']=$codperiodo;
    }
    public static function cerrar_sesion(){
        if(session_id() == ''){
            session_start();
        }
        if(isset($_SESSION['codpersona'])){
            unset($_SESSION['codpersona']);
        }
        if(isset($_SESSION['nompersona'])){
            unset($_SESSION['nompersona']);
        }
        if(isset($_SESSION['apepersona'])){
            unset($_SESSION['apepersona']);
        }
        if(isset($_SESSION['cedpersona'])){
            unset($_SESSION['cedpersona']);
        }
        if(isset($_SESSION['tippersona'])){
            unset($_SESSION['tippersona']);
        }
        if(isset($_SESSION['codperiodo'])){
            unset($_SESSION['codperiodo']);
        }
        if(isset($_SESSION['nomperiodo'])){
            unset($_SESSION['nomperiodo']);
        }
        session_destroy();
    }
    public static function sesion_iniciada(){
        if(session_id() == ''){
            session_start();
        }
        if(isset($_SESSION['codpersona'],$_SESSION['nompersona']) && isset($_SESSION['apepersona'])&& isset($_SESSION['cedpersona'])&& isset($_SESSION['tippersona'])&&isset($_SESSION['codperiodo'])&&isset($_SESSION['nomperiodo'])){
            return true;
        }else{
            return false;
        }
    }
}
?>

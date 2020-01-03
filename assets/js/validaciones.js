function validaciones(){
    var cedula, contrasenia;
    cedula = document.getElementById("cedula").value;
    contrasenia = document.getElementById("contrasenia").value;
    if(cedula==""){
        document.getElementById('user').style.color = "red";
        document.getElementById('user').innerHTML = "Ingrese Usuario";
    }
    if(contrasenia==""){
        document.getElementById('pass').style.color = "red";
        document.getElementById('pass').innerHTML = "Ingrese Contraseña";
        
    }
}


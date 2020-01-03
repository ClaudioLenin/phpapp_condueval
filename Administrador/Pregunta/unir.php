<?php

$cod = $_POST['coddocentemateria'];
echo $cod;
$enun = $_POST['enunciado'];
echo $enun;
$preguntas = json_decode($_POST['preguntas'],true); 
for($i=0;$i<Count($preguntas);$i++){
    echo $preguntas[$i]["value"]; 
}

print_r ($preguntas);
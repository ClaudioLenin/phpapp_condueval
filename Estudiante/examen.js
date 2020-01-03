$(document).ready(function () {
    cargarAsignaturas();
    function cargarAsignaturas() {
        $.ajax({
            url: 'Examen/asignaturas.php',
            type: 'POST',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '';
                tasks.forEach(task => {
                    template += `<li  coddocentemateria='${task.coddocentemateria}' nommateria='${task.nommateria}'><a href="#" id="materia"><i class="fa fa-bookmark"></i>${task.nommateria}</a></li>`;
                })
                $('#asignaturas').html(template);
                $('#materia').click();
            }
        });
    }

    $(document).on('click', '#materia', function () {
        let element = $(this)[0].parentElement;
        let coddocentemateria = $(element).attr('coddocentemateria');
        let nommateria = $(element).attr('nommateria');
        $.ajax({
            url: 'Examen/examenesHabilitados.php',
            type: 'POST',
            data: {coddocentemateria},
            success: function (response) {
                //console.log(response);
                let tasks = JSON.parse(response);
                let template = `
                                        <div class="col-lg-6 renglon renglon_centrado encabezado_examen">
                                            <b>DESCRIPCIÓN</b>
                                        </div>
                                        <div class="col-lg-6 renglon renglon_centrado encabezado_examen">
                                            <b>DISPONIBILIDAD</b>
                                        </div>
                                    `;
                tasks.forEach(task => {
                        template += `
                                        <div class="col-lg-6 renglon">
                                            <li coddocentemateria='${task.coddocentemateria}' codexamen='${task.codexamen}'><a href="#" id="test" class="seleccionar_test" data-toggle="modal" data-target="#exampleModalCenter">${task.descripcion}</a></li>
                                        </div>
                                        <div class="col-lg-3 renglon renglon_centrado">
                                            <b>Desde:</b>&nbsp;&nbsp;${task.fechaejecucion}
                                        </div>
                                        <div class="col-lg-3 renglon renglon_centrado">
                                            <b>Hasta: </b>${task.fechafin}
                                        </div>
                                    `;
                })
                $('#examenes').html(template);
                $('#identificador-asignatura').html(nommateria);
            }
        });
    });
    $(document).on('click', '#test', function () {
        let element = $(this)[0].parentElement;
        let coddocentemateria = $(element).attr('coddocentemateria');
        let codexamen = $(element).attr('codexamen');
        actualizarExamen(codexamen);
        document.getElementById('coddocentemateria').value = coddocentemateria;
        document.getElementById('codexamen').value = codexamen;
        
        /*alert(coddocentemateria);
         alert(codexamen);*/
    });
    
    //ACTUALIZAR EXAMEN
    function actualizarExamen(codexamen){
        $.ajax({
            url: 'Examen/actualizarExamen.php',
            type: 'POST',
            data: {codexamen},
            success: function (response) {
                console.log(response);
            }
        });
    }

    $("#enviarexamen").click(function (event) {
        event.preventDefault();
        let coddocentemateria = document.getElementById('coddocentemateria').value;
        let codexamen = document.getElementById('codexamen').value;
        let contrasenia = document.getElementById('contrasenia').value;
        $.ajax({
            url: 'Examen/contrasenia.php',
            type: 'POST',
            data: {coddocentemateria, codexamen, contrasenia},
            success: function (response) {
                console.log(response);
                if (response == 'volver') {
                    window.location = "Examen.php";
                } else {
                    window.location = "Examen/examen.php?ce="+codexamen+"&cm="+coddocentemateria;
                    //document.getElementById("enviarexamen").click();
                }

            }
        });
    })

});
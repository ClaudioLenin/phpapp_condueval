$(document).ready(function () {
    cargarAsignaturas();
    function cargarAsignaturas() {
        $.ajax({
            url: 'Examen/asignaturas.php',
            type: 'POST',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '';
                //var i = 0;
                tasks.forEach(task => {
                    /*i = i + 1;
                    if (i == 1) {
                        template += `<li  coddocentemateria='${task.coddocentemateria}' nommateria='${task.nommateria}'><a href="#" id="materia1"><i class="fa fa-bookmark"></i>${task.nommateria}</a></li>`;
                    } else {*/
                        template += `<li  coddocentemateria='${task.coddocentemateria}' nommateria='${task.nommateria}'><a href="#" id="materia"><i class="fa fa-bookmark"></i>${task.nommateria}</a></li>`;
                    //}
                })
                $('#asignaturas').html(template);
                $("#materia").click();
            }
        });
    }

    $(document).on('click', '#materia', function () {
        let element = $(this)[0].parentElement;
        let coddocentemateria = $(element).attr('coddocentemateria');
        let nommateria = $(element).attr('nommateria');
        //alert(id);
        $.ajax({
            url: 'Examen/examenesDeshabilitados.php',
            type: 'POST',
            data: {coddocentemateria},
            success: function (response) {
                //console.log(response);
                let tasks = JSON.parse(response);
                let template = `
                                        <div class="col-lg-12 renglon renglon_centrado encabezado_examen">
                                            <b>REPORTES</b>
                                        </div>
                                    `;
                tasks.forEach(task => {
                        template += `
                                        <div class="col-lg-12 renglon">
                                            <li coddocentemateria='${task.coddocentemateria}' codexamen='${task.codexamen}'><a href="#" id="test" class="seleccionar_test">${task.descripcion}</a></li>
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
        window.location = "Examen/mostrarReporte.php?ce=" + codexamen + "&cm=" + coddocentemateria;
        
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
});
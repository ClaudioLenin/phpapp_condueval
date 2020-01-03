$(document).ready(function () {
    $('#contenido-1').hide();
    $('#contenido-2').hide();
    $('#contenido-3').hide();
    $('#contenido-4').hide();
    $('#collapsible-panels a').click(function (e) {
        $(this).parent().next('#collapsible-panels div').slideToggle('fast');
        $(this).parent().toggleClass('active');
        //e.preventDefault();
    });

    //CARGAR SECCION
    cargarSeccion1();
    function cargarSeccion1() {
        $.ajax({
            url: 'Pregunta/seccion.php',
            type: 'POST',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Sección--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccion}'>${task.nomseccion}</option>`;
                })
                $('#seccion1').html(template);
            }
        });
    }
    cargarSeccion2();
    function cargarSeccion2() {
        $.ajax({
            url: 'Pregunta/seccion.php',
            type: 'POST',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Sección--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccion}'>${task.nomseccion}</option>`;
                })
                $('#seccion2').html(template);
            }
        });
    }
    cargarSeccion3();
    function cargarSeccion3() {
        $.ajax({
            url: 'Pregunta/seccion.php',
            type: 'POST',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Sección--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccion}'>${task.nomseccion}</option>`;
                })
                $('#seccion3').html(template);
            }
        });
    }
    cargarSeccion4();
    function cargarSeccion4() {
        $.ajax({
            url: 'Pregunta/seccion.php',
            type: 'POST',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Sección--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccion}'>${task.nomseccion}</option>`;
                })
                $('#seccion4').html(template);
            }
        });
    }
    //CARGAR PARALELO
    $('#seccion1').change(function () {
        var codperiodoseccion = $("#seccion1 option:selected").val();
        $.ajax({
            url: 'Pregunta/paralelo.php',
            type: 'POST',
            data: {codperiodoseccion},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Paralelo--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccionparalelo}'>${task.codparalelo}</option>`;
                })
                $('#paralelo1').html(template);
            }
        });

    });
    $('#seccion2').change(function () {
        var codperiodoseccion = $("#seccion2 option:selected").val();
        $.ajax({
            url: 'Pregunta/paralelo.php',
            type: 'POST',
            data: {codperiodoseccion},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Paralelo--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccionparalelo}'>${task.codparalelo}</option>`;
                })
                $('#paralelo2').html(template);
            }
        });

    });
    $('#seccion3').change(function () {
        var codperiodoseccion = $("#seccion3 option:selected").val();
        $.ajax({
            url: 'Pregunta/paralelo.php',
            type: 'POST',
            data: {codperiodoseccion},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Paralelo--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccionparalelo}'>${task.codparalelo}</option>`;
                })
                $('#paralelo3').html(template);
            }
        });

    });
    $('#seccion4').change(function () {
        var codperiodoseccion = $("#seccion4 option:selected").val();
        $.ajax({
            url: 'Pregunta/paralelo.php',
            type: 'POST',
            data: {codperiodoseccion},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Paralelo--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccionparalelo}'>${task.codparalelo}</option>`;
                })
                $('#paralelo4').html(template);
            }
        });

    });
    //CARGAR MATERIA
    $('#paralelo1').change(function () {
        var codperiodoseccionparalelo = $("#paralelo1 option:selected").val();
        $.ajax({
            url: 'Pregunta/materia.php',
            type: 'POST',
            data: {codperiodoseccionparalelo},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Materia--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.coddocentemateria}'>${task.nommateria}</option>`;
                })
                $('#materia1').html(template);
            }
        });
    });
    $('#paralelo3').change(function () {
        var codperiodoseccionparalelo = $("#paralelo3 option:selected").val();
        $.ajax({
            url: 'Pregunta/materia.php',
            type: 'POST',
            data: {codperiodoseccionparalelo},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Materia--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.coddocentemateria}'>${task.nommateria}</option>`;
                })
                $('#materia3').html(template);
            }
        });
    });
    $('#paralelo4').change(function () {
        var codperiodoseccionparalelo = $("#paralelo4 option:selected").val();
        $.ajax({
            url: 'Pregunta/materia.php',
            type: 'POST',
            data: {codperiodoseccionparalelo},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Materia--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.coddocentemateria}'>${task.nommateria}</option>`;
                })
                $('#materia4').html(template);
            }
        });
    });

    //MANEJO DE REPORTES
    $('#materia1').change(function () {
        var coddocentemateria = $("#materia1 option:selected").val();

        mostrarReporte1();
    });
    $('#paralelo2').change(function () {
        var codperiodoseccionparalelo = $("#paralelo2 option:selected").val();
        document.getElementById("codperiodoseccionparalelo").value = codperiodoseccionparalelo;
        mostrarReporte2();
    });
    $('#materia3').change(function () {
        var coddocentemateriareporte3 = $("#materia3 option:selected").val();

        document.getElementById("coddocentemateriareporte3").value = coddocentemateriareporte3;
        mostrarReporte3();
    });
    $('#materia4').change(function () {
        var coddocentemateria = $("#materia4 option:selected").val();
        $.ajax({
            url: 'Examen/mostrarExamen.php',
            type: 'POST',
            data: {coddocentemateria},
            success: function (response) {
                //console.log(response);
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Exámen--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codexamen}'>${task.descripcion}</option>`;
                })
                $('#listaexamenes').html(template);
            }
        });
    });

    //CARGAR EXAMENES
    $('#listaexamenes').change(function () {
        var codexamen = $("#listaexamenes option:selected").val();

        mostrarReporte4();
    });


    function mostrarReporte1() {
        var coddocentemateria = $("#materia1 option:selected").val();
        $.ajax({
            url: 'Reporte/mostrarReporte1.php',
            type: 'POST',
            data: {coddocentemateria},
            success: function (response) {
                let tasks = JSON.parse(response);
                if (tasks != '') {
                    let template = '';
                    tasks.forEach(task => {
                        template += `<tr ide='${task.codexamen}'>
                                    <td>${task.descripcion}</td>
                                    <td>${task.fechaejecucion}</td>
                                    <td>${task.cantidad} de ${task.alumnos}</td>
                                    <td>${task.promedio}</td>
                                    <td>
                                        <div class="col-lg-12 descarga" style="text-align: center;padding-bottom: 0px">
                                            <form method="post" action="Reporte/reporte1.php" target="_blank">
                                                <input type="hidden" id="codexamen" name="codexamen" value="${task.codexamen}">
                                                <input type="hidden" id="codexamen" name="coddocentemateria" value="${task.coddocentemateria}">
                                                <input class="btn btn-danger" type="submit" value="Evaluación" id="Evaluacion" name="Evaluacion">
                                            </form>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="col-lg-12 descarga" style="text-align: center;padding-bottom: 0px">
                                            <form method="post" action="Reporte/reporte11.php" target="_blank">
                                                <input type="hidden" id="codexamen" name="codexamen" value="${task.codexamen}">
                                                <input type="hidden" id="coddocentemateria" name="coddocentemateria" value="${task.coddocentemateria}">
                                                <input class="btn btn-danger" type="submit" value="Solucionario" id="Solucionario" name="Solucionario">
                                            </form>
                                        </div>
                                    </td>
                                </tr>`;
                    });
                    $('#container1').html(template);
                } else {

                    $('#container1').html('<tr><td colspan="6">NO HAY EVALUACIONES</td></tr>');
                }
            }

        });
    }
    ;
    function mostrarReporte2() {
        var codperiodoseccionparalelo = $("#paralelo2 option:selected").val();
        $.ajax({
            url: 'Reporte/mostrarReporte2.php',
            type: 'POST',
            data: {codperiodoseccionparalelo},
            success: function (response) {
                //console.log(response);
                let tasks = JSON.parse(response);
                if (tasks != '') {
                    let template = '';
                    tasks.forEach(task => {
                        template += `<tr>
                                    <td>${task.nommateria}</td>
                                    <td>${task.examenes}</td> 
                                </tr>`;
                    });
                    $('#container2').html(template);
                } else {

                    $('#container2').html('<tr><td colspan="4">NO HAY MATERIAS</td></tr>');
                }
            }

        });
    }
    ;
    function mostrarReporte3() {
        var coddocentemateria = $("#materia3 option:selected").val();
        $.ajax({
            url: 'Reporte/mostrarReporte1.php',
            type: 'POST',
            data: {coddocentemateria},
            success: function (response) {
                //console.log(response);
                let tasks = JSON.parse(response);
                if (tasks != '') {
                    let template = '';
                    tasks.forEach(task => {
                        template += `<tr ide='${task.codexamen}'>
                                    <td>${task.descripcion}</td>
                                    <td>${task.fechaejecucion}</td>
                                    <td>${task.cantidad} de ${task.alumnos}</td>
                                    <td>${task.promedio}</td>
                                </tr>`;
                    });
                    $('#container3').html(template);
                } else {

                    $('#container3').html('<tr><td colspan="4">NO HAY EVALUACIONES</td></tr>');
                }
            }

        });
    }
    ;
    function mostrarReporte4() {
        var codexamen = $("#listaexamenes option:selected").val();
        $.ajax({
            url: 'Reporte/mostrarReporte4.php',
            type: 'POST',
            data: {codexamen},
            success: function (response) {
                //console.log(response);
                let tasks = JSON.parse(response);
                if (tasks != '') {
                    let template = '';
                    tasks.forEach(task => {
                        template += `<tr>
                                    <td>${task.nompersona} ${task.apepersona}</td>
                                    <td>${task.nota}</td> 
                                    <td>
                                    <div class="col-lg-12 descarga" style="text-align: center;padding-bottom: 0px">
                                            <form method="post" action="Reporte/reporte4.php" target="_blank">
                                                <input type="hidden" id="codme" name="codme" value="${task.codme}">
                                                <input type="hidden" id="codestudiante" name="codestudiante" value="${task.codestudiante}">
                                                <input type="hidden" id="coddocentemateria" name="coddocentemateria" value="${task.coddocentemateria}">
                                                <input class="btn btn-danger" type="submit" value="Evaluación" id="Evaluacion" name="Evaluacion">
                                            </form>
                                        </div>
                                    </td>
                                </tr>`;
                    });
                    $('#container4').html(template);
                } else {

                    $('#container4').html('<tr><td colspan="4">NO HAY EVALUACIONES</td></tr>');
                }
            }

        });
    }
    ;
});

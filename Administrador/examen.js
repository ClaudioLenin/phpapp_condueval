$(document).ready(function () {
    //CARGAR SECCION
    cargarPeriodo();
    function cargarPeriodo() {
        console.log("Estoy en periodo");
        $.ajax({
            url: 'Pregunta/periodo.php',
            type: 'POST',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Periodo--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodo}'>${task.nomperiodo}</option>`;
                })
                $('#periodo').html(template);
            }
        });
    }
    //CARGAR SECCION
    $('#periodo').change(function () {
        var codperiodo = $("#periodo option:selected").val();
        console.log(codperiodo);
        $.ajax({
            url: 'Pregunta/seccion.php',
            type: 'POST',
            data: {codperiodo},
            success: function (response) {
                console.log(response);
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Sección--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccion}'>${task.nomseccion}</option>`;
                })
                $('#seccion').html(template);
            }
        });

    });
    //CARGAR PARALELO
    $('#seccion').change(function () {
        var codperiodoseccion = $("#seccion option:selected").val();
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
                $('#paralelo').html(template);
            }
        });

    });
    //CARGAR MATERIA
    $('#paralelo').change(function () {
        var codperiodoseccionparalelo = $("#paralelo option:selected").val();
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
                $('#materia').html(template);
            }
        });
    });
    $('#materia').change(function () {
        var codseccion = $("#seccion option:selected").val();
        var codparalelo = $("#paralelo option:selected").val();
        var coddocentemateria = $("#materia option:selected").val();
        mostrarPreguntas();
        mostrarExamenes();
        document.getElementById("codseccion").value = codseccion;
        document.getElementById("codparalelo").value = codparalelo;
        document.getElementById("coddocentemateria").value = coddocentemateria;
        console.log(codseccion);
        console.log(codparalelo);
        console.log(coddocentemateria);
    });

    $('#task-result').hide();
    //MOSTRAR DE PREGUNTAS
    function mostrarPreguntas() {
        let coddocentemateria = $("#materia option:selected").val();
        $.ajax({
            url: 'Pregunta/mostrarPregunta.php',
            type: 'POST',
            data: {coddocentemateria},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '';
                tasks.forEach(task => {
                    template +=
                            `<tr>
                                    <td class='color-pregunta'>${task.pregunta}</td>
                                    <td id='${task.codpregunta}'>respuesta</td>
                                    <td><img class='pimagen' src='${task.imagen}' alt="Recurso imagen"></td>
                                    <td>${task.tipo}</td>
                                    <td>${task.valor},00</td>
                                    <td style='text-align:center'>
                                        <input class="estilo-check" type="checkbox" id="preguntas[]" name="preguntas[]" value='${task.codpregunta}'>
                                    </td>
                                </tr>`;

                });
                $('#container').html(template);
                tasks.forEach(task => {
                    var codp = task['codpregunta'];
                    var tipo = task['tipo'];
                    $.ajax({
                        url: 'Pregunta/mostrarRespuesta.php',
                        type: 'POST',
                        data: {codp, tipo},
                        success: function (response) {
                            document.getElementById(task['codpregunta']).innerHTML = response;
                        }
                    });

                });
                $('#task-result').show();
            }
        });
    }
    //GUARDAR EXAMEN
    $('#examen').submit(function (e) {
        e.preventDefault();
        var datos = new FormData($('#examen')[0]);
        $.ajax({
            url: 'Examen/guardarExamen.php',
            type: 'POST',
            data: datos,
            //data: tipo,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response == 'NOT') {
                    alert("No se ha creado el exámen porque el puntaje total de preguntas debe ser 20 puntos!!");
                } else if (response == 'MATERIA') {
                    alert("No se ha seleccionado la materia o las preguntas");
                } else {
                    alert("Exámen creado exitosamente!!");
                    $('#examen').trigger('reset');
                    mostrarExamenes();
                }
            }

        });

    });

    //MOSTRAR EXAMENES
    function mostrarExamenes() {
        let coddocentemateria = $("#materia option:selected").val();
        $.ajax({
            url: 'Examen/mostrarExamen.php',
            type: 'POST',
            data: {coddocentemateria},
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '';
                tasks.forEach(task => {
                    template +=
                            `<tr ide='${task.codexamen}'>
                                    <td class='color-pregunta'>${task.descripcion}</td>
                                    <td>${task.fechaejecucion}</td>
                                    <td>${task.fechafin}</td>
                                    <td>${task.numpreguntas}</td>
                                    <td>${task.valoracion},00</td>`;
                    if (task['estado'] == 'HABILITADO') {
                        template += `<td style='color:green'><b>${task.estado}</b></td>`;
                    } else {
                        template += `<td style='color:red'><b>${task.estado}</b></td>`;
                    }

                    template += `<td>${task.fechacreacion},00</td>
                                    <td><button class='btn btn-danger' id="eliminar">Eliminar</button></td>
                                </tr>`;

                });
                $('#examenes').html(template);
                $('#task-exam').show();
            }
        });
    }

    //BUSCAR EXAMENES
    $('#search').keyup(function (e) {
        if ($('#search').val()) {
            let search = $('#search').val();
            let coddocentemateria = $("#materia option:selected").val();
            $.ajax({
                url: 'Examen/buscarExamen.php',
                type: 'POST',
                data: {search, coddocentemateria},
                success: function (response) {
                    let tasks = JSON.parse(response);
                    let template = '';
                    tasks.forEach(task => {
                        template +=
                                `<tr ide='${task.codexamen}'>
                                    <td class='color-pregunta'>${task.descripcion}</td>
                                    <td>${task.fechaejecucion}</td>
                                    <td>${task.fechafin}</td>
                                    <td>${task.numpreguntas}</td>
                                    <td>${task.valoracion},00</td>`;
                        if (task['estado'] == 'HABILITADO') {
                            template += `<td style='color:green'><b>${task.estado}</b></td>`;
                        } else {
                            template += `<td style='color:red'><b>${task.estado}</b></td>`;
                        }

                        template += `<td>${task.fechacreacion},00</td>
                                    <td><button class='btn btn-danger' id="eliminar">Eliminar</button></td>
                                </tr>`;

                    });
                    $('#examenes').html(template);
                    $('#task-exam').show();
                }
            });
        } else {
            mostrarExamenes();
        }
    })
    //ELIMINAR EXAMEN
    $(document).on('click', '#eliminar', function () {
        if (confirm('Estas seguro(a) de eliminar el registro?')) {
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('ide');
            //console.log(id);
            $.post('Examen/eliminarExamen.php', {id}, function () {
                mostrarExamenes();
            }); 
        }
    })
});
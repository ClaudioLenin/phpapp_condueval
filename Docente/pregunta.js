$(document).ready(function () {
    //CARGAR SECCION
    cargarSeccion()
    function cargarSeccion() {
        $.ajax({
            url: 'Pregunta/seccion.php',
            type: 'POST',
            success: function (response) {
                let tasks = JSON.parse(response);
                let template = '<option selected="true" disabled="disabled">--Sección--</option>';
                tasks.forEach(task => {
                    template += `<option value='${task.codperiodoseccion}'>${task.nomseccion}</option>`;
                })
                $('#seccion').html(template);
            }
        });
    }
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
        var coddocentemateria = $("#materia option:selected").val();
        //alert(coddocentemateria); 
        mostrarPreguntas();
        document.getElementById("coddocentemateria").value = coddocentemateria;
    });
    //BUSCAR PREGUNTA
    $('#task-result').hide();
    $('#search').keyup(function (e) {
        if ($('#search').val()) {
            let search = $('#search').val();
            let coddocentemateria = $("#materia option:selected").val();
            $.ajax({
                url: 'Pregunta/buscarPregunta.php',
                type: 'POST',
                data: {search, coddocentemateria},
                success: function (response) {
                    //console.log(response);
                    let tasks = JSON.parse(response);
                    //console.log(tasks);
                    let template = '';
                    tasks.forEach(task => {
                        template +=
                                `<tr idp='${task.codpregunta}' tip='${task.tipo}'>
                                    <td class='color-pregunta'><input type="hidden" value='${task.codpregunta}'>${task.pregunta}</td>
                                    <td id='${task.codpregunta}'>respuesta</td>
                                    <td><img class='pimagen' src='${task.imagen}' alt="Recurso imagen"></td>
                                    <td>${task.tipo}</td>
                                    <td>${task.valor},00</td>
                                    <td><button class='btn btn-danger' id="eliminar">Eliminar</button></td>
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
        } else {
            mostrarPreguntas();
        }
    })
    //GUARDAR PREGUNTA
    $('#task-form').submit(function (e) {
        var datos = new FormData($('#task-form')[0]);
        $.ajax({
            url: 'Pregunta/guardarPregunta.php',
            type: 'POST',
            data: datos,
            //data: tipo,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                $('#task-form').trigger('reset');
                mostrarPreguntas();
                cambio("UNIR");
            }

        });
        e.preventDefault();
    });
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
                            `<tr idp='${task.codpregunta}' tip='${task.tipo}'>
                                    <td class='color-pregunta'><input type="hidden" value='${task.codpregunta}'>${task.pregunta}</td>
                                    <td id='${task.codpregunta}'>respuesta</td>
                                    <td><img class='pimagen' src='${task.imagen}' alt="Recurso imagen"></td>
                                    <td>${task.tipo}</td>
                                    <td>${task.valor},00</td>
                                    <td><button class='btn btn-danger' id="eliminar">Eliminar</button></td>
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
    //ELIMINAR PREGUNTA
    $(document).on('click', '#eliminar', function () {
        if (confirm('Estas seguro(a) de eliminar el registro?')) {
            let element = $(this)[0].parentElement.parentElement;
            let id = $(element).attr('idp');
            let tip = $(element).attr('tip');
            $.post('Pregunta/eliminarPregunta.php', {id, tip}, function () {
                mostrarPreguntas();
            });
        }
    })
});
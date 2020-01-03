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
        }
    });
    e.preventDefault();
});
jQuery(document).ready(function($) {
    $('#add-param-row').on('click', function() {
        var row = '<tr>' +
            '<td><input type="text" name="neotel_params[keys][]" value=""></td>' +
            '<td><input type="text" name="neotel_params[values][]" value=""></td>' +
            '<td><button type="button" class="button remove-row">Eliminar</button></td>' +
            '</tr>';
        $('#neotel-params-table tbody').append(row);
    });

    $('#neotel-params-table').on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    // Agregar funcionalidad para copiar al hacer clic en las etiquetas de campo
    $('.mailtag.code').on('click', function() {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(this).text()).select();
        document.execCommand("copy");
        $temp.remove();
        alert('Etiqueta copiada: ' + $(this).text());
    });
});
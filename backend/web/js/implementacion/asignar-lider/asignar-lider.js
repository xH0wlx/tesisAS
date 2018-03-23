/**
 * Created by Howl on 19-03-2018.
 */

var botonAgregar = $("#boton-agregar-scb");


$(botonAgregar).on("click", function(e){
    alert("Click");
});

$(".modalButton").click(function (){
    var boton = $(this);
    $.get($(this).attr('href'), function(data) {
        var title = boton.data('title');
        $("#modalSCB").modal('show').find("#modalContentSCB").html(data);
        $("#modalSCB .modal-header").text(title);
    });
    return false;
});

$('#modalSCB').on('hide.bs.modal', function (e) {
});


//EVENTS
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Esta seguro que quiere eliminar este socio?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limite máximo alcanzado");
});
//

$(".dynamicform_wrapper").on("beforeInsertPropio", function(e, item) {
    var $form = $("#dynamic-form"),
        data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
    if ($($form).find("div.has-error").length !== 0) {
        $.notify({message: "No puede agregar otro Socio si no ha completado todos los datos requeridos"},{type: "danger"});
        return false;
    }else{
        return true;
    }
});
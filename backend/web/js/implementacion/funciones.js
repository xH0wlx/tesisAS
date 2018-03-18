/**
 * Created by Howl on 15-04-2017.
 */
$('#botonVolverSinGuardar').on('click', function(e) {
    var boton = $('#botonVolverSinGuardar');
    var r = confirm("¿Está seguro que desea volver sin guardar?");
    if (r == true) {
        window.location.replace(boton.val());
    }
});

$('#botonVolver').on('click', function(e) {
    var boton = $('#botonVolver');
    window.location.replace(boton.val());
});
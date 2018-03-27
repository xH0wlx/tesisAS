/**
 * Created by Howl on 19-03-2018.
 */

$(".modalButton").click(function (){
    var boton = $(this);
    $.get($(this).attr('href'), function(data) {
        var title = boton.data('title');
        $("#modalSCB").modal('show').find("#modalContentSCB").html(data);
        $("#modalSCB .modal-header").text(title);
    });
    return false;
});

function reloadEventsButtonsPartialView() {
    $(".modalButton").click(function (){
        var boton = $(this);
        $.get($(this).attr('href'), function(data) {
            var title = boton.data('title');
            $("#modalSCB").modal('show').find("#modalContentSCB").html(data);
            $("#modalSCB .modal-header").text(title);
        });
        return false;
    });
}
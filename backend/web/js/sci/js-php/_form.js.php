var citynames = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
    wildcard: 'consulta',
    url: <?php echo \Yii::$app->urlManager->createUrl(['match1/service-tag-input?query=']);?> +'consulta',
    transform: function(response) {
        // Map the remote source JSON array to a JavaScript object array
        var arr = [];
        response.forEach(function(asignatura) {
            palabras = asignatura.nombre_asignatura.split(" ");
            palabras.forEach(function(palabra) {
                arr.push(palabra);
            });
        });
        return $.map(arr, function(asignatura) {
            return {
                //name: asignatura.nombre_asignatura
                name: asignatura
            };
        });
    }
}
});
citynames.initialize();

var elt = $('#requerimiento-0-tagvalues');
elt.tagsinput({
    typeaheadjs: [{
        minLength: 1,
        highlight: true,
    },{
        minlength: 1,
        limit: 6,
        name: 'citynames',
displayKey: 'name',
valueKey: 'name',
source: citynames.ttAdapter()
}],
freeInput: true
});


//EVENTS
$(".dynamicform_wrapper").on("beforeDelete", function(e, item) {
    if (! confirm("Esta seguro que quiere eliminar este item?")) {
        return false;
    }
    return true;
});

$(".dynamicform_wrapper").on("limitReached", function(e, item) {
    alert("Limit reached");
});
//

$(".dynamicform_wrapper").on("beforeInsertPropio", function(e, item) {
    var $form = $("#dynamic-form"),
        data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
    console.log($($form).find("div.has-error").length);
    if ($($form).find("div.has-error").length !== 0) {
        $.notify({message: "No puede agregar otro Requerimiento si no ha completado todos los datos requeridos"},{type: "danger"});
        return false;
    }else{
        return true;
    }
});

$(".dynamicform_wrapper22").on("beforeInsertPropio", function(e, item) {
    var $form = $("#dynamic-form"),
        data = $form.data("yiiActiveForm");
    $.each(data.attributes, function() {
        this.status = 3;
    });
    $form.yiiActiveForm("validate");
    console.log($($form).find("div.has-error").length);
    if ($($form).find("div.has-error").length !== 0) {
        $.notify({message: "No puede agregar otro Contacto si no ha completado todos los datos requeridos"},{type: "danger"});
        return false;
    }else{
        return true;
    }
});

jQuery(".dynamicform_wrapper").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Requerimiento N°" + (i + 1));
        $('input[id *= tagvalues]').tagsinput({
        typeaheadjs: [{
            minLength: 1,
            highlight: true,
        },{
            minlength: 1,
            limit: 6,
            name: 'citynames',
        displayKey: 'name',
        valueKey: 'name',
        source: citynames.ttAdapter()
    }],
        freeInput: true
    });
});
});

jQuery(".dynamicform_wrapper").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper .panel-title").each(function(i) {
        jQuery(this).html("Requerimiento N°" + (i + 1))
    });
});

jQuery(".dynamicform_wrapper2").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_wrapper2 .panel-title").each(function(i) {
        jQuery(this).html("Contacto N°" + (i + 1))
    });
});

jQuery(".dynamicform_wrapper2").on("afterDelete", function(e) {
    jQuery(".dynamicform_wrapper2 .panel-title").each(function(i) {
        jQuery(this).html("Contacto N°" + (i + 1))
    });
});

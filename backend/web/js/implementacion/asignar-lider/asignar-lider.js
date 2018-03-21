/**
 * Created by Howl on 19-03-2018.
 */
var max_fields      = 5; //maximum input boxes allowed
var wrapper         = $(".input_fields_wrap"); //Fields wrapper
var add_button      = $(".add_field_button"); //Add button ID

var x = 1; //initlal text box count
$(add_button).click(function(e){ //on add input button click
    e.preventDefault();
    if(x < max_fields){ //max input box allowed
        x++; //text box increment
        $(wrapper).append('<div class="input-group mb-3">' +
            '<input name="ge_funcionario[profesion][]" type="text" class="form-control profesion_o_grado" value="">' +
            '<div class="input-group-append">' +
            '<button class="btn btn-danger remove_field" type="button"> -&nbsp;  </button>' +
            '</div>' +
            '</div>'); //add input box
    }
});

$(wrapper).on("click",".remove_field", function(e){ //user click on remove text
    e.preventDefault(); $(this).parent('div').parent('div').remove(); x--;
})
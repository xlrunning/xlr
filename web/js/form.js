function initDataSelectInput() {
    $('input:not([data-selects=""])[data-selects]').each(function(index, el){
        var inp = $(el);
        var dataSelects = inp.attr('data-selects').split(',');
        var ul = '<ul class="dropdown-menu">';
        for (var index in dataSelects) {
            ul +=  '<li><a class="inp-help-sel" href="#">' + dataSelects[index] + '</li>';
        }
        ul += '</ul>';
        var inpGroup = $('<div class="input-group" />');
        inpGroup.html('<div class="input-group-btn"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">选择 <span class="caret"></span></button>' + ul + '</div>');
        inp.parent().append(inpGroup.append(inp));
        inp.attr('placeholder', '或输入');
    });
    $('a.inp-help-sel').on('click', function(e){
        e.preventDefault();
        $(this).closest('.input-group').find('input[type=text]').val($(this).text());
    });
}

$(document).ready(function(){
    $('.radio-group input').each(function(index, el){
        var inp = $(el);
        inp.removeClass('form-control');
        var inpId = inp.attr('id');
        var label = $('label[for="' + inpId + '"]');
        label.addClass('btn btn-default').prepend(inp);
        if (inp.is(':checked')) {
            label.addClass('active');
        }
    });
    $('.radio-group').each(function(index, el){
        var btnGroup = $('<div class="btn-group" data-toggle="buttons" />');
        btnGroup.append($(el).children());
        $(el).append(btnGroup);
    });
    /*
    $('.form-group>div:not(.radio-group) input[type="checkbox"], .form-group>div:not(.radio-group) input[type="radio"]').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
    });*/
    $('.date-selects select').each(function(index, el){
        $(el).addClass('col-md-4');
    });
    if(jQuery().selectpicker) {
       $('.selectpickers select').selectpicker({
           size: 5
       });
    }
    
    if(jQuery().timepicker) {
        $(".timepicker").timepicker({
            showInputs: false,
            showMeridian: false,
            minuteStep: 5
        });
    }
    
    $('.bootstrap-select').removeClass('form-control');
    initDataSelectInput();
    
    $('.nnv_togglable').click(function(e){
        var checkboxes = $(this).parent().find('input[type="checkbox"]');
        if ($(this).parent().find('input[type="checkbox"]:checked').length === 0) {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });
    if ($('.nnv_togglable').length) {
        
    }
});
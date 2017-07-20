
var LxAdmin = {};
$(function(){
    
    $('html').on('click', 'a[rel*=confirm]', function(e){
        var title = $(this).attr('title');
        if (!title) title = $(this).attr('data-original-title');
        return confirm('确定' + title + '吗？');
    });
    
    $('a[rel=tooltip]').tooltip();
    //$('input[placeholder], textarea[placeholder]').placeholder();
    $('.select2').select2();
    
    $('html').on('click', 'a[rel=dumb]', function(e){
        return false;
    });
    
    $('a[data-toggle="popover"]').popover({
        html: true,
        trigger: 'focus'
    });
    /*
    $('form').submit(function(){
        var formActions = $(this).find('.form-actions');
        formActions.after(formActions.find('input.btn').hide());
        formActions.html('<strong>提交中...</strong>');
    });*/
    /*
    $('input.datetimepicker').parent().addClass('input-group date');
    $('input.datetimepicker').before('<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>');
    
    if (jQuery().datetimepicker) {
        $('.datetimepicker').datetimepicker({
            pickTime: false,
            language: 'zh-CN'
        });
    }*/

    $.each($('select[data-prefix-optgroup="true"]'), function(index, item){
        var select2 = $(item).data('select2');

        if (!select2) return;
        select2.opts.formatSelection = function(obj){
            var optgroup = $(obj.element).closest('optgroup');
            if (optgroup.length > 0) {
                return optgroup.attr('label') + ' ' + obj.text;
            }
            return obj.text;
        };
    });
    $.each($('select[data-prefix-img="true"]'), function(index, item){
        var select2 = $(item).data('select2');

        if (!select2) return;
        select2.opts.formatResult = function(obj){
            var img = $(obj.element).attr('data-img');
            var innerHTML = img ? '<img style="height: 20px; margin-right: 4px;" src="'+ img + '" /> ' + obj.text : obj.text;
            return '<span style="line-height: 24px; display: inline-block; height: 24px;">' + innerHTML + '</span>';
        };
    });
    $.each($('select[data-prefix-colorspan="true"]'), function(index, item){
        var select2 = $(item).data('select2');

        if (!select2) return;
        select2.opts.formatResult = function(obj){
            
            var colorspan = $(obj.element).attr('data-colorspan');
            var innerHTML = colorspan ? '<span style="display:inline-block;height: 20px;width: 20px; margin-right: 4px; background:'+ colorspan + '"/> ' + obj.text : obj.text;
            return '<span style="line-height: 24px; display: inline-block; height: 24px;">' + innerHTML + '</span>';
        };
    });
    LxAdmin = {
        changeSaleStatus: function (btn, url) {
            if (!confirm("确定更改？")) {
                return;
            }

            btn = $(btn);
            $.get(url, function(data) {
                btn.text(data);

                var label = btn.closest('tr').find('.is-on-sale');
                if (label.hasClass('label-danger')) {
                    label.removeClass("label-danger").addClass("label-success");
                    label.text("是");
                    return;
                }

                if (label.hasClass('label-success')) {
                    label.removeClass("label-success").addClass("label-danger");
                    label.text("否");
                    return;
                }
            });
        }
    };
});
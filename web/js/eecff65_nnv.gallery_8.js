// nnv.io@2015
$(function(){
    lightbox.option({
          'resizeDuration': 200,
          'wrapAround': true
    });
    $('#nnv_form_gallery_items').sortable({
        animation: 150,
        filter: '.popover, .thumb-wrapper, .btn',
        onSort: function(e) {
            // console.log(evt);
        },
        onUpdate: function(e) {
            $('.btn-seq').removeClass('btn-default').addClass('btn-warning');
        },
        onMove: function(){
            // return false;
        }
    });

    var uploadingXHRs = [];
    var updateInfo = function() {
        $('.nbuploaded').text($('.nnv-gallery-item.uploaded').length);
        $('.nbtoupload').text($('.nnv-gallery-item.uploading').length);
    };
    // 更新序号
    var seqItems = function(callback) {
        var seqs = {};
        $('.nnv-gallery-item').each(function(index, item){
            seqs[$(item).attr('data-id')] = index+1;
        });
        $.ajax({
            url: $('.nnv-gallery-form-wrapper').attr('data-seq-url'),
            type: 'POST',
            data: {seqs:seqs},
            success: function() {
                $('.btn-seq').removeClass('btn-warning').addClass('btn-default');
                if (callback) callback();
            },
            error: function(){
                if (callback) callback();
            }
        });
    };
    $('.btn-seq').click(function(e) {
        e.preventDefault();
        seqItems(function(){
            alert('序列更新成功');
        });
    });
    $('.btn-sortbyname').click(function(e) {
        var rows = $('#nnv_form_gallery_items .form-row').get();
        rows.sort(function(a, b) {
            var compA = $(a).find('.editable-item').text().toUpperCase();
            var compB = $(b).find('.editable-item').text().toUpperCase();
            return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
        });
        $.each(rows, function(idx, row) { $('#nnv_form_gallery_items').append(row); });
    });
    $('.btn-cleargallery').click(function(e){
        if (!confirm('确认删除所有图片？')) {
            return false;
        }
        var btn = $(this);
        $.ajax({
            url: btn.attr('data-url'),
            type: 'POST',
            success: function() {
                $('#nnv_form_gallery_items').empty();
            }
        });
    });

    var submitItem = function(item) {
        var data = item.data();
        var jqXHR = data.submit().done(function(rsp){
            item.attr('data-id', rsp.id);
            // 更新图片
            var thumbWrapper = item.find('.thumb-wrapper');
            thumbWrapper.attr('href', rsp.img).attr('data-lightbox', 'gallery');
            thumbWrapper.find('img').attr('src', rsp.img);
            item.removeClass('uploading').addClass('uploaded');
            updateInfo();
        });
        return jqXHR;
    };
    
    var addItem = function(data) {
        var prototype = $('#nnv_form_gallery_items').attr('data-prototype');
        var index = $('#nnv_form_gallery_items > div').length;
        prototype = prototype.replace(/__name__/g, index);
        var formrow = $(prototype);
        var item = formrow.find('.nnv-gallery-item');
        item.removeClass('uploaded').addClass('uploading');
        item.find('.thumb-wrapper').removeAttr('data-lightbox');
        var reader = new FileReader();
        reader.onload = function (e) {
            if ($('input[name="upload-preview"]').is(':checked')) {
                item.find('.thumb-wrapper').html($('<img />').attr('src', e.target.result));
            }
        };
        var upfile = data.files[0];
        // 默认文件名作为备注
        // item.find('.editable-item').html(upfile.name.split('.').shift());
        reader.readAsDataURL(upfile);
        data.context = formrow.appendTo($('#nnv_form_gallery_items'));
        item.data(data);

        updateInfo();
        attacheItemHandlers(item);
    };

    // param: item or items
    var attacheItemHandlers = function(item){
        item.find('.thumb-wrapper').click(function(e) {
            var item = $(this).closest('.nnv-gallery-item');
            if (item.hasClass('uploading')) {
                alert('上传后方可预览');
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
            // 预览图片
        });
        item.find('.btn-item-del').click(function(e) {
            e.preventDefault();
            var item = $(this).closest('.nnv-gallery-item');
            if (!confirm('确认删除吗')) {
                return false;
            }
            item.parent().remove();

            updateInfo();

            if (item.hasClass('uploading')) {
                return false;
            }
            var itemId = item.attr('data-id');
            $.ajax({
                url: $(this).attr('data-url') + '?id=' + itemId
            });
        });
        item.find('.editable-item').each(function(index, editableItem) {
            var itemWrapper = $(editableItem).closest('.nnv-gallery-item');
            if (itemWrapper.hasClass('uploaded')) {
                $(editableItem).attr('data-pk', itemWrapper.attr('data-id'));
            }
            $(editableItem).editable({
                placement: 'left',
                emptytext: '说明文字',
                value: {
                    type: $(editableItem).attr('data-metatype'),
                    content: $(editableItem).text()
                }
            });
        });
        item.find('.btn-edit-meta').click(function(e){
            e.preventDefault();
            $(this).closest('.nnv-gallery-item').find('.editable-item').editable('toggle');
            return false;
        });
        item.find('.btn-single-upload').click(function(e) {
            e.preventDefault();
            uploadingXHRs.push(submitItem(item));
        });
    };
    // init
    attacheItemHandlers($('.nnv-gallery-item'));

    var lineAll = new ProgressBar.Line($('.nnv-gallery-form-wrapper .progress-all').get(0), {
        trailColor: '#f5f5f5',
        color: 'red',
        svgStyle: { height: '2px' }
    });
    // lineAll.animate(0.4);
    $('.btn-cancel-upload').click(function(e) {
        e.preventDefault();
        while (uploadingXHRs.length > 0) {
            var jqXHR = uploadingXHRs.pop();
            jqXHR.abort();
        }
        lineAll.set(0);
    });
    $('.btn-prune').click(function(e){
        e.preventDefault();
        if (confirm('确定删除所有未上传图片？')) {
            $('.nnv-gallery-item.uploading').parent().remove();
            updateInfo();
        }
    });
    $('.btn-upload-all').click(function(e){
        e.preventDefault();

        var nbToUpload = $('.nnv-gallery-item.uploading').length;
        if (nbToUpload === 0) {
            alert('没有待上传图片');
            return false;
        }

        $('.nnv-gallery-item').each(function(index, item){
            item = $(item);
            if (item.hasClass('uploading')) {
                submitItem(item);
            }
        });
    });
    
    $('#nnv_form_gallery_items').fileupload({
        url: $('.nnv-gallery-form-wrapper').attr('data-item-create-url'),
        dataType: 'json',
        acceptFileTypes: /(\.|\/)(jpe?g|png)$/i,
        sequentialUploads: true,
        paramName: 'file',
        fileInput: $('input[name="file"]'),
        start: function() {
            $('#nnv_form_gallery_items').sortable('disabled', true);
        },
        add: function(e, data) {
            addItem(data);
        },
        submit: function(e, data){
            var formRow = data.context;
            var index = $('#nnv_form_gallery_items .form-row').index(formRow) + 1;
            var itemEl = data.context.find('.editable-item');
            data.formData = {
                seq: index,
                meta_type: itemEl.attr('data-metatype'),
                meta_content: itemEl.hasClass('editable-empty') ? '' : itemEl.text()
            };
        },
        done: function(e, data) {
            // triggered by every add
            /*
            $.each(data.result.files, function (index, file) {

            });*/
        },
        stop: function(e, data) {
            lineAll.set(0);
            setTimeout(function() {
                seqItems(function(){
                    $('#nnv_form_gallery_items').sortable('disabled', false);
                });
            }, 100);
            // 同步顺序
        },
        progress: function(e, data) {
            //console.log('progress:', data);
        },
        progressall: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            lineAll.animate(progress / 100);
        }
    });
});
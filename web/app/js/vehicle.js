/**
 * Created by chrischen on 2017/4/20.
 */

/**
 * 加载车系车型
 */
$(function () {
    var $brand = $("#vehicle_brand");
    var brandId;
    var seriesUrl = $("#vehicle_series").val();
    //加载车型
    $brand.on('change', function () {
        brandId = $brand.val();
        $.ajax({
            url: seriesurl + "?brand=" + brandid,
            method: "post",
            success: function (res) {
                console.log('serie');
                console.log(res);
                var seriesarr = res.data;
                //重置车型车系
                $('#vehicle_serie_select').html("<option value=''>车系</option>");
                $('#vehicle_model_select').html("<option value=''>车型</option>");
                for (var series in seriesarr) {
                    seriesarr[series].foreach(function (ele) {
                        $('#vehicle_serie_select').append($('<option>', {
                            value: ele.serieid,
                            text: ele.name
                        }));
                    })
                }
            }
        });
    });
    //加载车系
    var modelUrl = $("#vehicle_model").val();
    var serieId;
    var $serie = $("#vehicle_serie_select");
    $serie.on('change', function () {
        serieId = $serie.val();
        $.ajax({
            url: modelUrl + '?serie=' + serieId,
            method: 'POST',
            success: function (res) {
                console.log('model');
                console.log(res);
                //重置车系
                var modelArr = res.data;
                $('#vehicle_model_select').html("<option value=''>车系</option>");
                for (var model in modelArr) {
                    modelArr[model].forEach(function (ele) {
                        $('#vehicle_model_select').append($('<option>', {
                            value: ele.id,
                            text: ele.name
                        }));
                    })
                }

            }
        });
    });

    //判断是否选择了品牌
    $serie.on('click', function () {
        if ($serie.children().length == 1) {
            alert('请先选择品牌');
            return false;
        }
    });

    //判断是否选择了车系
    var $model = $("#vehicle_model_select");
    $model.on('click', function () {
        if ($model.children().length == 1) {
            alert('请先选择车型');
            return false;
        }
    });
});

/**
 * 初始化颜色 日期选择器
 */
$(function () {
    var colorSelectFunction = function () {
        weui.picker([
            {
                label: '<span class="vehicle_color_silver">银灰色</span>',
                value: '银灰色'
            },
            {
                label: '<span class="vehicle_color_gray">灰色</span>',
                value: '灰色'
            },
            {
                label: '<span class="vehicle_color_black">黑色</span>',
                value: '黑色'
            },
            {
                label: '<span class="vehicle_color_white">白色</span>',
                value: '白色'
            },
            {
                label: '<span class="vehicle_color_red">红色</span>',
                value: '红色'
            },
            {
                label: '<span class="vehicle_color_blue">蓝色</span>',
                value: '蓝色'
            },
            {
                label: '<span class="vehicle_color_coffee">咖啡色</span>',
                value: '咖啡色'
            },
            {
                label: '<span class="vehicle_color_champagne">香槟色</span>',
                value: '香槟色'
            },
            {
                label: '<span class="vehicle_color_yellow">黄色</span>',
                value: '黄色'
            },
            {
                label: '<span class="vehicle_color_purple">紫色</span>',
                value: '紫色'
            },
            {
                label: '<span class="vehicle_color_green">绿色</span>',
                value: '绿色'
            },
            {
                label: '<span class="vehicle_color_orange">橙色</span>',
                value: '橙色'
            },
            {
                label: '<span class="vehicle_color_pink">粉红色</span>',
                value: '粉红色'
            },
            {
                label: '<span class="vehicle_color_brown">棕色</span>',
                value: '棕色'
            },
            {
                label: '<span class="vehicle_color_other">其他</span>',
                value: '其他'
            },
        ], {
            defaultValue: ['黑色'],
            onChange: function (result) {
                console.log(result);
            },
            onConfirm: function (result) {
                $("#vehicle_color").html(result);
                $("#vehicle_color_val").val(result);
            }
        });
    };
    var colorSelectFunctionImported = function () {
        weui.picker([
            {
                label: '<span class="vehicle_color_silver">银灰色</span>',
                value: '银灰色'
            },
            {
                label: '<span class="vehicle_color_gray">灰色</span>',
                value: '灰色'
            },
            {
                label: '<span class="vehicle_color_black">黑色</span>',
                value: '黑色'
            },
            {
                label: '<span class="vehicle_color_white">白色</span>',
                value: '白色'
            },
            {
                label: '<span class="vehicle_color_red">红色</span>',
                value: '红色'
            },
            {
                label: '<span class="vehicle_color_blue">蓝色</span>',
                value: '蓝色'
            },
            {
                label: '<span class="vehicle_color_coffee">咖啡色</span>',
                value: '咖啡色'
            },
            {
                label: '<span class="vehicle_color_champagne">香槟色</span>',
                value: '香槟色'
            },
            {
                label: '<span class="vehicle_color_yellow">黄色</span>',
                value: '黄色'
            },
            {
                label: '<span class="vehicle_color_purple">紫色</span>',
                value: '紫色'
            },
            {
                label: '<span class="vehicle_color_green">绿色</span>',
                value: '绿色'
            },
            {
                label: '<span class="vehicle_color_orange">橙色</span>',
                value: '橙色'
            },
            {
                label: '<span class="vehicle_color_pink">粉红色</span>',
                value: '粉红色'
            },
            {
                label: '<span class="vehicle_color_brown">棕色</span>',
                value: '棕色'
            },
            {
                label: '<span class="vehicle_color_other">其他</span>',
                value: '其他'
            },
        ], {
            defaultValue: ['黑色'],
            onChange: function (result) {
                console.log(result);
            },
            onConfirm: function (result) {
                $("#imported_vehicle_color").html(result);
                $("#imported_vehicle_color_val").val(result);
            }
        });
    };

    $('#vehicle_color').on('click', colorSelectFunction);
    $('#imported_vehicle_color').on('click', colorSelectFunctionImported);

    $('[name=kind]').change(function () {
        // change hidden block...
        if (this.value == 'imported') {
            $("#block_imported").removeClass('hidden');
            $("#block_new_and_secondhand").addClass('hidden');
        }
        if (this.value == 'new' || this.value == 'secondhand') {
            $("#block_new_and_secondhand").removeClass('hidden');
            $("#block_imported").addClass('hidden');
        }
    });
    $('#showDatePicker').on('click', function () {
        weui.datePicker({
            start: 1990,
            end: new Date().getFullYear(),
            defaultValue: [2017, 1, 1],
            onChange: function (result) {
                console.log(result);
            },
            onConfirm: function (result) {
                $('#showDatePicker').html(result[0] + "/" + result[1] + "/" + result[2]);
                $('#showDatePickerVal').val(result[0] + "/" + result[1] + "/" + result[2]);
            }
        });
    });
});

/**
 * formdata上传图片
 */
var img = getEle("#vehicle_upload_click");
var upload = ({
    images: '',
    token: '',
    prefix: 'test/img/',
    imgArr: [],
    getImag: function () {
        this.images = getEle("#vehicle_upload_click").files;
    },
    getToken: function () {
        this.token = getEle("#token").value;
    },
    ajax: function (f, tag) {
        $.ajax({
            url: 'http://up-z2.qiniu.com',
            type: 'POST',
            data: f,
            processData: false,
            contentType: false,
            beforeSend: function () {
            },
            xhr: function () {
                var myXhr = $.ajaxSettings.xhr();
                var upload_tag = '.upload_tag' + tag;
                var upload_tag_obj = document.querySelector(upload_tag);
                if (myXhr.upload) {
                    myXhr.upload.addEventListener('progress', function (e) {
                        console.log(tag);
                        if (e.lengthComputable) {
                            var percent = e.loaded / e.total * 100;
                            upload_tag_obj.innerHTML = percent.toFixed(2) + "%";
                            if (percent == 100) {
                                upload_tag_obj.classList.add('none');
                            }
                        }
                    }, false);
                }
                return myXhr;
            },
            success: function (res) {
                upload.imgArr.push(res.key);
                var upload_tag = '.upload_tag' + tag;
                var upload_tag_obj = document.querySelector(upload_tag);
                addDelBtn('.upload_tag' + tag, res.key);
                upload_tag_obj.parentNode.removeChild(upload_tag_obj);
                console.log(upload.imgArr);
            },
            error: function (fail) {
                console.log('error');
                console.log(fail);
            }
        });
    }
});

/**
 * 创建上传图片dom，并在页面预览
 *
 * @param oFile         图片files字段
 * @param tag           你要自定义插入的className
 */

function createImg(oFile, tag) {
    //图片的div
    var img = document.createElement('img');
    //图片定位的div
    var vehicle_img_b = document.createElement('div');
    //上传进度条div
    var upload_process = document.createElement('div');
    var vehicle_img = getEle('.vehicle_img');
    img.className = 'upload_img' + tag + " vehicle_img_b_w";
    vehicle_img_b.className = 'vehicle_img_b';
    upload_process.className = 'upload_process ' + 'upload_tag' + tag;
    upload_process.innerHTML = '等待上传';
    var reader = new FileReader();
    reader.readAsDataURL(oFile);
    reader.onload = function (oFREvent) {
        img.src = oFREvent.target.result;
    };

    vehicle_img_b.appendChild(img);
    vehicle_img_b.appendChild(upload_process);
    vehicle_img.appendChild(vehicle_img_b);
}

/**
 * 增加删除按钮
 *
 * @param tag       标志class的Name例如：.test
 * @param imgName   ajax请求成功之后返回的imgName
 */
function addDelBtn(tag, imgName) {
    //图片删除的div
    var vehicle_img_b_delete = document.createElement('div');
    vehicle_img_b_delete.className = 'vehicle_img_b_delete';
    vehicle_img_b_delete.innerHTML = 'x';
    vehicle_img_b_delete.setAttribute('data-img', imgName);
    vehicle_img_b_delete.addEventListener('click', function () {
        var self = this;
        var data_img = self.getAttribute('data-img');
        var imgArr = upload.imgArr;
        var delIndex = imgArr.indexOf(data_img);
        console.log('delIndex', delIndex);
        //删除数组中的图片
        upload.imgArr.splice(delIndex, 1)[0];
        self.parentNode.classList.add('none');

    });
    var vehicle_img_b = document.querySelector(tag);
    vehicle_img_b.parentNode.appendChild(vehicle_img_b_delete);
}

/**
 * 点击图片上的删除按钮
 *
 * 移除图片
 *
 * 移除记录的数组
 */
function deleteEvent(self) {
    //得到删除图片的名称
    var data_img = self.getAttribute('data-img');
    var imgArr = upload.imgArr;
    var delIndex = imgArr.findIndex(function (ele) {
        if (ele == data_img) {
            return ele;
        }
    });
    //删除数组中的图片
    upload.imgArr.splice(delIndex, 1)[0];
    self.parentNode.classList.add('none');
}

/**
 * 监听图片上传
 *
 * 1：图片上传显示预览
 * 2：图片上传在预览图上显示上传进度
 * 3：完成上传之后将七牛返回的图片名称存在input框
 */
img.addEventListener('change', function () {
    upload.getImag();
    upload.getToken();
    var imgLen = upload.images.length;
    for (var i = 0; i < imgLen; i++) {
        //预览图片
        createImg(upload.images[i], i);

        //上传图片
        var f = new FormData();
        f.append('token', upload.token);
        var img = upload.images[i];
        var randName = Math.random().toString(36).substr(2) + img.name.match(/\.?[^.\/]+$/);
        f.append('file', img);
        f.append('key', upload.prefix + randName);
        upload.ajax(f, i);
    }

});

/**
 * 验证表单
 *
 * 提交表单
 *
 * 现在测试的七牛前缀是test/img/，正式环境的前缀需要改成/image/detail/
 */
getEle(".add_vehicle_submit_btn").addEventListener('click', function () {
    //图片的名称，以数组形式存储
    var img_arr = upload.imgArr;
    var subUrl = getEle(".subUrl").value;
    $.ajax({
        url:subUrl,
        data:{
            "image":JSON.stringify(upload.imgArr),
            "kind":$("[name=kind]").val(),
            "sale": $("[name=nbOnSale]").val(),
            "vin": $("[name=vin]").val(),
            "brand_id": $("select[name=brand]").val(),
            "series_id": $("select[name=serie]").val(),
            "model_id": $("select[name=model]").val(),
            "mileage": $("[name=mileage]").val(),
            "register_date": $("[name=register_date]").val(),
            "color": $("[name=color]").val(),
            // attribute of importedCar
            "imported_on_sale": $("[name=imported_on_sale]").val(),
            "imported_stock": $("[name=imported_stock]").val(),
            "imported_info": $("[name=imported_info]").val(),
            "imported_color": $("[name=imported_color]").val(),
            "imported_brand": $("[name=imported_brand]").val(),
            "imported_series": $("[name=imported_series]").val(),
            "imported_model": $("[name=imported_model]").val()
        },
        type:'POST',
        success:function (res) {
            alert('添加成功');
        },
        error: function (res) {
            alert('参数错误');
        }
    });
});


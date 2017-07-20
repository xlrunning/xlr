/**
 * Created by chrischen on 2017/4/1.
 */
function getQueryString(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
    var r = window.location.search.substr(1).match(reg);
    if (r != null) return unescape(r[2]); return null;
}
//加载分页
$(function () {
    // Initialize
    window.bLazy = new Blazy();
    var $carUl = $("#car-ul");
    var urlStr = window.location.origin + window.location.pathname;
    $("#loader-css").hide();
    $("#loadMoreBtn").on('click', function () {
        var page = $("#listPage").val();
        var reqPage = parseInt(page) + 1;
        var urlRe = new RegExp("brand");
        //带brand带查询
        var url = window.location.href;
        if (urlRe.test(url)){
            var brand = getQueryString('brand');
            var listUrl = urlStr + "?page=" + reqPage +'&brand=' + brand;
        }else{
            var listUrl = urlStr + "?page=" + reqPage;
        }
        console.log(reqPage);

        var isLoadFinish = $("#loader-info").html();
        if (isLoadFinish === '加载完毕') {
            return false;
        }
        $("#loader-info").hide();
        $("#loader-css").show();
        $.ajax({
            url: listUrl,
            success: function (res) {
                console.log(res);
                var re = new RegExp("没有更多了");
                //匹配是否还有更多
                //如果没有更多则加载完毕
                if (re.test(res)) {
                    $("#loader-info").html('加载完毕');
                    $("#loader-css").hide();
                    $("#loader-info").show();
                } else {
                    $carUl.append(res);
                    $("#listPage").val(reqPage);
                    $("#loader-css").hide();
                    $("#loader-info").show();
                }
            }
        }).done(function () {
            //重新加载blazy
            var lazyClock = setInterval(function () {
                if ($('.b-lazy[data-src]').length === 0) {
                    clearInterval(lazyClock);
                    return;
                }
                bLazy.revalidate();
            }, 300);
        });

    });
});

//点击选择过滤
$(function () {
    //给品牌增加选中颜色
    $("#select-brand").on('click', function () {
        this.classList.add('brand-active');
    });

    //select 改变的时候 请求查询 brand
    $("#select-brand").on('change', function () {
        var selectVal = this.value;
        var urlStr = window.location.origin + window.location.pathname;
        window.location.href = urlStr + "?brand=" + selectVal;
    });
});
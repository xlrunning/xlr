/**
 * Created by chrischen on 2017/3/3.
 */
//breadcrumb
// ;$(function () {
//     var now_url = $("#now_url").val();
//     var parent_class = now_url.split("_");
//     console.log(parent_class);
//     $(".breadcrumb li:nth-child(1)").html(parent_class[1]);
//     $(".breadcrumb li:nth-child(2)").html(parent_class[2]);
//     $(".breadcrumb li:nth-child(3)").html(parent_class[3]);
// });
//sider_bar
;$(function () {
    var now_url = $("#now_url").val();
    var parent_class = now_url.split("_")[2];
    var $li = $('#rs-siderbar-nav').find('.'+now_url);
    var $pObj = $li.closest('li[class^='+parent_class+']');
    $pObj.addClass("active");
    $pObj.addClass("selected");
    $li.addClass("selected");
    $li.addClass("active");
});
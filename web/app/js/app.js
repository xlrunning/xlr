/**
 * Created by chrischen on 2017/4/18.
 */
$(function () {
    $(".common_deposit").bind("opened", function () {
        $(this).find('.common_arrow_img').addClass('common_trasform_90');
    });
    $(".common_deposit").bind("closed", function () {
        $(this).find('.common_arrow_img').removeClass('common_trasform_90');
    });
    $(".common_deposit h2").each(function () {
        if ($(this).is('.open')) {
            $(this).find('.common_arrow_img').addClass('common_trasform_90');
        }
    });
});
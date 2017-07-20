/**
 * Created by chrischen on 2017/3/31.
 */
/*
 * formatMoney(s,type)
 * 功能：金额按千位逗号分割
 * 参数：s，需要格式化的金额数值.
 * 参数：type,判断格式化后的金额是否需要小数位.
 * 返回：返回格式化后的数值字符串.
 */
function formatMoney(s) {
    var type = 2;
    if (/[^0-9\.]/.test(s))
        return "";
    if (s == null || s == "")
        return "";
    s = s.toString().replace(/^(\d*)$/, "$1.");
    s = (s + "00").replace(/(\d*\.\d\d)\d*/, "$1");
    s = s.replace(".", ",");
    var re = /(\d)(\d{3},)/;
    while (re.test(s))
        s = s.replace(re, "$1,$2");
    s = s.replace(/,(\d\d)$/, ".$1");
    if (type == 0) {// 不带小数位(默认是有小数位)
        var a = s.split(".");
        if (a[1] == "00") {
            s = a[0];
        }
    }
    return s;
}

$(function () {
    window.onCashierBack = function(data){
//                $('.payresult').html(data);

        var jsonObj = JSON.parse(data);

        var payTypes = {
            '1001': '现金',
            '1003': '微信',
            '1004': '支付宝',
            '1006': '银行卡'
        };
        // 1001 现金
        // 1003 微信
        // 1004 支付宝
        // 1005 百度钱包
        // 1006 银行卡
        // 1007 易付宝
        // 1009 京东钱包
        // 1011 QQ钱包
        /*内容样式为:
         {
         "errCode": "0",
         "errMsg": "支付成功",
         "out_trade_no": "1474442791311",
         "trade_status": "PAY",
         "input_charset": "UTF-8",
         "cashier_trade_no": null,
         "pay_type": "1006",
         "pay_info": "支付成功"
         }*/
        document.querySelector('.pay-page').style.display = 'none';
        document.querySelector('.check-ok').style.display = 'block';
        if (jsonObj.errCode == 0) {
            document.querySelector('.check-ok-span').innerHTML = jsonObj.pay_info;
        } else {
            document.querySelector('.pay-success').innerHTML = "<span class='check-ok-img-error'></span><span class='check-ok-span'>" + jsonObj.errMsg + "</span>";
        }

        // $('.pay-success').html(data);
        // $('.payresult').append('<br/>' + jsonObj.pay_info + '：' + payTypes[jsonObj.pay_type]);
    };
});

// function test() {
//     var jsonObj = {
//         "errCode": "0",
//         "errMsg": "支付成功",
//         "out_trade_no": "1474442791311",
//         "trade_status": "PAY",
//         "input_charset": "UTF-8",
//         "cashier_trade_no": null,
//         "pay_type": "1006",
//         "pay_info": "支付成功"
//     };
//     console.log('click');
//     payWay.payWay = '银联支付';
//     payWay.payWayLogoUrl = '/app/img/unionpay.png';
//     document.querySelector('.vehicle-float-qrcode').innerHTML = "<img src='../img/pos.png' class='qr-code-img'>";
//     document.querySelector('.pay-page').style.display = 'none';
//     document.querySelector('.check-ok').style.display = 'block';
//     payWay.isFloat=true;
//     if (jsonObj.errCode == 0) {
//         document.querySelector('.check-ok-span').innerHTML = JSON.stringify(jsonObj);
//     } else {
//         document.querySelector('.pay-success').innerHTML = "<span class='check-ok-img-error'></span><span class='check-ok-span'>" + jsonObj.errMsg + "</span>";
//     }
// }
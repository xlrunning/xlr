/**
 * Created by chrischen on 2017/4/11.
 */
function rand(min, max) {
    return Math.round(Math.random() * (max - min) + min);
}
var payWay = new Vue({
    delimiters: ['${', '}'],
    el: "#pay-way-vue",
    data: {
        orderPrice: '',      //确认价格
        isFloat: false,      //控制浮层
        payWay: '',          //支付方式：支付宝、微信、银联
        alertMes: '',        //提醒消息
        isAlert: false,
        payWayLogoUrl: '',
        tradeId: '',
        tradeNo: '',
        setId: '',               //轮询id
        amount:'',
    },
    filters: {
        formatMoney: function (s) {
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
            return s + '元';
        }
    },
    methods: {
        payClick: function (chooseWay, type) {
            if (this.orderPrice.length == 0) {
                this.showAlert("请输入价格", 1000);
                return false;
            }
            var payUrlId = document.querySelector('.payment-url').value;
            this.amount = this.orderPrice / 1000;
            var payUrl = payUrlId + '?type=' + type + '&amount=' + this.amount;
            switch (chooseWay) {
                case 'alipay':
                    this.payWay = '支付宝';
                    this.payWayLogoUrl = '/app/img/alipay-q.png';
                    var payType = 'alipay';//传递的type
                    this.getQrcode(payUrl, payType);
                    break;
                case 'wxpay':
                    this.payWay = '微信支付';
                    this.payWayLogoUrl = '/app/img/wxpay.png';
                    var payType = 'wx';//传递的type
                    this.getQrcode(payUrl, payType);
                    break;
                case 'unionpay':
                    this.payWay = '银联支付';
                    this.payWayLogoUrl = '/app/img/unionpay.png';
                    document.querySelector('.vehicle-float-qrcode').innerHTML = "<img src='../img/pos.png' class='qr-code-img'>";

                    if (typeof android == 'undefined') {
                        // @todo 显示请使用智能POS机
                    } else {
                        this.wposPay(payUrl, 'wpos');
                    }

                    break;
            }
            this.isFloat = true;
        },
        //显示错误提醒，第一个参数为提醒文字，第二个参数为提醒显示的时间
        showAlert: function (message, time) {
            this.alertMes = message;
            var self = this;
            this.isAlert = true;
            setTimeout(function () {
                self.isAlert = false;
            }, time);
        },
        wposPay: function (payUrl, payType) {
            var self = this;
            $.ajax({
                url: payUrl,
                method: 'POST',
                data: {
                    method: payType
                },
                success: function (res) {
                    self.tradeId = res.trade;

                    self.tradeNo = res.tradeNo;
                    android.invokeCashier(self.tradeNo, self.amount, res.tradeBody, res.notifyUrl, '');
                    // self.checkPay();
                }
            });
        },
        getQrcode: function (payUrl, payType) {
            var self = this;
            $.ajax({
                url: payUrl,
                method: 'POST',
                data: {
                    method: payType
                },
                success: function (res) {
                    document.querySelector('.vehicle-float-qrcode').innerHTML = '<img src=' + res.qr + ' alt="" class="qr-code-img">';
                    self.tradeId = res.trade;
                    self.checkPay();
                }
            });
        },
        checkPay: function () {
            var self = this;
            this.setId = setInterval(function () {
                var baseUrl = document.querySelector('.pay-check-url').value;
                var url = baseUrl.replace(/\d/, self.tradeId);
                $.ajax({
                    url: url,
                    method: 'POST',
                    success: function (res) {
                        console.log(res);
                        //支付成功，跳出轮询
                        if (res.payed === 1) {
                            console.log('pay ok');
                            clearInterval(self.setId);
                            document.querySelector('.pay-page').style.display = 'none';
                            document.querySelector('.check-ok').style.display = 'inherit';
                        }
                    }
                });
            }, 2000)
        },
        closeFloat: function () {
            this.isFloat = false;
            clearInterval(this.setId);
        },
    }
});
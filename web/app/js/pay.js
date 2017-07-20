/**
 * Created by chrischen on 2017/4/24.
 */
/*
 * formatMoney(s,type)
 * 功能：金额按千位逗号分割
 * 参数：s，需要格式化的金额数值.
 * 参数：type,判断格式化后的金额是否需要小数位.
 * 返回：返回格式化后的数值字符串.
 */
function formatMoney(s, type) {
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


var pay = ({
    data:{
        amountShow: this.getEle("#amountShow"),
        amount: this.getEle("#amount"),
        submitBtn: this.getEle(".pay_amount_btn"),
        clearBtn: this.getEle(".pay_amount_clear"),
        amountVal:''
    },
    formatMoney:function (s, type) {
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
    },
    getEle:function (ele) {
        return document.querySelector(ele);
    },
    //清除input
    clearInput:function () {
        this.data.amountShow.value = '';
        this.data.amount.value = '';
    },
    checkIsPut:function () {
        if (this.amountVal===0 || this.amountVal==="" || this.amountVal===undefined){
            this.data.submitBtn.disabled = true;
            this.data.submitBtn.classList.add('btn_disable');
            console.log('123');
        }else{
            this.data.submitBtn.disabled = false;
            this.data.submitBtn.classList.remove('btn_disable');
        }
    },
    //显示清除按钮
    clearBtnShow:function () {
        this.data.clearBtn.classList.remove("display_none");
    },
    //显示提交按钮
    showSubmitBtn:function () {
        this.data.submitBtn.classList.remove("btn_disable");
    },
    removeSubmitBtn:function () {
        this.data.submitBtn.classList.add("btn_disable");
    }
});


var amountShow = getEle("#amountShow");
var amount = getEle("#amount");
var submitBtn = getEle(".pay_amount_btn");
var clearBtn = getEle(".pay_amount_clear");
//显示清除按钮 移除提交按钮的disable
amountShow.addEventListener('keyup',function () {
    if (pay.data.amountShow.value.length > 0){
        pay.clearBtnShow();
        pay.showSubmitBtn();
    }else{
        pay.removeSubmitBtn();
    }
});

//清除input框的值 增加提交框的disable
clearBtn.addEventListener('click',function () {
    pay.data.amountShow.value = '';
    pay.data.clearBtn.classList.add("display_none");
    pay.removeSubmitBtn();
});

//格式化输入的值
amountShow.addEventListener('blur', function () {
    var data = pay.data;
    data.amountVal = data.amountShow.value;
    data.amount.value = data.amountVal;
    var formatVal = pay.formatMoney(data.amountVal, 2);
    data.amountShow.value = formatVal;
});

//提交按钮
submitBtn.addEventListener('click',function () {
    if (pay.data.amount.value > 0){
        console.log('submit');
    }else{
        alert('输入价格');
    }
});



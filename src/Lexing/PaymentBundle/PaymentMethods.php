<?php

namespace Lexing\PaymentBundle;

/**
 * PaymentMethods
 *
 * @author kail
 */
class PaymentMethods
{
    /**
     * 微信JS
     */
    const WX_JSAPI        = 'wx.jsapi';
    
    /**
     * 微信扫码
     */
    const WX_NATIVE       = 'wx.native';

    /**
     * 支付宝JS
     */
    const ALIPAY_JSAPI    = 'alipay.jsapi';

    /**
     * 支付宝扫码
     */
    const ALIPAY_NATIVE   = 'alipay.native';

    const WPOS_PENDING    = 'wpos.pending'; // 创建交易时尚未确定
    const WPOS_CASH       = 'wpos.cash';
    const WPOS_WX         = 'wpos.wx';
    const WPOS_ALIPAY     = 'wpos.alipay';
    const WPOS_UNIONPAY   = 'wpos.unionpay';
}

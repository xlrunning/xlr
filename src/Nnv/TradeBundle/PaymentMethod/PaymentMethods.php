<?php

namespace Nnv\TradeBundle\PaymentMethod;

/**
 * PaymentMethods
 * 
 * define some constants
 *
 * @author kail
 */
class PaymentMethods
{
    const BALANCE         = 'balance';
    
    const WX_JSAPI        = 'wx.jsapi';
    
    /**
     * 扫码
     */
    const WX_NATIVE       = 'wx.native';

    /**
     */    
    const ALIPAY_JSAPI    = 'alipay.jsapi';

    /**
     * 扫码
     */
    const ALIPAY_NATIVE   = 'alipay.native';
    
    public static function belongToWx($paymentMethod)
    {
        return $paymentMethod === self::WX_JSAPI || $paymentMethod === self::WX_NATIVE;
    }

    public function getAvailableMethods()
    {

    }
}

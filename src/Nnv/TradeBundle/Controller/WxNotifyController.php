<?php

namespace Nnv\TradeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * WxNotifyController
 * 
 * 负责接收处理来自微信的支付相关通知
 * 
 * @Route("/trade/wxnotify")
 */
class WxNotifyController extends Controller
{
    /**
     * 处理微信支付结果通知
     * 将该url设置为notify_url参数的值
     * 
     * @Route("/onpay", name="nnv_wx_notify_onpay")
     * @Method({"POST"})
     */
    public function onpayAction(Request $req)
    {
        $wxpay = $this->get('nnv_wx.pay');
        $notifiedArr = $wxpay->notify($req->getContent());
        
        $logger = $this->get('logger');
        $logger->error('---- wxpay notified: ' . $req->getContent() . json_encode($notifiedArr));
        
        // do trade
        $transId = $notifiedArr['transaction_id'];
        
        //查询订单，判断订单真实性
        if (!$wxpay->queryOrder($transId)) {
            // $msg = "订单查询失败";
            $logger->error('payv3 ' . $transId . ' 订单查询失败');
            return $wxpay->getNotifyResponse();
        }
        
        $tm = $this->container->get('nnv_trade.manager');
        $tradeNo = $notifiedArr['out_trade_no'];
        $trade = $tm->getTradeByNo($tradeNo);
        if (!empty($trade) && $trade->getTransId() == null) {
            $logger->crit('order ' . $tradeNo . ' paid');
            $tm->payTrade($trade, $transId);
        }
        if (empty($trade)) {
            $logger->error('order ' . $tradeNo . ' 订单不存在');
        }
        
        return $wxpay->getNotifyResponse();
    }
}
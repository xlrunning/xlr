<?php

namespace Nnv\TradeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Cookie;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\EventDispatcher\GenericEvent;
use Nnv\TradeBundle\Event\TradeEvents;
use Nnv\TradeBundle\Event\TradeEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Nnv\TradeBundle\PaymentMethod\PaymentMethods;

/**
 * TradeController
 * 
 * @todo NATIVE, ALIPAY
 * @Route("/trade")
 */
class TradeController extends Controller
{
    /**
     * JSAPI
     * @todo make action slim
     * 
     * @Route("/pay/{type}", name="nnv_trade_pay", defaults={"_format":"json"})
     * @Method({"POST"})
     */
    public function payAction($type, Request $req)
    {
        // @todo trade exist, then repay
        // @todo validate $paymentMethod(supported or not)
        $paymentMethod = $req->get('method');
        if (!$paymentMethod) {
            $ret = ['ok' => 0, 'err' => '需指定支付方式'];
        }
        try {
            $tm = $this->get('nnv_trade.manager');
            $trade = $tm->createTrade($type, $this->getUser(), $req);
            $ret = $tm->preparePayment($trade, $paymentMethod);
            $ret['ok'] = 1;
        } catch (\Exception $e) {
            $ret = ['ok' => 0, 'err' => $e->getMessage()];
        }
        
        return $this->json($ret);
    }
}
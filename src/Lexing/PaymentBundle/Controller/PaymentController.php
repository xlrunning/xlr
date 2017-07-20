<?php

namespace Lexing\PaymentBundle\Controller;

use Lexing\PaymentBundle\PaymentMethods;
use Lexing\TradeBundle\Content\TradeVehicleSaleContent;
use Lexing\TradeBundle\Entity\VehicleSale;
use Lexing\TradeBundle\Payment\Payment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Constraints\Url;

/**
 * @Route("/payment")
 */
class PaymentController extends Controller
{
    /**
     * @todo 哪种支付网关
     *
     * @Route("/notify", name="lx_payment_notify")
     * @Method({"POST"})
     */
    public function notifyAction(Request $req)
    {
        return $this->get('lexing_payment.payment_manager')->onNotify($req);
    }

    /**
     * @Route("/vehicles")
     * @Template("pay/vehicles.html.twig")
     */
    public function vehiclesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $vehicles = $em->createQuery('SELECT v, s FROM LexingVehicleBundle:Vehicle v LEFT JOIN v.sales s')
            ->getResult();
        return [
            'vehicles' => $vehicles
        ];
    }

    /**
     * @Route("/vehicle/{id}", name="lx_pay_vehicle")
     */
    public function vehicleAction($id, Request $req)
    {
        $methodMapping = [
            'wx' => PaymentMethods::WX_NATIVE,
            'alipay' => PaymentMethods::ALIPAY_NATIVE,
            'wpos' => PaymentMethods::WPOS_PENDING
        ];

        $paymentMethod = $methodMapping[$req->get('method')];
        $type = $req->get('type');

        $tm = $this->get('lexing_trade.trade_manager');

        // @todo 已经付过定金，首付等，ok=>0
        $typeMapping = [
            'first' => VehicleSale::TYPE_PAID_FIRST,
            'front' => VehicleSale::TYPE_PAID_FRONT,
            'final' => VehicleSale::TYPE_PAID_FINAL,
        ];

        $amount = $req->query->getInt('amount');
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($id);

        $trade = $tm->createTrade(
            new Payment($amount, $paymentMethod, ''.$vehicle),
            new TradeVehicleSaleContent(
                $vehicle,
                $typeMapping[$type]
            )
        );
        $returnVars = [
            'ok' => 1,
            'trade' => $trade->getId()
        ];
        if ($paymentMethod == PaymentMethods::WPOS_PENDING) {
            $returnVars['tradeNo'] = $trade->getTradeNo();
            $returnVars['tradeBody'] = $trade->getSummary();
            $returnVars['notifyUrl'] = $this->generateUrl('lx_pay_wpos_notify', [], UrlGeneratorInterface::ABSOLUTE_URL);
        } else {
            $codeUrl = $this->get('lexing_payment.payment_manager')->putOrder($trade);
            $returnVars['qr'] = $codeUrl;
        }
        return $this->json($returnVars);
    }

    /**
     * 检查是否付款
     * @Route("/check/{tradeId}", name="lx_pay_check")
     */
    public function checkAction($tradeId, Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $trade = $em->getRepository('LexingTradeBundle:Trade')->find($tradeId);
        return $this->json([
            'ok' => 1,
            'paid' => $trade->isPaid() ? 1 : 0
        ]);
    }

    // @todo 退还定金

    /**
     *
     * @Route("/test", name="lx_pay_test")
     */
    public function testAction(Request $req)
    {
        $paymentMethod = $req->get('method');
        $type = $req->get('type');

        $tm = $this->get('lexing_trade.trade_manager');

        // @todo 已经付过定金，首付等
        $id = 3;
        $amount = $req->query->getInt('amount');
        $em = $this->getDoctrine()->getManager();
        $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($id);
        dump($vehicle->getFirstPaidTrade());exit;

        $trade = $tm->createTrade(
            'vehicle.sale',
            new Payment($amount, PaymentMethods::WX_NATIVE, '车辆' . $vehicle),
            $vehicle->getId(),
            ['type' => VehicleSale::TYPE_PAID_FIRST]
        );

        return new Response('success');
    }


    /**
     *
     * @Route("/testpay", name="lx_pay_testpay")
     */
    public function testpayAction(Request $req)
    {
        // $this->get('lexing_payment.payment_manager');

        // @todo already paid exception

        $tm = $this->get('lexing_trade.trade_manager');
        $tradeNo = $req->get('num');
        $tm->payTrade($tradeNo, date('YmdHis') . mt_rand(99999, 999999));
        return new Response('success'.$tradeNo);
    }

    /**
     * 旺POS收银测试页
     *
     * @Route("/testpos", name="lx_pay_testpos")
     * @Template("app/vehicle_wpos.html.twig")
     */
    public function testposAction(Request $req)
    {
        return [];
    }

    /**
     * 旺POS支付异步通知
     *
     * @Route("/wpos/notify", name="lx_pay_wpos_notify")
     */
    public function wposNotifyAction(Request $req)
    {
        $wangposGateway = $this->get('lexing_payment.wangpos_gateway');
        return $wangposGateway->onNotify($req);
    }
}

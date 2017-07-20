<?php

namespace Lexing\DealerBundle\Controller;

use Lexing\ImportedCarBundle\Entity\ImportedVehicle;
use Lexing\VehicleBundle\Entity\Vehicle;
use Qiniu\Storage\UploadManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class AppController
 * @package Lexing\VehicleBundle\Controller
 *
 * 车商APP包括旺POS机
     * @Route("/app/dealer")
 */
class AppController extends Controller
{
    private function getDealer()
    {
        if (!$this->isGranted('ROLE_USER') || (!$dealer = $this->getUser()->getAdminDealer())) {
            throw new AccessDeniedHttpException('需车商身份认证');
        }
        return $dealer;
    }

    /**
     * 首页
     *
     * @Route("/index", name="lx_dealer_app_dealer_index")
     * @Template("app/index/index.html.twig")
     */
    public function indexAction()
    {
        $dealer = $this->getDealer();
        return [
            'dealer' => $dealer
        ];
    }

    /**
     * 库存车辆
     * @Route("/vehicles", name="lx_dealer_app_dealer_vehicles")
     * @Template("app/list/list_page.html.twig")
     */
    public function vehiclesAction(Request $request)
    {
        $dealer = $this->getDealer();


        $page = $request->get('page') > 0 ? $request->get('page') : 1;
        $itemNum = 10;
        $numItemsPerPage = ($page - 1) * 10;
        $em = $this->getDoctrine()->getManager();
        $dql = 'SELECT v, s FROM LexingVehicleBundle:Vehicle v LEFT JOIN v.sales s WHERE v.dealer = :dealer AND v.onSale = :onSale';
        $params = [
            'dealer' => $dealer,
            'onSale' => true,
        ];

        $brand = $request->get('brand');
        if ($brand && $dealer){
            $dql .= ' AND v.model.brand = :brand';
            $params['brand'] = $brand;
        }

        // handle sale on or sale out
        $sale = $request->get('sale');
        if ($sale && in_array($sale, ['on', 'out']) && $dealer) {
            $dql .= ' AND v.onSale= :onSale';
            $sale = $sale == 'on' ? 1 : 0;
            $params['onSale'] = $sale;
        }

        if ($sale && in_array($sale, ['process']) && $dealer) {
            $dql .= ' AND s.id is not null';
        }

        $cq = $em->createQuery($dql);
        $vehicleArr = $cq->setParameters($params)
            ->setMaxResults($itemNum)
            ->setFirstResult($numItemsPerPage)
            ->getResult();
        //得到统计的上架车辆的数量
        $nbOnSale = $em->getRepository("LexingVehicleBundle:Vehicle")->nbOnSale($dealer);

        $brandArr = $this->get('lexing_vehicle.get_vehicle_info')->getBrand();
        return $this->render($page > 1 ? 'app/list/list_items.html.twig' : 'app/list/list_page.html.twig', [
            'vehicleArr' => $vehicleArr,
            'page' => $page,
            'dealer' => $dealer,
            'title' => '库存车辆',
            'brandArr' => $brandArr,
            'nbOnSale' => $nbOnSale
        ]);
    }

    /**
     * 我的账户
     * @Route("/my", name="lx_dealer_app_dealer_my")
     * @Template("/app/my/my.html.twig")
     */
    public function myAction()
    {
        $dealer = $this->getDealer();

        $em = $this->getDoctrine()->getManager();
        $nbOnSale = $em->getRepository("LexingVehicleBundle:Vehicle")->nbOnSale($dealer);
        // 正在走款的车辆
        // @todo 专门提醒付了首付或者全款的？
        $nbInSaleProcess = $em->createQuery('SELECT COUNT(DISTINCT v.id) FROM LexingTradeBundle:VehicleSale vs LEFT JOIN vs.vehicle v WHERE v.dealer = :dealer AND v.onSale = :onSale')
            ->setParameters(['dealer' => $dealer, 'onSale' => true])
            ->getSingleScalarResult();

        $credit = $em->getRepository("LexingLoanBundle:CreditApplication")->getCredit(['dealer' => $dealer]);

        $vehicleNum = $this->get('lexing_vehicle.get_vehicle_info')->countVehicle(['dealer' => $dealer]);

        return [
            'dealer' => $dealer,
            'nbOnSale' => $nbOnSale,
            'vehicleNum' => $vehicleNum,
            'credit' => $credit
        ];
    }

    /**
     * 贷款申请
     * @Route("/loanreq", name="lx_dealer_app_dealer_loanreq")
     * @Template("app/loan/loanreq.html.twig")
     */
    public function loanreqAction()
    {
        $dealer = $this->getDealer();

        $em = $this->getDoctrine()->getManager();

        $credit = $em->getRepository("LexingLoanBundle:CreditApplication")->getCredit(['dealer' => $dealer]);
        return [
            'dealer' => $dealer,
            'credit' => $credit,
        ];
    }

    /**
     * 关于乐行
     * @Route("/about", name="lx_dealer_app_dealer_about")
     * @Template("app/about.html.twig")
     */
    public function aboutAction()
    {
        return [];
    }

    /**
     * 帮助支持
     * @Route("/support", name="lx_dealer_app_dealer_support")
     * @Template("app/support.html.twig")
     */
    public function supportAction()
    {
        return [];
    }

    /**
     *上架车辆
     * @Route("/add/vehicle", name="lx_dealer_app_dealer_add_vehicle")
     * @Template("app/add/vehicle.html.twig")
     */
    public function addVehicleAction()
    {
        $token = $this->get('lexing_dealer.qiniu')->getToken();
        $brandArr = $this->get('lexing_vehicle.get_vehicle_info')->getBrand();
        return [
            'brandArr' => $brandArr,
            'token' => $token
        ];
    }

    /**
     * 得到上传的参数
     *
     * @Route("/get/vehicle", name="lx_dealer_app_get_vehicle")
     */
    public function getVehicleAction(Request $request)
    {
        $dealer = $this->getDealer();

        $payload = $request->request->all();
        if (in_array($payload['kind'], [Vehicle::SECONDHAND, Vehicle::NEW])) {
            $repo = $this->getdoctrine()->getrepository('LexingDealerBundle:VehicleDealer');

            $resultOfValidation = $repo->validate($payload);

            // return an error json.
            if ($resultOfValidation != null) {
                return $this->json(['validator' => $resultOfValidation], 400);
            }

            $model = $this->getDoctrine()->getRepository('LexingVehicleBundle:VehicleModel')->find($payload['model_id']);

            $repo->storeVehicle(
                $payload, $model,
                $dealer
            );
            return $this->json(['code'=>'done', 'message'=>'添加成功']);
        }
        if (in_array($payload['kind'], [ImportedVehicle::IMPORTED_VEHICLE])) {
            // handler imported car date.
            $payload['imported_stock'] = @(int)$payload['imported_stock'];
            $repo = $this->getDoctrine()->getRepository('LexingImportedCarBundle:ImportedVehicle');

            $model = $this->getDoctrine()->getRepository('LexingImportedCarBundle:ImportedModel')->find($payload['imported_model']);

            $resultOfValidation = $repo->validate($payload);

            // return an error json.
            if ($resultOfValidation != null) {
                return $this->json(['validator' => $resultOfValidation], 400);
            }

            $repo->storeVehicle(
                $payload,
                $dealer,
                $model
            );

            return $this->json(['code'=>'done', 'message'=>'添加成功']);
        }

        throw new \RuntimeException('Kind value incorrect.');
    }

    /**
     * 上传图片，并返回图片名称
     *
     * @Route("upload/img", name="lx_dealer_app_dealer_upload_img")
     *
     */
    public function uploadImg()
    {
        $upManager = new UploadManager();
        $token = $this->get('lexing_dealer.qiniu')->getToken();
    }

    /**
     *
     * @Route("/pay", name="lx_dealer_app_dealer_pay")
     *
     * @Template("app/pay/pay.html.twig")
     */
    public function payAction()
    {
        return [];
    }
}

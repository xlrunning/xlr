<?php

namespace Lexing\AssistBundle\Controller;

use Nnv\NotificationBundle\Event\NotifiableEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("/assist")
 */
class AssistController extends Controller
{

    /**
     *
     * @Route("/index", name="lx_assist_index")
     * @Template()
     */
    public function indexAction()
    {
        // 统计...
        $em = $this->getDoctrine()->getManager();
//        $this->get('event_dispatcher')->dispatch('noti.test', new NotifiableEvent(null, [
//            'mobile' => '18621586912',
//            'code' => '456789'
//        ]));
        $logEntries = $em->getRepository('LexingAssistBundle:LogEntry')->getRecentLogEntries();
        return [
            'logEntries' => $logEntries
        ];
    }

    /**
     * 注意事项
     * 1.assist/deploy/onpush必须可以匿名访问
     * 2.整个项目所有者需要是www-data，可以chown -R www-data:www-data project来设置
     * 3.为www-data生成ssh public key（sudo -u www-data ssh-keygen -t rsa），将id_rsa.pub添加到git的deploy keys中
     * 4.sudo -u www-data git pull 建立和gitlab的认证
     *
     * @Route("/deploy/onpush", name="lx_assist_deploy_onpush")
     */
    public function onpushAction(Request $req)
    {
        $env = $this->get('kernel')->getEnvironment();
        $ip = $req->getClientIp();
        if ($env != 'dev' && ($ip != '121.43.158.141' || $req->headers->get('X-Gitlab-Event') != 'Push Hook')) {
            throw $this->createAccessDeniedException('illegal access');
        }
        $logger = $this->get('logger');
        $projectDir = dirname($this->get('kernel')->getRootDir());
        $precheck   = shell_exec("cd $projectDir && git reset --hard HEAD && git pull 2>&1");
        $logger->error('deploy precheck: ' . $precheck);
        $toexec     = "cd $projectDir";
        $updateSchema = 'php bin/console doctrine:schema:update --force';
        if (strpos($precheck, 'Entity') !== false) {
            // 只要有entity发生变化就需要更新数据库（不检查是否是字段）
            $toexec .= " && $updateSchema";
        }
        $toexec .= ' && php bin/console --env=prod cache:clear && chmod -R 777 var/';
        $output = shell_exec($toexec);
        $logger->error('deploy: ' . $output); // test deploy finally
        return new Response($output);
    }

    private function makeSign($data)
    {
        ksort($data);
        unset($data['signature']); // @todo sign or ?
        $string = array_reduce(array_keys($data), function($carry, $key) use ($data) {
            if ($data[$key] != '' && !is_array($data[$key])) {
                return $carry .= $key . '=' . $data[$key] . '&';
            }
            return $carry;
        });
        $string = $string . 'token=lexing2017';
        return strtoupper(sha1($string));
    }

    private function getNonceStr($length = 8)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     *
     * @Route("/wpos", name="lx_assist_wpos")
     */
    public function wposAction(Request $req)
    {
        $data = [
            'out_trade_no' => 'test0123456789',
            'body' => 'test下单',
            'total_fee' => 1,
            //'notify_url' => 'http://test.weipasss.cn/pay/notify',
            // 'attach' => '',
        ];
        $params = [
            'access_token' => '58eb5eecee3c200780009402',
            'service' => 'cashier.api.order',
            'timestamp' => time(),
            'mcode' => '193922',
            'nonce' => $this->getNonceStr(),
            'device_en' => '1438ae1a',
            'data' => json_encode($data),
        ];
        $params['signature'] = $this->makeSign($params);
        $url = 'http://open.wangpos.com/wopengateway/api/entry';
        $c = curl_init();

        $paramStr = array_reduce(array_keys($params), function($carry, $key) use ($params) {
            if ($params[$key] != '' && !is_array($params[$key])) {
                return $carry .= $key . '=' . $params[$key] . '&';
            }
            return $carry;
        });
        $url .= "?$paramStr";
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        //curl_setopt($c, CURLOPT_POSTFIELDS, $params);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($c);
        dump($response);exit;
    }
}

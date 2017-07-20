<?php

namespace Nnv\WxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * 
 * 1.Access Token获取接口
 * http://---/wx/api/token?secret=nnvwx2015
 * 2.JSSDK jsapiticket获取接口
 * http://---/wx/api/jsapiticket
 * 3.网页授权接口
 * http://---/wx/api/auth?url=http://nnv.io&type=userinfo
 * 参数：
 * url - 回调地址
 * type - userinfo|base
 * 
 * @Route("/wx/api")
 */
class ApiController extends Controller
{
    /**
     * 获取AccessToken
     * @Route("/token", name="nnv_wx_api_token")
     */
    public function tokenAction(Request $req)
    {
        if ($req->get('secret') != 'nnvwx2015') {
            die('fail');
        }
        $wxHelper = $this->get('nnv_wx.helper');
        return new Response($wxHelper->getWxAccessToken());
    }
    
    /**
     * 
     * 获取JSSDK apiticket
     * @Route("/jsapiticket", name="nnv_wx_api_jsapiticket")
     */
    public function jsapiticketAction()
    {
        $jssdk = $this->get('nnv_wx.jssdk');
        return new Response($jssdk->getJsApiTicket());
    }
    
    /**
     * 
     * 授权
     * @Route("/auth", name="nnv_wx_api_auth")
     */
    public function authAction(Request $req)
    {
        $type = $req->get('type', 'userinfo'); // userinfo or base
        if (!in_array($type, ['userinfo', 'base'])) {
            die('fail');
        }
        $callback = $req->get('url');
        if (!$callback) {
            die('fail');
        }
        $wxhelper = $this->get('lmyhome_wx.helper');
        $authUrl = $wxhelper->getAuthUrl($this->generateUrl('nnv_wx_api_authback', ['callback' => $callback, 'type' => $type], true), $type);
        return $this->redirect($authUrl);
    }
    
    /**
     * 
     * 授权回调
     * @Route("/authback", name="nnv_wx_api_authback")
     */
    public function authbackAction(Request $req)
    {
        $code = $req->get('code');
        $callback = $req->get('callback');
        $type = $req->get('type');
        $wxhelper = $this->get('nnv_wx.helper');
        $ret = $wxhelper->getUserInfo($code, $type);
        if ($ret === false) {
            return $this->redirect($callback);
        }
        $retstr = urlencode(is_array($ret) ? json_encode($ret) : $ret);
        $callback .= strpos($callback, '?') !== false ? "&result=$retstr" : "?result=$retstr";
        return $this->redirect($callback);
    }
}
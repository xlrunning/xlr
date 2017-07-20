<?php

namespace Nnv\WxBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class WxJSSDK {
    /**
     * 
     * @var ContainerInterface
     */
    private $container;
    
    private $appId;
    private $appSecret;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->appId = $this->container->getParameter('wx_appid');
        $this->appSecret = $this->container->getParameter('wx_appsecret');
    }

    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();
        if (!$jsapiTicket) {
            return null;
        }
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function getJsApiTicket()
    {
        $kernel = $this->container->get('kernel');
        $cacheDir = $kernel->getCacheDir();
        $path = $cacheDir . '/jsapi_ticket.json';
        $initData = new \stdClass();
        $initData->expire_time = 0;
        $initData->jsapi_ticket = '';
        $data = file_exists($path) ? json_decode(file_get_contents($path)) : $initData;
        if ($data->expire_time < time()) {
            $accessToken = $this->getAccessToken();
            if ($accessToken === false) {
                return false;
            }
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            if (isset($res->errcode) && $res->errcode) {
                return false;
            }
            $ticket = $res->ticket;
            if ($ticket) {
                $data->expire_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $fp = fopen($path, "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }
    
    private function getAccessToken()
    {
        $wxHelper = $this->container->get('nnv_wx.helper');
        return $wxHelper->getWxAccessToken();
    }
    
    private function httpGet($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

}

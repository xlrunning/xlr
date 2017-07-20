<?php

namespace Nnv\WxBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;

class WxHelper
{
    /**
     * 
     * @var ContainerInterface
     */
    private $container;
    
    /**
     * Method inject service container
     * 
     * @param ConatainerInterface $container
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    public function isInWechat()
    {
        $req = $this->container->get('request_stack')->getCurrentRequest();
        $userAgent = $req->server->get('HTTP_USER_AGENT');
        return strpos($userAgent, 'MicroMessenger') !== false;
    }
    
    private function getRemoteAccessToken()
    {
        // @todo reafactor config to nnv?
        $wxAppid = $this->container->getParameter('wx_appid');
        $wxSecret = $this->container->getParameter('wx_appsecret');
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $wxAppid . '&secret=' . $wxSecret;
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url); 
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        $accessToken = $retArr['access_token'];
        $ttl = intval($retArr['expires_in']);
        if ($ttl == 0) {
            $ttl = 7000;
        } else {
            $ttl -= 200;
        }
        curl_close($c);
        
        return ['token' => $accessToken, 'ttl' => $ttl];
    }
    
    /**
     * 返回微信公众号access_token
     * 
     * @return string
     */    
    public function getWxAccessToken()
    {
        $kernel = $this->container->get('kernel');
        $cacheDir = $kernel->getCacheDir();
        $path = $cacheDir . '/access_token.json';
        $initData = new \stdClass();
        $initData->expire_time = 0;
        $initData->access_token = '';
        $data = file_exists($path) ? json_decode(file_get_contents($path)) : $initData;
        if ($data->expire_time < time()) {
            $arr = $this->getRemoteAccessToken();
            $accessToken = $arr['token'];
            if ($accessToken) {
                $data->expire_time = time() + $arr['ttl'];
                $data->access_token = $accessToken;
                $fp = fopen($path, "w");
                fwrite($fp, json_encode($data));
                fclose($fp);
            }
        } else {
            $accessToken = $data->access_token;
        }
        return $accessToken;
    }
    
    /**
     * 发送模板消息
     * 消费通知
     * 
     * @param string $openid
     * @param string $tplId
     * @param array $msgArr
     * @return boolean
     */    
    public function sendTplMsg($openid, $tplId, $msgArr, $url = null)
    {
        $env = $this->container->get('kernel')->getEnvironment();
        if ('dev' == $env) {
            return true;
        }
        
        $postData = [
            'touser' => $openid,
            'template_id' => $tplId,
            'url' => $url,//$this->container->get('router')->generate('incocktail_user_profile', ['openid' => $openid], true),
            'topcolor' => '#FF0000'
        ];
        
        foreach ($msgArr as $key=>$val) {
            $postData['data'][$key] = [
                'color' => '#173177',
                'value' => $val
            ];
        }
        
        $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $this->getWxAccessToken();
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url); 
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        curl_close($c);
        
        $result = empty($retArr['errcode']);
        if (!$result) {
            $logger = $this->container->get('logger');
            $logger->error('--tplmsg err:' . $ret);
        }
        return $result;
    }
    
    public function getAuthUrl($redirectUri, $scope)
    {
        if (!in_array($scope, ['base', 'userinfo'])) {
            die('scope must be base or userinfo');
        }
        
        $wxscope = $scope == 'base' ? 'snsapi_base' : 'snsapi_userinfo';
        $wxAppid = $this->container->getParameter('wx_appid');
        $redirectUri = urlencode($redirectUri);
        $authUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . $wxAppid .'&redirect_uri=' . $redirectUri . '&response_type=code&scope=' . $wxscope . '&state=1#wechat_redirect';
        return $authUrl;
    }
    
    /**
     * 获取微信用户信息
     * 
     * @param string $route
     * @param string $scope 'base'|'userinfo'
     * @return array|string|boolean
     */
    public function getUserInfo($code, $scope)
    {
        if (!in_array($scope, ['base', 'userinfo'])) {
            die('scope must be base or userinfo');
        }
        
        if ($this->container->get('kernel')->getEnvironment() == 'dev') {
            if ($scope == 'base') {
                return '123458888';
            }
            return ['openid' => '123458888', 'nick' => 'Jo', 'avatar' => ''];
        }
        
        $wxAppid = $this->container->getParameter('wx_appid');
        $wxSecret = $this->container->getParameter('wx_appsecret');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
        $url .= 'appid=' . $wxAppid . '&secret=' . $wxSecret;
        $url .= '&code=' . $code . '&grant_type=authorization_code';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url); 
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        curl_close($c);
        
//        $logger = $this->container->get('logger');
//        $logger->error('----auth:' . $ret);
        
        if ($scope == 'base') {
            return isset($retArr['openid']) ? $retArr['openid'] : false;
        } else if (!isset($retArr['access_token']) || !isset($retArr['openid'])) {
            return false;
        }
        
        $accessToken = $retArr['access_token'];
        $openid = $retArr['openid'];
        $url2 = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
        $c2 = curl_init();
        curl_setopt($c2, CURLOPT_URL, $url2); 
        curl_setopt($c2, CURLOPT_RETURNTRANSFER, TRUE);
        $ret2 = curl_exec($c2);
        $retArr2 = json_decode($ret2, true);
        curl_close($c2);
        
        return array(
            'nick'   => $retArr2['nickname'],
            'avatar' => $retArr2['headimgurl'],
            'openid' => $openid
        );
    }
    
    public function replyText($wxSignal, $username, $text, $ret = false)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    </xml>";
                    
        $resultStr = sprintf($textTpl, $username, $wxSignal, time(), $text);
        if ($ret) {
            return $resultStr;
        } else {
            echo $resultStr;
        }
    }
    
    public function replyImage($wxSignal, $username, $mediaId)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Image>
                    </xml>";
                    
        $resultStr = sprintf($textTpl, $username, $wxSignal, time(), $mediaId);
        echo $resultStr;
    }
    
    public function replyImageText($wxSignal, $username, $title, $description, $picUrl, $url)
    {
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                        <item>
                        <Title><![CDATA[%s]]></Title> 
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                        </item>
                    </Articles>
                    </xml>";
        $resultStr = sprintf($textTpl, $username, $wxSignal, time(), $title, $description, $picUrl, $url);
        echo $resultStr;
    }
    
    /**
     * 
     * @param string $wxSignal
     * @param string $username
     * @param string $articles array(title, description, picUrl, url)
     */
    public function replyImageTexts($wxSignal, $username, $articles)
    {   
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[news]]></MsgType>
                    <ArticleCount>%d</ArticleCount>
                    <Articles>";
        $itemTpl = "<item>
                        <Title><![CDATA[%s]]></Title> 
                        <Description><![CDATA[%s]]></Description>
                        <PicUrl><![CDATA[%s]]></PicUrl>
                        <Url><![CDATA[%s]]></Url>
                    </item>";
        $resultStr = sprintf($textTpl, $username, $wxSignal, time(), count($articles));
        foreach ($articles as $article) {
            $resultStr .= sprintf($itemTpl, $article['title'], $article['description'], $article['picUrl'], $article['url']);
        }
        $resultStr .=  '</Articles></xml>';
        
        echo $resultStr;
    }
    
    public function activate()
    {
        $req = $this->container->get('request_stack')->getCurrentRequest();
        $signature = $req->get('signature');
        $timestamp = $req->get('timestamp');
        $nonce = $req->get('nonce');
        $token = $this->container->getParameter('wx_token');
        
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            echo $req->get('echostr');
        }
    }
    
    /**
     * 永久二维码
     * 
     * @param string $sceneStr
     * @param boolean $reset
     * @return boolean
     */
    public function createSceneQR($sceneStr, $reset = false)
    {
        $rootDir = $this->container->getParameter('kernel.root_dir');
        $qrscenePath = $rootDir . '/../web/qrscenes/' . $sceneStr . '.jpg';
        if (file_exists($qrscenePath) && !$reset) {
            return true;
        }
        
        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->getWxAccessToken();
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url); 
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": ' . $sceneStr . '}}}');
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        curl_close($c);
        if (!isset($retArr['ticket'])) {
            return false;
        }
        $ticket = $retArr['ticket'];
        $qrsceneImageUrl = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket;
        return file_put_contents($qrscenePath, file_get_contents($qrsceneImageUrl)) != false;
    }
    
    /**
     * 返回关注者信息,无关注则为空
     * 
     * @param stirng $openid
     * @return array|null
     */
    public function querySubscriberInfo($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?lang=zh_CN&access_token=' . $this->getWxAccessToken() . '&openid=' . $openid;
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json'
        ));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        curl_close($c);
        
        if (isset($retArr['subscribe']) && $retArr['subscribe']) {
            return $retArr;
        }
        $logger = $this->container->get('logger');
        $logger->error('-- query subscriber failed:' . $ret);
        return null;
    }
    
    /**
     * 
     * @param string $openid
     * @param array $articles
     * @return boolean
     */
    public function kefuSendMsg($openid, $articles)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $this->getWxAccessToken();
        
        $postData = [
            'touser' => $openid,
            'msgtype' => 'news',
            'news' => [ 'articles' => $articles ]
        ];
        
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, urldecode(json_encode($postData)));
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        //$retArr = json_decode($ret, true);
        curl_close($c);
        
        return true;
    }
    
    /**
     * 
     * @param string $path
     * @return string|null
     */
    public function newMedia($path)
    {
        $c = curl_init();
        
        if (function_exists('curl_file_create')) { // php 5.6+
            $cFile = curl_file_create($path);
        } else { // 
            $cFile = '@' . realpath($path);
        }
        $data = ['media' => $cFile];
        
        $api = 'https://api.weixin.qq.com/cgi-bin/media/upload?type=image&access_token=' . $this->getWxAccessToken();
        curl_setopt($c, CURLOPT_URL, $api);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($c, CURLOPT_POST, TRUE);
        curl_setopt($c, CURLOPT_POSTFIELDS, $data); 
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        curl_close($c);
        
        if (isset($retArr['media_id'])) {
            return $retArr['media_id'];
        }
        
        $logger = $this->container->get('logger');
        $logger->error('-- newmedia err:' . $path . ', ' . $ret);
        return null;
    }
}
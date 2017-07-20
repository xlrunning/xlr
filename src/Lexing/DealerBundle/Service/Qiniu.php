<?php
/**
 * Created by PhpStorm.
 * User: EasyChris<chris@afox.cc>
 * Date: 2017/4/21
 * Time: 下午5:17
 */

/**
 * 操作跟七牛有关的服务
 */

namespace Lexing\DealerBundle\Service;

use Qiniu\Auth;

class Qiniu
{
    private $accessKey = '';
    private $secretKey = '';
    private $bucketName = '';
    public function __construct($accessKey, $secretKey, $bucketName)
    {
        $this->accessKey = $accessKey;
        $this->secretKey = $secretKey;
        $this->bucketName = $bucketName;
    }

    /**
     * 返回前端上传图片需要的七牛Token
     *
     * @return string
     */
    public function getToken()
    {
        $auth = new Auth($this->accessKey, $this->secretKey);
        $token = $auth->uploadToken($this->bucketName);
        return $token;
    }

}
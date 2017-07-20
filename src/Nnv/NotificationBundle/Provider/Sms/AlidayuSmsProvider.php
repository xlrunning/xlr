<?php

namespace Nnv\NotificationBundle\Provider\Sms;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class AlidayuSmsProvider
 */
class AlidayuSmsProvider implements SmsProviderInterface
{
    use ContainerAwareTrait;

    private $config;

    public function setConfig(array $config)
    {
        // @todo validate
        $this->config = $config;
    }

    protected function generateSign($params)
    {
        ksort($params);
        $secretKey = $this->config['secret'];
        $stringToBeSigned = $secretKey;
        foreach ($params as $k => $v)
        {
            if(is_string($v) && "@" != substr($v, 0, 1))
            {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $secretKey;

        return strtoupper(md5($stringToBeSigned));
    }

    /**
     *
     * @todo type
     * @param array $smsConfig
     * @param array $vars
     * @param string|array $mobile
     */
    public function sendSms($smsConfig, $vars, $mobile)
    {
        $aliMethod = 'alibaba.aliqin.fc.sms.num.send';
        $smsParams = ['code' => $vars['code']];
        $params = [
            'app_key' => ''.$this->config['appkey'],
            'format' => 'json',
            'partner_id' => 'nnv',
            'method' => $aliMethod,
            'sign_method' => 'md5',
            'timestamp' => date('Y-m-d H:i:s'),
            'v' => '2.0',
            'rec_num' => $mobile,
            'sms_free_sign_name' => $smsConfig['sign_name'],
            'sms_param' => json_encode($smsParams),
            'sms_template_code' => $smsConfig['tpl_id'],
            'sms_type' => 'normal'
        ];
        $params['sign'] = $this->generateSign($params);

        $url = 'http://gw.api.taobao.com/router/rest';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, 1);
        //curl_setopt($c, CURLOPT_HTTPHEADER, ['content-type: application/x-www-form-urlencoded; charset=UTF-8']);
        curl_setopt($c, CURLOPT_POSTFIELDS, $params);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        curl_close($c);
        dump($retArr);exit;
        // $retArr[alibaba_aliqin_fc_sms_num_send_response]//error_response
        // $retArr[alibaba_aliqin_fc_sms_num_send_response]['result'] // err_code 0, success
        return $retArr;
    }
}
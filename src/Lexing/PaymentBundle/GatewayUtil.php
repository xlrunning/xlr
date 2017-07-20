<?php

namespace Lexing\PaymentBundle;

use Symfony\Component\Serializer\Encoder\XmlEncoder;

/**
 * Class GatewayUtil
 * @package Lexing\PaymentBundle
 */
class GatewayUtil
{
    public static function makeSign(array $data, $key = null, $keyVal = null)
    {
        ksort($data);
        unset($data['sign']); // @todo sign or ?
        $string = array_reduce(array_keys($data), function($carry, $key) use ($data) {
            if ($data[$key] != '' && !is_array($data[$key])) {
                return $carry .= $key . '=' . $data[$key] . '&';
            }
            return $carry;
        });
        $string = $string . ($key ? "$key=$keyVal" : '');
        return strtoupper(md5($string));
    }

    public static function checkSign($sign, array $data, $key = null, $keyVal = null)
    {
        return $sign == static::makeSign($data, $key, $keyVal);
    }

    public static function getNonceStr($length = 16)
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
     * @param string $url
     * @param array $data
     * @return array
     */
    public static function postData($url, array $data, $format = 'xml')
    {
        if (!isset($data['sign'])) {
            // make sign
        }

        if ($format == 'xml') {
            $xmlEncoder = new XmlEncoder('xml');
            $postBody = $xmlEncoder->encode($data, 'xml');
        } else if ($format == 'json') {
            $postBody = json_encode($data);
        } else {
            // exception
        }

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $postBody);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($c);

        if ($format == 'xml') {
            return $xmlEncoder->decode($response, 'xml');
        } else if ($format == 'json') {
            return json_decode($data, true);
        }
    }
}
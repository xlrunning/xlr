<?php

namespace Lexing\DeviceBundle\Service;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class DeviceSignHelper
{
    use ContainerAwareTrait;

    public function makeSign(array $params, $secret)
    {
        ksort($params);
        unset($params['token']);
        $string = array_reduce(array_keys($params), function($carry, $key) use ($params) {
            if ($params[$key] != '' && !is_array($params[$key])) {
                return $carry .= $key . '=' . $params[$key] . '&';
            }
            return $carry;
        });
        $string = $string . 'secret=' . $secret; // @todo
        return strtoupper(md5($string));
    }

    public function checkSign($receivedSign, $ourSign)
    {
        return $receivedSign === $ourSign;
    }
}
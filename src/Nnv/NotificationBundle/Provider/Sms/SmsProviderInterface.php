<?php

namespace Nnv\NotificationBundle\Provider\Sms;

interface SmsProviderInterface
{
    public function setConfig(array $config);

    public function sendSms($smsConfig, $vars, $mobile);
}
<?php

namespace Nnv\NotificationBundle\Provider;

use Nnv\NotificationBundle\Provider\Sms\SmsProviderInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

/**
 * Class SmsProviderManager
 * @package Nnv\NotificationBundle\Provider
 */
class SmsProviderManager
{
    private $providers = [];

    /**
     *
     * @param string $name
     * @param SmsProviderInterface $provider
     */
    public function addProvider($name, SmsProviderInterface $provider)
    {
        $this->providers[$name] = $provider;
    }

    public function getProviders()
    {

    }

    /**
     * @param $tplId
     * @param $vars
     * @param $mobile
     * @param string $serviceName
     * @return bool
     */
    public function sendSms($tplId, $vars, $mobile, $serviceName = null)
    {
        if (empty($this->providers)) {
            throw new ServiceNotFoundException('no sms provider service');
        }
        if ($serviceName && !isset($this->providers[$serviceName])) {
            throw new ServiceNotFoundException("sms provider service $serviceName not found");
        }
        $provider = array_values($this->providers)[0];
        $provider->sendSms($tplId, $vars, $mobile);
        return true;
    }
}
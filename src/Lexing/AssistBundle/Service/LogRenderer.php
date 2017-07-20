<?php

namespace Lexing\AssistBundle\Service;

use Lexing\AssistBundle\Entity\LogEntry;
use Lexing\DealerBundle\Entity\VehicleDealer;
use Lexing\LoanBundle\Entity\VehicleMortgage;
use Lexing\VehicleBundle\Entity\Vehicle;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LogRenderer
 * @package Lexing
 */
class LogRenderer
{
    /**
     *
     * @var ContainerInterface
     */
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    private function createLink($route, $object)
    {
        return sprintf(
            '<a target="_blank" href="%s">%s</a>',
            $this->container->get('router')->generate($route, ['id' => $object->getId()]),
            strval($object)
        );
    }

    /**
     *
     * @param LogEntry $logEntry
     * @param boolean  $withUser - NOT SUPPORTED YET
     * @return string
     */
    public function render($logEntry, $withUser = false)
    {
        $data = $logEntry->getData();
        $object = $logEntry->getObject();

        // 通过logEntry和object发现具体动作
        if ($object instanceof Vehicle) {
            if (isset($data['onSale'])) {
                return ($data['onSale'] ? '上架了' : '下架了') . ' ' . $this->createLink('admin_lexing_vehicle_vehicle_show', $object);
            }
        }

        if ($object instanceof VehicleDealer) {
            if (isset($data['creditExtensionAmount'])) {
                return '授信了' . $data['creditExtensionAmount'] . '元给' . $this->createLink('admin_lexing_dealer_vehicledealer_show', $object);
            }
        }

        if ($object instanceof VehicleMortgage) {
            $vehicle = $object->getVehicle();
            if (!$vehicle) {
                return null;
            }
            $dealer  = $vehicle->getDealer();
            if (!$dealer) {
                return null;
            }
            $dealerLink = $this->createLink('admin_lexing_dealer_vehicledealer_show', $dealer);
            $vehicleLink = $this->createLink('admin_lexing_vehicle_vehicle_show', $vehicle);
            if (isset($data['amount']) && !isset($data['repaidAt'])) {
                return '借款' . $data['amount'] . '元给' . $dealerLink . '的车辆' . $vehicleLink;
            }
            if (!isset($data['amount']) && isset($data['repaidAt'])) {
                return '确认' . $dealerLink . '还款 "金额元" 解押车辆' . $vehicleLink;
            }
        }

        return null;
    }
}
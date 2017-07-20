<?php

namespace Lexing\TradeBundle\Content;

use Lexing\TradeBundle\Entity\Trade;
use Lexing\TradeBundle\Entity\VehicleSale;
use Lexing\TradeBundle\Payment\Payment;
use Lexing\VehicleBundle\Entity\Vehicle;

/**
 * Class TradeVehicleSale
 * @package Lexing\TradeBundle\Content
 */
class TradeVehicleSaleContent implements TradeContentInterface
{
    /**
     * 定金、首付等
     * @var string
     */
    private $type;

    /**
     * @var Vehicle
     */
    private $vehicle;

    /**
     *
     * @param Vehicle $vehicle
     * @param $type
     */
    public function __construct(Vehicle $vehicle, $type)
    {
        $this->vehicle  = $vehicle;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle $vehicle
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;
    }
}
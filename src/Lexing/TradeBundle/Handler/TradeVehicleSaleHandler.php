<?php

namespace Lexing\TradeBundle\Handler;

use Lexing\TradeBundle\Content\TradeVehicleSaleContent;
use Lexing\TradeBundle\Entity\Trade;
use Lexing\TradeBundle\Entity\VehicleSale;

/**
 * Class TradeVehicleSaleHandler
 * @package Lexing\TradeBundle\Handler
 */
class TradeVehicleSaleHandler extends TradeHandler
{
    public function getContentClass()
    {
        return TradeVehicleSaleContent::class;
    }

    public function getContentTransformer()
    {
        return $this->container->get('lexing_trade.vehicle_sale_transformer');
    }

    public function onCreate(Trade $trade)
    {
        // 确认相关设置都有, targetIdentifier, metaData.type
    }

    public function onPaid(Trade $trade)
    {
        $tradeContent = $trade->getContentData();
        $vehicleSaleContent = $this->getContentTransformer()->reverseTransform($tradeContent);
        $em = $this->container->get('doctrine.orm.entity_manager');
        $sale = new VehicleSale();
        $sale->setTrade($trade)
            ->setVehicle($vehicleSaleContent->getVehicle())
            ->setType($vehicleSaleContent->getType());
        $em->persist($sale);
        $em->flush();

        // dispatch event
    }
}
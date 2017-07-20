<?php

namespace Lexing\TradeBundle\Content\Transformer;

use Lexing\TradeBundle\Content\TradeContentInterface;
use Lexing\TradeBundle\Content\TradeVehicleSaleContent;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Class VehicleSaleTransformer
 * @package Lexing\TradeBundle\Content\Transformer
 */
class VehicleSaleTransformer implements TradeContentTransformerInterface
{
    use ContainerAwareTrait;

    /**
     * @param TradeContentInterface $tradeContent
     * @return array
     */
    public function transform(TradeContentInterface $tradeContent)
    {
        return [
            'type' => $tradeContent->getType(),
            'vehicle' => $tradeContent->getVehicle()->getId(),
        ];
    }

    /**
     *
     * @param array $contentData
     * @return TradeContent
     */
    public function reverseTransform(array $contentData)
    {
        // assertion
        $em = $this->container->get('doctrine.orm.entity_manager');
        $id = $contentData['vehicle'];
        $vehicle = $em->getRepository('LexingVehicleBundle:Vehicle')->find($id);
        return new TradeVehicleSaleContent($vehicle, $contentData['type']);
    }
}
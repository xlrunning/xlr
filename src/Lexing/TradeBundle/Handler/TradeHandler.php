<?php

namespace Lexing\TradeBundle\Handler;

use Lexing\TradeBundle\Content\Transformer\TradeContentTransformerInterface;
use Lexing\TradeBundle\Entity\Trade;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * class TradeHandler
 *
 * 对交易进行处理，用于构造业务相关的交易
 *
 * @package Lexing\TradeBundle\Handler
 */
abstract class TradeHandler
{
    /**
     * 保存到trade.type中
     * @var string
     */
    protected $type;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
     * @return string
     */
    public abstract function getContentClass();

    /**
     *
     * @return TradeContentTransformerInterface
     */
    public abstract function getContentTransformer();

    /**
     * 会作为event listener加入到trade的lifecycle中
     *
     * @param Trade $trade
     * @return mixed
     */
    public abstract function onCreate(Trade $trade);

    /**
     * 会作为event listener加入到trade的lifecycle中
     *
     * @param Trade $trade
     * @return mixed
     */
    public abstract function onPaid(Trade $trade);
}
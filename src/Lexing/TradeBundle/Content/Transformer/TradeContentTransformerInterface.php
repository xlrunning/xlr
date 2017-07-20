<?php

namespace Lexing\TradeBundle\Content\Transformer;

use Lexing\TradeBundle\Content\TradeContentInterface;

/**
 * TradeContentTransformer
 */
interface TradeContentTransformerInterface
{
    /**
     * @param TradeContentInterface $tradeContent
     * @return array
     */
    public function transform(TradeContentInterface $tradeContent);

    /**
     * @param array $contentData
     * @return TradeContent
     */
    public function reverseTransform(array $contentData);
}
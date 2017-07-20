<?php

namespace Lexing\AssistBundle\SonataBlock;

use Symfony\Component\HttpFoundation\Response;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\BlockBundle\Block\Service\AbstractBlockService;

/**
 * 
 * 
 * @author kail
 */
class AssistBlockService extends AbstractBlockService
{
    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = NULL)
    {
        $settings = array_merge($this->getDefaultSettings(), $blockContext->getSettings());
        
        return $this->renderResponse('LexingAssistBundle:Assist:sonatablock.html.twig', array(
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Assist';
    }

    /**
     * {@inheritdoc}
     */
    function getDefaultSettings()
    {
        return array(
            'content' => '各种统计...',
        );
    }
}
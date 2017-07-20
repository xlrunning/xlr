<?php

namespace Lexing\AssistBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class LogTwigExtension extends \Twig_Extension
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
    
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('log_render', array($this, 'renderLogFunc')),
        );
    }
    
    public function renderLogFunc($logEntry)
    {
        return $this->container->get('lexing_log.render')->render($logEntry);
    }

    public function getName()
    {
        return 'log_twig_extension';
    }
}

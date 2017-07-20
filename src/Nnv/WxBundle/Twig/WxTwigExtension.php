<?php

namespace Nnv\WxBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class WxTwigExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('is_inwx', array($this, 'isInWxFunc')),
            new \Twig_SimpleFunction('wxsignpackage', array($this, 'wxsignpackageFunc')),
        );
    }
    
    public function wxsignpackageFunc()
    {
        if ($this->container->get('nnv_wx.helper')->isInWechat()) {
            return $this->container->get('nnv_wx.jssdk')->getSignPackage();
        }
        
        return null;
    }
    
    public function isInWxFunc()
    {
        return $this->container->get('nnv_wx.helper')->isInWechat();
    }

    public function getName()
    {
        return 'wx_twig_extension';
    }
}

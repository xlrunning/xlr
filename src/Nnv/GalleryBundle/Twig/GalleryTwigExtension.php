<?php

namespace Nnv\GalleryBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GalleryTwigExtension extends \Twig_Extension
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
            new \Twig_SimpleFunction('gallery_notattached', array($this, 'galleryNotAttachedFunc')),
        );
    }
    
    public function galleryNotAttachedFunc($code)
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $gallery = $em->getRepository('NnvGalleryBundle:Gallery')->findOneBy([
            'code' => $code,
            'attached' => false
        ]);
        return $gallery;
    }

    public function getName()
    {
        return 'gallery_twig_extension';
    }
}

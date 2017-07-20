<?php

namespace Nnv\GalleryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NnvGalleryExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $bundles = $container->getParameter('assetic.bundles');
        $bundles[] ='NnvGalleryBundle';
        $container->setParameter('assetic.bundles', $bundles);
        
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new Loader\XmlFileLoader($container, $fileLocator);
        $loader->load('services.xml');
        
        $yamlLoader = new Loader\YamlFileLoader($container, $fileLocator);
        $yamlLoader->load('services.yml');
        
        $container->getDefinition('nnv_gallery.helper')
            ->addArgument($config['types']);
    }
}

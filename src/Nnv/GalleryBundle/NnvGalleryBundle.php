<?php

namespace Nnv\GalleryBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nnv\GalleryBundle\DependencyInjection\Compiler\GlobalVariablesCompilerPass;

class NnvGalleryBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GlobalVariablesCompilerPass());
    }
}

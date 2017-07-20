<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            
            new FOS\UserBundle\FOSUserBundle(),
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Sonata\CoreBundle\SonataCoreBundle(),
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            
            new Nnv\FormBundle\NnvFormBundle(),
            #new Nnv\AreaBundle\NnvAreaBundle(),
            #new Nnv\WxBundle\NnvWxBundle(),
            new Nnv\GalleryBundle\NnvGalleryBundle(),
            new Nnv\TaxonomyBundle\NnvTaxonomyBundle(),
            new Nnv\NotificationBundle\NnvNotificationBundle(),
            new Nnv\DoctrineBundle\NnvDoctrineBundle(),
            new Nnv\UserBundle\NnvUserBundle(),
            # new Nnv\TradeBundle\NnvTradeBundle(),
            
            new Lexing\UserBundle\LexingUserBundle(),
            new Lexing\MartBundle\LexingMartBundle(),
            new Lexing\DealerBundle\LexingDealerBundle(),
            new Lexing\VehicleBundle\LexingVehicleBundle(),
            new Lexing\XinBundle\LexingXinBundle(),
            new Lexing\AssistBundle\LexingAssistBundle(),
            new Lexing\SiteBundle\LexingSiteBundle(),
            new Lexing\SouCheBundle\LexingSouCheBundle(),
            new Lexing\WorkflowBundle\LexingWorkflowBundle(),
            new Lexing\TradeBundle\LexingTradeBundle(),
            new Lexing\PaymentBundle\LexingPaymentBundle(),
            new Lexing\DeviceBundle\LexingDeviceBundle(),
            new Lexing\LoanBundle\LexingLoanBundle(),
            new Lexing\ImportedCarBundle\LexingImportedCarBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    private function getVarParentDir()
    {
        return $this->getEnvironment() == 'prod' ? dirname(__DIR__) : sys_get_temp_dir() . '/' . basename(dirname(__DIR__));
    }

    public function getCacheDir()
    {
        return $this->getVarParentDir() . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir()
    {
        return $this->getVarParentDir(). '/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}

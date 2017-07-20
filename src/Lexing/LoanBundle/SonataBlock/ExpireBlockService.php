<?php

namespace Lexing\LoanBundle\SonataBlock;

use Sonata\BlockBundle\Block\Service\AbstractBlockService;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;


class ExpireBlockService extends AbstractBlockService
{
    use ContainerAwareTrait;

    private $expireDay = 7;

    /**
     * {@inheritdoc}
     */
    public function execute(BlockContextInterface $blockContext, Response $response = NULL)
    {
        $settings = array_merge($this->getDefaultSettings(), $blockContext->getSettings());

        $vehicleMortgages = $this->getExpireVehicleMortgages();

        return $this->renderResponse('LexingLoanBundle:Expire:sonatablock.html.twig', array(
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings,
            'vehicleMortgages' => $vehicleMortgages
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Expire';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'content' => '到期提示',
        );
    }

    private function getExpireVehicleMortgages()
    {
        $repository = $this->container->get('doctrine.orm.entity_manager')->getRepository('LexingLoanBundle:VehicleMortgage');
        $vehicleMortgages = $repository->findAll();
        $expireVehicleMortgages = [];
        foreach ($vehicleMortgages as $vehicleMortgage) {
            $createAt = $vehicleMortgage->getCreatedAt()->format('Y-m-d');
            $mustRepayAt = strtotime("$createAt + {$vehicleMortgage->getLoanProduct()->getPeriodByMonth()} month");
            if (($mustRepayAt - time()) < ($this->expireDay * 60 * 60 *24)) {
                $expireVehicleMortgages[] = $vehicleMortgage;
            }
        }
        return $expireVehicleMortgages;
    }
}
<?php

namespace Lexing\SouCheBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LexingDealerIdCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lexing:dealerId')
            ->setDescription('关联搜车和车商的id')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souArr = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        $souId = $this->getContainer()->getParameter('sou_id');
        foreach ($souArr as $sou){
            $getSouId = $sou->getStore();
            $key = array_search($getSouId,$souId);
            $sou->setDealer($key);
            $em->persist($sou);
        }
        $em->flush();
        echo 'done';
    }

}

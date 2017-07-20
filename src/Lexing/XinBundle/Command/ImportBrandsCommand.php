<?php

namespace Lexing\XinBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Lexing\VehicleBundle\Entity\VehicleBrand;

class ImportBrandsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lexing:import-brands')
            ->setDescription('import brands from xin')
//            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $argument = $input->getArgument('argument');
//
//        if ($input->getOption('option')) {
//            // ...
//        }

        $url = 'http://api.xin.com/brand/view';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        curl_close($c);

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $brand = $em->getRepository('LexingVehicleBundle:VehicleBrand')->find(1);
        if ($brand) {
            $output->writeln('brands already imported');
            exit;
        }
        if (!isset($retArr['data'])) {
            $output->writeln('xin api fail');
            exit;
        }
        foreach ($retArr['data'] as $initial=>$line) {
            foreach ($line as $brandArr) {
                if ($brandArr['brandid'] == 0) {
                    continue;
                }
                $brand = new VehicleBrand();
                $brand->setXinId($brandArr['brandid']);
                $brand->setName($brandArr['brandname']);
                $brand->setInitial($initial);
                $em->persist($brand);
            }
        }
        $em->flush();
        $output->writeln('done.');
    }

}

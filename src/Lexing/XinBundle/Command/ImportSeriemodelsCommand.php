<?php

namespace Lexing\XinBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Lexing\VehicleBundle\Entity\VehicleBrand;
use Lexing\VehicleBundle\Entity\VehicleSerie;
use Lexing\VehicleBundle\Entity\VehicleModel;

class ImportSeriemodelsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lexing:import-seriemodels')
            ->setDescription('import serie models from xin')
//            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }


    private function crawlSerieModels(VehicleBrand $brand)
    {
        $url = 'http://api.xin.com/serie/view?brandid=' . $brand->getXinId() . '&showmodel=1';
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        $ret = curl_exec($c);
        $retArr = json_decode($ret, true);
        curl_close($c);

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        if (!isset($retArr['data']['serie'])) {
            return false;
        }
        foreach ($retArr['data']['serie'] as $bymake) {
            $makeName = $bymake['makename'];
            $serielist = $bymake['serielist'];
            foreach ($serielist as $serieData) {
                $serie = new VehicleSerie();
                $serie->setBrand($brand)
                    ->setMakeName($makeName)
                    ->setXinId($serieData['serieid'])
                    ->setName($serieData['seriename']);
                foreach ($serieData['model'] as $modelsByYear) {
                    $year = $modelsByYear['year'];
                    $modelList = $modelsByYear['modellist'];
                    foreach ($modelList as $modelData) {
                        $model = new VehicleModel();
                        $model->setSerie($serie)
                            ->setYear(intval($year))
                            ->setName($modelData['modelname'])
                            ->setXinId($modelData['modelid']);
                        $em->persist($model);
                    }
                }
            }
        }
        $em->flush();
        // $em->clear();

        return true;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $brands = $em->getRepository('LexingVehicleBundle:VehicleBrand')->findAll();
        if (count($brands) == 0) {
            $output->writeln('import brands first!');
            exit;
        }
        foreach ($brands as $brand) {
            if (!$this->crawlSerieModels($brand)) {
                $output->writeln('xin api fail');
                exit;
            }
            $output->writeln("$brand done.");
        }
        // @todo some brands left

        $output->writeln('done.');
    }

}

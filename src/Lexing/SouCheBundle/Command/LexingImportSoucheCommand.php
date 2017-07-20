<?php

namespace Lexing\SouCheBundle\Command;

use Lexing\VehicleBundle\Entity\VehicleSou;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LexingImportSoucheCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lexing:import-souche')
            ->setDescription('import from souche APP')
//            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pageIndex = 1;
        $url = 'http://erp.souche.com/app/car/appcarsearchaction/searchCar.json?createDateEnd=&bodyModels=&platformType=AppStore&status=zaishou&province=&priceInterval=&gearBox=&purchaseType=&pageSize=10&assessor=&sort=dayUp&store=&brand=&listType=2&sellType=&city=&jpushid=141fe1da9e997d1407a&plateDateInterval=&tanGeCheState=&version=5.9.0&quanguoSearch=0&mileInterval=&userCarStatus=&model=&pageIndex=' . $pageIndex . '&emissionStandard=&carColor=&isUpshelf=&series=&createDateStart=&key=';
        $resArr = $this->souCurl($url);
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $totalPage = $resArr->data->totalPage;
        for ($i = 1; $i < $totalPage; $i++) {
            $url = 'http://erp.souche.com/app/car/appcarsearchaction/searchCar.json?createDateEnd=&bodyModels=&platformType=AppStore&status=zaishou&province=&priceInterval=&gearBox=&purchaseType=&pageSize=10&assessor=&sort=dayUp&store=&brand=&listType=2&sellType=&city=&jpushid=141fe1da9e997d1407a&plateDateInterval=&tanGeCheState=&version=5.9.0&quanguoSearch=0&mileInterval=&userCarStatus=&model=&pageIndex=' . $i . '&emissionStandard=&carColor=&isUpshelf=&series=&createDateStart=&key=';
            $resArr = $this->souCurl($url);
            $em = $this->getContainer()->get('doctrine.orm.entity_manager');
            $totalPage = $resArr->data->totalPage;
            $output->writeln("NO:" . $i . 'ready to save');
            if ($resArr->code == 200) {
                $i = 0;
                foreach ($resArr->data->items as $item) {
                    $souId = $item->id;
                    $result = $em->getRepository('LexingVehicleBundle:VehicleSou')->findBy(['souId'=>$souId]);
                    if (empty($result)){
                        $output->writeln("id" . $item->id);
                        $sou = new VehicleSou();
                        $sou->setSouId($item->id);
                        $sou->setMile($item->mile);
                        $sou->setName($item->name);
                        $sou->setOrderPrice(isset($item->orderPrice) ? $item->orderPrice : 0);
                        $sou->setPicUrl($item->picUrl);
                        $sou->setPlateTime(isset($item->plateTime) ? $item->plateTime : '0');
                        $sou->setPrice($item->price);
                        $sou->setSoucheNumber($item->soucheNumber);
                        $sou->setStatus($item->status);
                        $sou->setStore($item->store);
                        $sou->setStoreFullName($item->storeFullName);
                        $sou->setStoreName($item->storeName);
                        $sou->setSynchonousStr($item->synchonousStr);
                        $sou->setTags(json_encode($item->tags));
                        $sou->setUserDefinedStatus(isset($item->userDefinedStatus) ? $item->userDefinedStatus : 0);
                        $sou->setVin($item->vin);
                        $sou->setSaledTime($item->saledTime);
                        $sou->setStayDay($item->stayDay);
                        $em->persist($sou);
                        $i++;
                    }
                    if ($i==0){
                        echo '没有新的数据';
                        exit();
                    }
                }
                $em->flush();
                $output->writeln('add '.$i.'items');
                sleep(5);
            } else {
                $output->writeln('fail');
            }
        }
        $output->writeln('done all');

    }
    public function souCurl($url){
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($c, CURLOPT_HTTPHEADER, array("cookie: JSESSIONID=130EDCB53911B73FC52D74200F36DD42; _security_token=1487900116214281"));
        $ret = curl_exec($c);
        $resArr = json_decode($ret);
        curl_close($c);
        return $resArr;
    }

}

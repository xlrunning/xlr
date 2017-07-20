<?php

namespace Lexing\SouCheBundle\Command;

use Lexing\VehicleBundle\Entity\EmbeddableModel;
use Lexing\VehicleBundle\Entity\Vehicle;
use Lexing\VehicleBundle\Entity\VehicleModel;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LexingImportVehicleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lexing:import-vehicle')
            ->setDescription('import sou to vehicle table')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->importVehicle();
    }

    /**
     * 将数据导入到vehicle表
     */
    public function importVehicle()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souArr = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souArr as $sou ){
            $sou->setIsImport(1);
            $vehicle = new Vehicle();
            $dealer = $em->getRepository('LexingDealerBundle:VehicleDealer')->findOneBy(['siteNo'=>$sou->getDealer()]);
            if (!empty($sou->getModelId())){
                $model = $em->getRepository('LexingVehicleBundle:VehicleModel')->find($sou->getModelId());
                $vehicle->setAssocModel($model)
                    ->setVin($sou->getVin())
                    ->setMileage($sou->getMile())
                    ->setAge($this->getCarAge($sou->getPlateTime()))
                    ->setInfo($this->setPrice($sou->getPrice()))
                    ->setDealer($dealer)
                    ->setPic($this->transPic($sou->getPic(),$sou))
                    ->setCover($this->getImageName($sou->getPicUrl()))
                    ->setSoucheModel($sou->getName());
                $vehicle->getModel()->setBrand($sou->getBrandName());
                $vehicle->getModel()->setName($model->getName());
                $vehicle->getModel()->setMake($sou->getMakeName());
                $vehicle->getModel()->setSerie($sou->getSerieName());
                $vehicle->getModel()->setYear($this->getYear($sou->getPlateTime()));
            }else{
                $vehicle->setVin($sou->getVin())
                    ->setMileage($sou->getMile())
                    ->setAge($this->getCarAge($sou->getPlateTime()))
                    ->setInfo($this->setPrice($sou->getPrice()))
                    ->setDealer($dealer)
                    ->setSoucheModel($sou->getName());
                $vehicle->getModel()->setBrand($sou->getBrandName())
                ;
                $vehicle->getModel()->setMake($sou->getMakeName());
                $vehicle->getModel()->setSerie($sou->getSerieName());
                $vehicle->getModel()->setYear($this->getYear($sou->getPlateTime()));
            }
            $em->persist($vehicle);
        }
        $em->flush();
        echo "import vehicle donw \n";
    }

    /**
     * @param string $plateTime 上牌时间
     * @return string           返回车龄
     */
    public function getCarAge($plateTime)
    {
        preg_match("/\b\d+/",$plateTime,$res);
        $res = intval(date('Y')) - intval($res[0]);
        return $res;
    }


    public function getYear($plateTime)
    {
        preg_match("/\b\d+/",$plateTime,$res);
        return $res[0];
    }

    public function setPrice($price)
    {
        $vehicle_keys = $this->getContainer()->getParameter('vehicle_keys');
        $priceParam = $vehicle_keys[0];
        $priceKeyValue = [
            'key'=>$priceParam,
            'val'=>$price,
        ];
        return [$priceKeyValue];
    }


    /**
     * 转换之前lx_vehicle_sou表中的搜车pic列表为数组
     *
     * @param $picArr
     * @param $sou
     *
     * @return array
     */
    public function transPic($picArr,$sou)
    {
        $pics = [];
        if (is_array($picArr)){
            foreach ($picArr as $pic){
                $picUrl = $pic['pictureBig'];
                $pics[] = $this->getImageName($picUrl);
            }
        }else{
            dump("图片集为空:".$sou->getId());
        }
        return $pics;
    }


    /**
     * 传入图片的UR，返回图片的名称
     *
     * @param string $picUrl
     *
     * @return mixed
     */
    public function getImageName($picUrl)
    {
        $names = mb_split('/',$picUrl);
        $name = $names[count($names)-1];
        return $name;
    }

}

<?php

namespace Lexing\SouCheBundle\Command;

use Assetic\Exception\Exception;
use Lexing\SouCheBundle\Entity\SouBrand;
use Lexing\SouCheBundle\Entity\SouModel;
use Lexing\SouCheBundle\Entity\SouSerie;
use Lexing\VehicleBundle\Entity\VehicleSou;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LexingDataCleanCommand extends ContainerAwareCommand
{
    public $cookie = ["cookie: JSESSIONID=130EDCB53911B73FC52D74200F36DD42; _security_token=1487900116214281"];
    public $dataFileDir;

    protected function configure()
    {
        $this
            ->setName('lexing:import')
            ->setDescription('data clean')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')//            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $argument = $input->getArgument('argument');
        switch ($argument) {
            case "brand":
                //为sou数据总表添加brandId
                $this->addBrand();
                break;
            case "brandName":
                //为sou数据总表添加brandId
                $this->addBrandName();
                break;
            case "serie":
                //为sou数据总表添加SerieId
                $this->addSerie();
                break;
            case "serieName":
                //为sou数据总表添加SerieName 和 serie的makeName
                $this->addSerieName();
                break;
            case "model":
                //为sou数据总表添加ModelId
                $this->addModel();
                break;
            case "model2":
                $this->addModel2();
                break;
            case "model3":
                $this->addModel3();
                break;
            case "importSerie":
                $this->importSerie();
                break;
            case "importModel":
                $this->importModel();
                break;
            case "mappingSerie":
                $this->mappingSerie();
                break;
            case "makeName":
                $this->addMakeName();
                break;
            case "year":
                $this->addYear();
                break;
            default :
                echo 'please input argument' . "\n";
        }
    }


    /**
     * 将导入的sou的数据加上brand的id
     */
    public function addBrand()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souObj = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souObj as $sou) {
            echo 'start id:' . $sou->getId() . "\n";
            $splitArr = mb_split('\s', $sou->getName());
            $brand = mb_split('[\u4e00-\u9fa5]*', $splitArr[0])[0];
            $query = $em->getRepository('LexingVehicleBundle:VehicleBrand')->createQueryBuilder('b')
                ->where('b.name like :name')
                ->setParameter('name', '%' . $brand . '%')
                ->getQuery();
            if ($query->getResult()) {
                $resId = $query->getResult()[0]->getId();
            } else {
                $resId = null;
                $this->saveFile("Error id:" . $sou->getId() . "\n", time() . '-brand.log', true);
            }
            $sou->setBrandId($resId);
            $em->persist($sou);
        }
        $em->flush();
        var_dump('done');
    }

    /**
     * 将导入的sou的数据加上brand的name
     */
    public function addBrandName()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souObj = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souObj as $sou) {
            echo 'start id:' . $sou->getId() . "\n";
            $splitArr = mb_split('\s', $sou->getName());
            $brand = mb_split('[\u4e00-\u9fa5]*', $splitArr[0])[0];
            $query = $em->getRepository('LexingVehicleBundle:VehicleBrand')->createQueryBuilder('b')
                ->where('b.name like :name')
                ->setParameter('name', '%' . $brand . '%')
                ->getQuery();
            if ($query->getResult()) {
                $resName = $query->getResult()[0]->getName();
            } else {
                $resId = null;
                $this->saveFile("Error id:" . $sou->getId() . "\n", time() . '-brand.log', true);
            }
            $sou->setBrandName($resName);
            $em->persist($sou);
        }
        $em->flush();
        var_dump('done');
    }


    /**
     *为sou加上serie的id
     */
    public function addSerie()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souObj = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souObj as $sou) {
            if (empty($sou->getSerieId())) {
                echo 'start id:' . $sou->getId();
                $splitArr = $this->filterArr(mb_split('\s', $sou->getName()));
                var_dump($splitArr[1]);
                preg_match('/\w+/', $splitArr[1], $serie);
                if (!isset($serie[0])) {
                    preg_match('/^[\x{4e00}-\x{9fa5}]+/u', $splitArr[1], $serie);
                }
                $brandId = $sou->getBrandId();
                $query = $em->getRepository('LexingVehicleBundle:VehicleSerie')->createQueryBuilder('b')
                    ->where('b.name like :name and b.brand = :brandId')
                    ->setParameter('name', '%' . $serie[0] . '%')
                    ->setParameter('brandId', $brandId)
                    ->getQuery();
                if ($query->getResult()) {
                    $resSerieId = $sou->getSerieId();
                    if (!$resSerieId) {
                        $resId = $query->getResult()[0]->getId();
                        $sou->setSerieId($resId);
                    }
                } else {
                    $resId = null;
                    $this->saveFile("Error id:" . $sou->getId() . "\n", time() . 'serie.log', true);
                }
                $em->persist($sou);
            }
        }
        $em->flush();
    }


    /**
     *为sou加上serie的name
     */
    public function addSerieName()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souObj = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souObj as $sou) {
            echo 'start id:' . $sou->getId() . "\n";
            $splitArr = $this->filterArr(mb_split('\s', $sou->getName()));
            preg_match('/\w+/', $splitArr[1], $serie);
            if (!isset($serie[0])) {
                preg_match('/^[\x{4e00}-\x{9fa5}]+/u', $splitArr[1], $serie);
            }
            $brandId = $sou->getBrandId();
            $query = $em->getRepository('LexingVehicleBundle:VehicleSerie')->createQueryBuilder('b')
                ->where('b.name like :name and b.brand = :brandId')
                ->setParameter('name', '%' . $serie[0] . '%')
                ->setParameter('brandId', $brandId)
                ->getQuery();
            if ($query->getResult()) {
                $resName = $query->getResult()[0]->getName();
                $makeName = $query->getResult()[0]->getMakeName();
                $sou->setSerieName($resName);
                $sou->setMakeName($makeName);
            } else {
                $resId = null;
                $this->saveFile("Error id:" . $sou->getId(), time() . 'serie.log', true);
            }
            $em->persist($sou);
        }
        $em->flush();
    }


    /**
     * 为VehicleSou增加model_id;
     * 但是成功率很低。大概40%
     */
    public function addModel()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souArr = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souArr as $sou) {
            $name = $sou->getName();
            $serieId = $sou->getSerieId();
            $nameSplitArr = mb_split("\s", $name);
            preg_match('/\b\d+/', $name, $year);
            $year = $year[0];
            $name = $nameSplitArr[count($nameSplitArr) - 1];
            $query = $em->getRepository('LexingVehicleBundle:VehicleModel')->createQueryBuilder('m')
                ->where('m.name like :name AND m.serie = :serie')
                ->setParameter('name', '%' . $name . '%')
                ->setParameter('serie', $serieId)
                ->getQuery();
            if ($query->getResult()) {
                $successId[] = $query->getResult()[0]->getId();
                $sou->setModelId($query->getResult()[0]->getId());
                echo 'save:' . $query->getResult()[0]->getId() . "\n";
                $em->persist($sou);
            } else {
                $failId[] = $serieId;
            }
        }
        $em->flush();
        $this->saveFile(json_encode($successId), 'modelAddId.json' . time());
        $this->saveFile(json_encode($failId), 'modelAddIdFail.json' . time());
    }

    /**
     * 第二种增加model id的方法
     */
    public function addModel2()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souArr = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souArr as $sou) {
            if (empty($sou->getModelId())) {
                $name = $sou->getName();
                $serieId = $sou->getSerieId();
                $subName = substr($name, -9);
                $query = $em->getRepository('LexingVehicleBundle:VehicleModel')->createQueryBuilder('m')
                    ->where('m.name like :name AND m.serie = :serie')
                    ->setParameter('name', '%' . $subName . '%')
                    ->setParameter('serie', $serieId)
                    ->getQuery();
                if ($query->getResult()) {
                    $successId[] = $query->getResult()[0]->getId();
                    $sou->setModelId($query->getResult()[0]->getId());
                    echo 'save:' . $query->getResult()[0]->getId() . "\n";
                    $em->persist($sou);
                } else {
                    $failId[] = $serieId;
                    $successId[] = 0;
                }
            }
        }
        $em->flush();
        $this->saveFile(json_encode($successId), 'modelAddId.json' . time());
        $this->saveFile(json_encode($failId), 'modelAddIdFail.json' . time());
    }

    /**
     * 第三种增加model id的方法
     *
     * 很多都是加了进口导致错误。所以这里只针对（进口）进行处理
     */

    public function addModel3()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souArr = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souArr as $sou) {
            if (empty($sou->getModelId())){
                echo 'start id:' . $sou->getId() ."\n";
                $nameStr = $sou->getName();
                $nameArr = $this->filterArr(explode(" ",$nameStr));
                $brandId = $sou->getBrandId();
                $query = $em->getRepository('LexingVehicleBundle:VehicleSerie')->createQueryBuilder('b')
                    ->where('b.name = :name and b.brand = :brandId')
                    ->setParameter('name',$nameArr[1])
                    ->setParameter('brandId', $brandId)
                    ->getQuery();
                if ($query->getResult()) {
                    dump($query->getResult());
                    $resName = $query->getResult()[0]->getName();
                    $makeName = $query->getResult()[0]->getMakeName();
                    $serieId = $query->getResult()[0]->getId();
                    $sou->setSerieName($resName);
                    $sou->setMakeName($makeName);
                    $sou->setSerieId($serieId);
                } else {
                    $resId = null;
                    $this->saveFile("Error id:" . $sou->getId(), time() . 'serie.log', true);
                }
                $em->persist($sou);
            }
        }
        $em->flush();
    }

    /**
     * 过滤空字符串
     * @param array $arr
     * @return array
     */
    public function filterArr($arr)
    {
        $res = [];
        foreach ($arr as $value) {
            if (!is_null($value) && $value != '') {
                $res[] = $value;
            }
        }
        return $res;
    }


    /**
     * @param string $fileName
     * @return string
     */
    public function getData($fileName)
    {
        return $this->getApplication()->getKernel()->getRootDir() . '/Resources/data/' . $fileName;
    }


    /**
     * 将soucheSerie表与xin的车系对应
     */
    public function mappingSerie()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        //souBundle
        $serieArr = $em->getRepository('LexingSouCheBundle:SouSerie')->findAll();
        foreach ($serieArr as $serie) {
            $name = $serie->getName();//serie name
            var_dump($name);
            //vehicleBundle
            $query = $em->getRepository('LexingVehicleBundle:VehicleSerie')->createQueryBuilder('s')
                ->where('s.name like :name')
                ->setParameter('name', '%' . $name . '%')
                ->getQuery();
            if ($query->getResult()) {
                $successId[] = $query->getResult()[0]->getId();
            } else {
                $failId[] = $serie->getId();
            }
        }
        $this->saveFile(json_encode($successId), 'success_serie.json');
        $this->saveFile(json_encode($failId), 'fail_serie.json');
    }


    /**
     * 断点爬取
     * model大概有1500条，中断的时候调用breakPoint,从断点重新开始爬取
     * @return array 返回导入model的断点，重新开始爬取。
     */
    public function breakPoint()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $serieArr = $em->getRepository('LexingSouCheBundle:SouSerie')->findAll();
        $modelArr = $em->getRepository('LexingSouCheBundle:SouModel')->findAll();
        foreach ($serieArr as $serie) {
            $serieReBuild[] = $serie->getSouSerieId();
        }
        $serieReBuild = array_unique($serieReBuild);
        foreach ($modelArr as $model) {
            $modelReBuild[] = $model->getSouSerieId();
        }
        $modelReBuild = array_unique($modelReBuild);
        foreach ($serieReBuild as $s) {
            if (!in_array($s, $modelReBuild)) {
                $ret[] = $s;
            }
        }
        return array_unique($ret);

    }

    /**
     * 爬取brand模块
     * 根据搜车app的url，爬取搜车的brand字段数据
     * 从souche将brand导入到数据库
     */
    public function importBrand()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $brandArr = json_decode(file_get_contents($this->getData("brand.json")));
        foreach ($brandArr->data as $brand) {
            $name = $brand->name; //name
            $splitArr = mbsplit(" ", $name);
            //get xin brand id
            $brands = $em->getRepository('LexingVehicleBundle:VehicleBrand')->findBy(['name' => $splitArr[1]]);
            if ($brands) {
                $souBrand = new SouBrand();
                $souBrand->setName($splitArr[1]);
                $souBrand->setSouId($brand->code);
                $souBrand->setXinId($brands[0]->getXinId());
                $souBrand->setBrandId($brands[0]->getId());
                $em->persist($souBrand);
            } else {
                $name . 'is null ';
            }
        }
        $em->flush();
        echo 'done \n';
    }

    /**
     * 爬取serie模块
     * 根据搜车app的url，爬取搜车的serie字段数据
     * 从souche将serie导入到数据库
     */
    public function importSerie()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $brands = $em->getRepository('LexingSouCheBundle:SouBrand')->findAll();
        foreach ($brands as $brand) {
            $brandId = $brand->getSouId();
            $url = 'http://dfc.souche.com/rest/dictionary/cat/seriesV2?brandCode=' . $brandId;
            $res = json_decode($this->openUrl($url));
            foreach ($res->data as $series) {
                foreach ($series->item as $serie) {
                    $souSerie = new SouSerie();
                    $souSerie->setMakeName($series->title);
                    $souSerie->setSouBrandId($brandId);
                    $souSerie->setSouSerieId($serie->code);
                    $souSerie->setName($serie->name);
                    $em->persist($souSerie);
                }
            }
            sleep(3);
            $em->flush();
            echo "save:" . $brandId . "\n";
        }
    }

    /**
     * 爬取model模块
     * 根据搜车app的url，爬取搜车的model字段数据
     * 从souche将model导入到数据库
     */
    public function importModel()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
//        $serieArr = $em->getRepository('LexingSouCheBundle:SouSerie')->findAll();
        $serieArr = $this->breakPoint();
        foreach ($serieArr as $serieId) {
//            $serieId = $serie->getSouSerieId();
            $url = 'dfc.souche.com/rest/dictionary/model?seriesCode=' . $serieId;
            $res = json_decode($this->openUrl($url));
            foreach ($res->data as $model) {
                preg_match('/\b\d+/', $model->name, $year);
                $souModel = new SouModel();
                $souModel->setName($model->name);
                $souModel->setSouModelId($model->code);
                $souModel->setSouSerieId($serieId);
                $souModel->setYear(!empty($year[0]) ? $year[0] : null);
                $em->persist($souModel);
            }
            $em->flush();
            sleep(random_int(2, 5));
            echo "save:" . $serieId . "\n";
        }
    }

    /**
     * 给sou表增加年份
     */
    public function addYear()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $souArr = $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
        foreach ($souArr as $sou) {
            $name = $sou->getName();
            preg_match("/\d+/", $name, $yearArr);
            $year = $yearArr[0];
            $id = $sou->getId();
            $souObj = $em->getRepository('LexingVehicleBundle:VehicleSou')->find($id);
            $souObj->setYear($year);
            $em->persist($souObj);
        }
        $em->flush();
    }


    /**
     * 保存到data文件目录
     *
     * @param string $txt 文件内容
     * @param string $fileName 文件目录
     * @return string
     */
    public function saveFile($txt, $fileName, $append = false)
    {
        $dataFileDir = $this->getData($fileName);
        if ($append) {
            file_put_contents($dataFileDir, $txt, FILE_APPEND);
        } else {
            file_put_contents($dataFileDir, $txt);
        }

        return 'success';
    }

    /**
     * 得到打开URL到数据
     *
     * @param string $url 传入url
     * @return mixed    mixed
     */
    public function openUrl($url)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, $this->cookie);
        $ret = curl_exec($c);
        curl_close($c);
        return $ret;
    }


    /**
     * 中文排序
     * 将中文每个字切成数组传入
     *
     * @param   array $chinese
     * @return  array|bool
     */
    public function zsort($chinese)
    {
        if (!is_array($chinese)) {
            return false;
        }
        foreach ($chinese as $k => $v) {
            //先讲utf-8格式转换为gbk格式
            $chinese[$k] = iconv('UTF-8', 'GBK//IGNORE', $v);
        }
        asort($chinese);
        foreach ($chinese as $k => $v) {
            //最后将gbk格式再转换回utf-8格式
            $chinese[$k] = iconv('GBK', 'UTF-8//IGNORE', $v);
        }
        return $chinese;
    }

}

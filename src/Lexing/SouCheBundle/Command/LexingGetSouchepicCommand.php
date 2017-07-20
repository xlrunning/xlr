<?php

namespace Lexing\SouCheBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class LexingGetSouchepicCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('lexing:get-souchepic')
            ->setDescription('从搜车app导入封面图和图片集')
            ->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        爬取封面图
//        $this->getCoverPic();

        //爬取相册图集
        $this->album();

    }

    public function smallAlbum()
    {
        $souArr = $this->getSouChe();
    }

    /**
     * 传入soucheId模拟登陆，得到车辆详情
     *
     */
    public function album()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        $soucheArr = $this->getSouChe();
        foreach ($soucheArr as $sou) {
            $souId = $sou->getSouId();
            if (empty($sou->getPic())){
                dump('start:' . $souId);
                $res = $this->getRequest($souId);
                if ($res->success) {
                    $picArr = $res->data->picture;
                    //保存到数据库
                    if (is_array($picArr)){
                        $sou->setPic($picArr);
                        //下载图片
                        $this->downArrImg($picArr);
                    }else{
                        dump('图片集为空id:'.$sou->getId());
                    }
                } else {
                    dump('fail souId is:' . $souId);
                }
                $em->persist($sou);
                $em->flush();
            }
        }

    }

    /**
     * 下载图片集
     *
     * @param array $imgArr
     */
    public function downArrImg($imgArr)
    {
        foreach ($imgArr as $img) {
            $imgUrl = $img->pictureBig;
            dump('downImg:' . $imgUrl);
            $filePath = $this->getPath('detail');
            $this->downPic($imgUrl, $filePath);
        }
    }

    /**
     * 下载200*200的封面图
     */
    public function getCoverPic()
    {
        $soucheArr = $this->getSouChe();
        $filePath = $this->getPath('list');
        foreach ($soucheArr as $sou) {
//            断点重爬
//            if ($sou->getId() > 514) {
                $imgUrl = $sou->getPicUrl();
                dump('down:' . $imgUrl);
                $this->downPic($imgUrl, $filePath, true);
                sleep(rand(1, 3));
//            }
        }
    }

    /**
     * 得到souche表的实例
     */
    public function getSouChe()
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        return $em->getRepository('LexingVehicleBundle:VehicleSou')->findAll();
    }

    /**
     * 根据传入的URL保存图片到指定路径
     *
     * @param string $imgUrl  图片URL
     * @param bool   $isSmall 是否需要小图200*200
     */
    public function downPic($imgUrl, $filePath, $isSmall = false)
    {
        if (!empty($imgUrl)) {
            $nameArr = mb_split('/', $imgUrl);
            $name = $nameArr[count($nameArr) - 1];
            if ($isSmall) {
//                $imgUrl = $imgUrl . '@200w_200h_1e_1c_2o';
                $imgUrl = $imgUrl . '@320w_200h_1e_1c_2o';
            }
            $savePath = $filePath . $name;
            file_put_contents($savePath, file_get_contents($imgUrl));
            dump("downImgSuccess:" . $imgUrl);
        } else {
            dump('imgUrl为空');
        }

    }

    /**
     * 返回保存图片的文件夹路径
     *
     * @param  string $fileName 文件夹名称
     *
     * @return string       返回保存图片的文件夹路径
     */
    public function getPath($fileName)
    {
        return $this->getApplication()->getKernel()->getRootDir() . "/../web/app/img/souche/" . $fileName . "/";
    }

    /**
     * 发起http请求,并返回得到的数据
     *
     * @param   string $carId
     *
     * @return  mixed
     */
    public function getRequest($carId)
    {
        $url = 'erp.souche.com/pc/car/cardetailaction/getCarPictures.json';
        $cookie = 'Cookie:JSESSIONID=85026B99C41C962EEF35807C15479202; _security_token=1487900116214281';
        $params = [
            "platformType" => 'AppStore',
            'carId' => $carId,
            'jpushid' => '141fe1da9e997d1407a',
            'version' => '5.9.4'
        ];
        return $this->request($url, $cookie, $params);
    }

    /**
     * 发起http请求
     *
     * @param   string $url    请求的URL
     * @param   string $cookie 请求的cookie，格式：Cookie:xxx;
     * @param   array  $params post请求的参数
     *
     * @return mixed
     */
    public function request($url, $cookie, $params)
    {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($c, CURLOPT_HTTPHEADER, array($cookie));
        curl_setopt($c, CURLOPT_POSTFIELDS, $params);
        $ret = curl_exec($c);
        $resArr = json_decode($ret);
        curl_close($c);
        return $resArr;
    }

}

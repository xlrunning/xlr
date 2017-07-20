<?php
/**
 * Created by PhpStorm.
 * User: EasyChris<chris@afox.cc>
 * Date: 2017/4/14
 * Time: 下午1:36
 */

namespace Lexing\VehicleBundle\Service;


class GetVehicleInfo
{
    private $container;

    /**
     * 初始化container容器
     *
     * @param $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * 得到vehicle_brand表的品牌数据
     *
     * @return mixed
     */
    public function getBrand()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        $brandArr = $em->getRepository('LexingVehicleBundle:VehicleBrand')->findAll();
        return $brandArr;
    }


    /**
     * 返回指定车商名下的车辆数量
     *
     * @param array $params
     *
     * @return mixed
     */
    public function countVehicle($params)
    {
        $dql = 'SELECT COUNT(DISTINCT v.id) FROM LexingVehicleBundle:Vehicle v WHERE v.dealer = :dealer';
        if (array_key_exists('brand',$params)){
            $dql .= ' AND v.model.brand = :brand';
        }
        $em = $this->container->get('doctrine.orm.entity_manager');
        $countVehicle = $em->createQuery($dql)
            ->setParameters($params)
            ->getSingleScalarResult();
        return $countVehicle;
    }
}
<?php

namespace Lexing\VehicleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VehicleSou
 *
 * @ORM\Table(name="vehicle_sou")
 * @ORM\Entity(repositoryClass="Lexing\VehicleBundle\Repository\VehicleSouRepository")
 */
class VehicleSou
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="souId", type="string", length=255)
     */
    private $souId;

    /**
     * @var float
     *
     * @ORM\Column(name="mile", type="float")
     */
    private $mile;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="brand_id", type="string", length=255,nullable=true)
     */
    private $brandId;

    /**
     * @var string
     *
     * @ORM\Column(name="serie_id", type="string", length=255,nullable=true)
     */
    private $serieId;

    /**
     * @var string
     *
     * @ORM\Column(name="model_id", type="string", length=255,nullable=true)
     */
    private $modelId;

    /**
     * @var string
     *
     * @ORM\Column(name="picUrl", type="string", length=255)
     */
    private $picUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="brand_name", type="string", length=255,nullable=true)
     */
    private $brandName;

    /**
     * @var string
     *
     * @ORM\Column(name="serie_name", type="string", length=255,nullable=true)
     */
    private $serieName;

    /**
     * @var string
     *
     * @ORM\Column(name="make_name", type="string", length=255,nullable=true)
     */
    private $makeName;

    /**
     * @var integer
     *
     * @ORM\Column(name="is_import", type="integer", nullable=true, )
     */
    private $isImport;

    /**
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="model_name", type="string", nullable=true)
     */
    private $modelName;

    /**
     * @var string
     *
     * @ORM\Column(name="pic", type="json_array", nullable=true)
     */
    private $pic;

    /**
     * @return string
     */
    public function getPic()
    {
        return $this->pic;
    }

    /**
     * @param string $pic
     */
    public function setPic($pic)
    {
        $this->pic = $pic;
    }



    /**
     * @return int
     */
    public function getIsImport()
    {
        return $this->isImport;
    }

    /**
     * @param int $isImport
     */
    public function setIsImport($isImport)
    {
        $this->isImport = $isImport;
    }



    /**
     * @return string
     */
    public function getModelName()
    {
        return $this->modelName;
    }

    /**
     * @param string $modelName
     */
    public function setModelName($modelName)
    {
        $this->modelName = $modelName;
    }



    /**
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param int $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return string
     */
    public function getMakeName()
    {
        return $this->makeName;
    }

    /**
     * @param string $makeName
     */
    public function setMakeName($makeName)
    {
        $this->makeName = $makeName;
    }



    /**
     * @return string
     */
    public function getBrandName()
    {
        return $this->brandName;
    }

    /**
     * @param string $brandName
     */
    public function setBrandName($brandName)
    {
        $this->brandName = $brandName;
    }

    /**
     * @return string
     */
    public function getSerieName()
    {
        return $this->serieName;
    }

    /**
     * @param string $serieName
     */
    public function setSerieName($serieName)
    {
        $this->serieName = $serieName;
    }



    /**
     * @return string
     */
    public function getBrandId()
    {
        return $this->brandId;
    }

    /**
     * @param string $brandId
     */
    public function setBrandId($brandId)
    {
        $this->brandId = $brandId;
    }

    /**
     * @return string
     */
    public function getSerieId()
    {
        return $this->serieId;
    }

    /**
     * @param string $serieId
     */
    public function setSerieId($serieId)
    {
        $this->serieId = $serieId;
    }

    /**
     * @return string
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * @param string $modelId
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;
    }



    /**
     * @var string
     *
     * @ORM\Column(name="orderPrice", type="string", length=255)
     */
    private $orderPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="plateTime", type="string", length=255)
     */
    private $plateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="string",length=255)
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="saledTime", type="integer")
     */
    private $saledTime;

    /**
     * @var string
     *
     * @ORM\Column(name="soucheNumber", type="string", length=255)
     */
    private $soucheNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="stayDay", type="string", length=255)
     */
    private $stayDay;

    /**
     * @var string
     *
     * @ORM\Column(name="store", type="string", length=255)
     */
    private $store;

    /**
     * @var string
     *
     * @ORM\Column(name="storeFullName", type="string", length=255)
     */
    private $storeFullName;

    /**
     * @var string
     *
     * @ORM\Column(name="storeName", type="string", length=255)
     */
    private $storeName;

    /**
     * @var string
     *
     * @ORM\Column(name="synchonousStr", type="string", length=255)
     */
    private $synchonousStr;

    /**
     * @var string
     *
     * @ORM\Column(name="tags", type="string", length=255)
     */
    private $tags;

    /**
     * @var string
     *
     * @ORM\Column(name="userDefinedStatus", type="string", length=255)
     */
    private $userDefinedStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="vin", type="string", length=255)
     */
    private $vin;

    /**
     * @var string
     *
     * @ORM\Column(name="dealer", type="string", length=255)
     */
    private $dealer;

    /**
     * @return string
     */
    public function getDealer()
    {
        return $this->dealer;
    }

    /**
     * @param string $dealer
     */
    public function setDealer($dealer)
    {
        $this->dealer = $dealer;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set souId
     *
     * @param string $souId
     *
     * @return VehicleSou
     */
    public function setSouId($souId)
    {
        $this->souId = $souId;

        return $this;
    }

    /**
     * Get souId
     *
     * @return string
     */
    public function getSouId()
    {
        return $this->souId;
    }

    /**
     * Set mile
     *
     * @param float $mile
     *
     * @return VehicleSou
     */
    public function setMile($mile)
    {
        $this->mile = $mile;

        return $this;
    }

    /**
     * Get mile
     *
     * @return float
     */
    public function getMile()
    {
        return $this->mile;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return VehicleSou
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set picUrl
     *
     * @param string $picUrl
     *
     * @return VehicleSou
     */
    public function setPicUrl($picUrl)
    {
        $this->picUrl = $picUrl;

        return $this;
    }

    /**
     * Get picUrl
     *
     * @return string
     */
    public function getPicUrl()
    {
        return $this->picUrl;
    }

    /**
     * Set orderPrice
     *
     * @param float $orderPrice
     *
     * @return VehicleSou
     */
    public function setOrderPrice($orderPrice)
    {
        $this->orderPrice = $orderPrice;

        return $this;
    }

    /**
     * Get orderPrice
     *
     * @return float
     */
    public function getOrderPrice()
    {
        return $this->orderPrice;
    }

    /**
     * Set plateTime
     *
     * @param string $plateTime
     *
     * @return VehicleSou
     */
    public function setPlateTime($plateTime)
    {
        $this->plateTime = $plateTime;

        return $this;
    }

    /**
     * Get plateTime
     *
     * @return string
     */
    public function getPlateTime()
    {
        return $this->plateTime;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return VehicleSou
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set saledTime
     *
     * @param integer $saledTime
     *
     * @return VehicleSou
     */
    public function setSaledTime($saledTime)
    {
        $this->saledTime = $saledTime;

        return $this;
    }

    /**
     * Get saledTime
     *
     * @return int
     */
    public function getSaledTime()
    {
        return $this->saledTime;
    }

    /**
     * Set soucheNumber
     *
     * @param string $soucheNumber
     *
     * @return VehicleSou
     */
    public function setSoucheNumber($soucheNumber)
    {
        $this->soucheNumber = $soucheNumber;

        return $this;
    }

    /**
     * Get soucheNumber
     *
     * @return string
     */
    public function getSoucheNumber()
    {
        return $this->soucheNumber;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return VehicleSou
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set stayDay
     *
     * @param string $stayDay
     *
     * @return VehicleSou
     */
    public function setStayDay($stayDay)
    {
        $this->stayDay = $stayDay;

        return $this;
    }

    /**
     * Get stayDay
     *
     * @return string
     */
    public function getStayDay()
    {
        return $this->stayDay;
    }

    /**
     * Set store
     *
     * @param string $store
     *
     * @return VehicleSou
     */
    public function setStore($store)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return string
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Set storeFullName
     *
     * @param string $storeFullName
     *
     * @return VehicleSou
     */
    public function setStoreFullName($storeFullName)
    {
        $this->storeFullName = $storeFullName;

        return $this;
    }

    /**
     * Get storeFullName
     *
     * @return string
     */
    public function getStoreFullName()
    {
        return $this->storeFullName;
    }

    /**
     * Set storeName
     *
     * @param string $storeName
     *
     * @return VehicleSou
     */
    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * Get storeName
     *
     * @return string
     */
    public function getStoreName()
    {
        return $this->storeName;
    }

    /**
     * Set synchonousStr
     *
     * @param string $synchonousStr
     *
     * @return VehicleSou
     */
    public function setSynchonousStr($synchonousStr)
    {
        $this->synchonousStr = $synchonousStr;

        return $this;
    }

    /**
     * Get synchonousStr
     *
     * @return string
     */
    public function getSynchonousStr()
    {
        return $this->synchonousStr;
    }

    /**
     * Set tags
     *
     * @param string $tags
     *
     * @return VehicleSou
     */
    public function setTags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get tags
     *
     * @return string
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set userDefinedStatus
     *
     * @param string $userDefinedStatus
     *
     * @return VehicleSou
     */
    public function setUserDefinedStatus($userDefinedStatus)
    {
        $this->userDefinedStatus = $userDefinedStatus;

        return $this;
    }

    /**
     * Get userDefinedStatus
     *
     * @return string
     */
    public function getUserDefinedStatus()
    {
        return $this->userDefinedStatus;
    }

    /**
     * Set vin
     *
     * @param string $vin
     *
     * @return VehicleSou
     */
    public function setVin($vin)
    {
        $this->vin = $vin;

        return $this;
    }

    /**
     * Get vin
     *
     * @return string
     */
    public function getVin()
    {
        return $this->vin;
    }
}


<?php

namespace Lexing\VehicleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Lexing\DealerBundle\Entity\VehicleDealer;
use Lexing\TradeBundle\Entity\VehicleSale;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Vehicle
 *
 * @ORM\Table(name="vehicle")
 * @ORM\Entity(repositoryClass="Lexing\VehicleBundle\Repository\VehicleRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable(logEntryClass="Lexing\AssistBundle\Entity\LogEntry")
 */
class Vehicle
{
    const BRANDNEW   = 'new';
    const SECONDHAND = 'secondhand';

    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var VehicleDealer
     * @Assert\NotBlank()
     * 客户归属车城
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\DealerBundle\Entity\VehicleDealer",
     *     inversedBy="vehicles"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dealer;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Lexing\TradeBundle\Entity\VehicleSale",
     *     mappedBy="vehicle",
     *     indexBy="type"
     * )
     */
    private $sales;

    /**
     * vehicle type e.g. new / secondhand
     *
     * @var string
     * @Assert\NotBlank()
     *
     * @ORM\Column(name="kind", type="string", length=50, nullable=true)
     */
    private $kind = self::SECONDHAND;

    /**
     * @var string
     *
     * @ORM\Column(name="plate_loc", type="string", length=255, nullable=true)
     */
    private $plateLoc;

    /**
     * @var string
     *
     * @ORM\Column(name="plate_no", type="string", length=255, nullable=true)
     */
    private $plateNo;

    /**
     * 17位数字字母不能含有I、O、Q
     * @var string
     * @ORM\Column(name="vin", type="string", length=255,  nullable=true)
     */
    private $vin;

    /**
     * 车型
     * @var string
     *
     * @ORM\Embedded(class="Lexing\VehicleBundle\Entity\EmbeddableModel", columnPrefix="model_")
     */
    private $model;

    /**
     * 关联的VehicleModel
     * @var VehicleModel
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\VehicleBundle\Entity\VehicleModel"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $assocModel;

    /**
     * @var float
     *
     * @ORM\Column(name="mileage", type="float", nullable=true)
     */
    private $mileage;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="json_array", nullable=true)
     */
    private $info;

    /**
     * @var string
     *
     * @ORM\Column(name="souche_model", type="string", nullable=true)
     */
    private $soucheModel;


    /**
     * 车辆本身的照片、行驶证等
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(
     *      targetEntity="Nnv\GalleryBundle\Entity\Gallery",
     *      orphanRemoval=true,
     *      indexBy="code",
     *      cascade={"persist", "remove"}
     * )
     */
    protected $galleries;

    /**
     * 评估价格
     *
     * @var int
     *
     * @ORM\Column(name="evaluation", type="integer", nullable=true)
     */
    private $evaluation;

    /**
     * @var string
     *
     * @ORM\Column(name="pic", type="json_array", nullable=true)
     */
    private $pic;

    /**
     * @var string
     *
     * @ORM\Column(name="cover", type="string", nullable=true)
     */
    private $cover;

    /**
     * 是否在售
     * @var boolean
     *
     * @ORM\Column(name="on_sale", type="boolean")
     * @Gedmo\Versioned
     */
    private $onSale = true;

    /**
     *
     * @var Vehicle
     *
     * @ORM\OneToOne(
     *     targetEntity="Lexing\LoanBundle\Entity\VehicleMortgage",
     *     inversedBy="vehicle"
     * )
     * @Gedmo\Versioned
     */
    private $mortgage;

    /**
     * 下架时间
     * @var DateTime
     *
     * @ORM\Column(name="off_sale_at", type="datetime", nullable=true)
     */
    private $offSaleAt;

    /**
     * 下架原因、备注
     * @var string
     *
     * @ORM\Column(name="off_sale_note", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $offSaleNote;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->galleries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sales = new \Doctrine\Common\Collections\ArrayCollection();
        $this->model = new EmbeddableModel();
    }

    private function syncModel()
    {
        $assocModel = $this->getAssocModel();
        if (!$assocModel) {
            return $this;
        }
        $serieObj   = $assocModel->getSerie();
        $this->model->setName($assocModel->getName())
            ->setYear($assocModel->getYear())
            ->setSerie($serieObj->getName())
            ->setMake($serieObj->getMakeName())
            ->setBrand($serieObj->getBrand()->getName());
        return $this;
    }

    /**
     * @ORM\PrePersist()
     */
    public function prePersist($event)
    {
        $this->syncModel();
        $this->dealer->incNbTotalVehicles();
        $isOnSale = $event->getObject()->isOnSale();
        if ($isOnSale) $this->dealer->incNbOnSaleVehiclesOnly();

    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(PreUpdateEventArgs $event)
    {
        if ($event->hasChangedField('assocModel')) {
            $this->syncModel();
        }
        if ($event->hasChangedField('onSale') && !$event->getNewValue('onSale')) {
            $this->offSaleAt = new \DateTime();
        }
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
     * Set plateLoc
     *
     * @param string $plateLoc
     *
     * @return Vehicle
     */
    public function setPlateLoc($plateLoc)
    {
        $this->plateLoc = $plateLoc;

        return $this;
    }

    /**
     * Get plateLoc
     *
     * @return string
     */
    public function getPlateLoc()
    {
        return $this->plateLoc;
    }

    /**
     * Set plateNo
     *
     * @param string $plateNo
     *
     * @return Vehicle
     */
    public function setPlateNo($plateNo)
    {
        $this->plateNo = $plateNo;

        return $this;
    }

    /**
     * Get plateNo
     *
     * @return string
     */
    public function getPlateNo()
    {
        return $this->plateNo;
    }

    /**
     * Set vin
     *
     * @param string $vin
     *
     * @return Vehicle
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

    /**
     * Set model
     *
     * @param EmbeddableModel $model
     *
     * @return Vehicle
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return EmbeddableModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set mileage
     *
     * @param integer $mileage
     *
     * @return Vehicle
     */
    public function setMileage($mileage)
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * Get mileage
     *
     * @return int
     */
    public function getMileage()
    {
        return $this->mileage;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Vehicle
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Vehicle
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set info
     *
     * @param string $info
     *
     * @return Vehicle
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    public function getGalleries()
    {
        return $this->galleries;
    }

    public function addGallery($gallery)
    {
        $this->galleries->add($gallery);
        $gallery->setAttached(true);
        return $this;
    }

    public function setGalleries($galleries)
    {
        $this->galleries = $galleries;
    }

    /**
     * Remove gallery
     *
     * @param \Nnv\GalleryBundle\Entity\Gallery $gallery
     */
    public function removeGallery(\Nnv\GalleryBundle\Entity\Gallery $gallery)
    {
        $this->galleries->removeElement($gallery);
    }

    /**
     * Set assocModel
     *
     * @param \Lexing\VehicleBundle\Entity\VehicleModel $assocModel
     *
     * @return Vehicle
     */
    public function setAssocModel(\Lexing\VehicleBundle\Entity\VehicleModel $assocModel = null)
    {
        $this->assocModel = $assocModel;

        return $this;
    }

    /**
     * Get assocModel
     *
     * @return \Lexing\VehicleBundle\Entity\VehicleModel
     */
    public function getAssocModel()
    {
        return $this->assocModel;
    }

    /**
     * @return string
     */
    public function getSoucheModel()
    {
        return $this->soucheModel;
    }

    /**
     * @param string $soucheModel
     */
    public function setSoucheModel($soucheModel)
    {
        $this->soucheModel = $soucheModel;
    }

    /**
     * Set dealer
     *
     * @param \Lexing\DealerBundle\Entity\VehicleDealer $dealer
     *
     * @return Vehicle
     */
    public function setDealer(\Lexing\DealerBundle\Entity\VehicleDealer $dealer = null)
    {
        $this->dealer = $dealer;

        return $this;
    }

    /**
     * Get dealer
     *
     * @return \Lexing\DealerBundle\Entity\VehicleDealer
     */
    public function getDealer()
    {
        return $this->dealer;
    }

    /**
     * @return ArrayCollection
     */
    public function getSales()
    {
        return $this->sales;
    }

    /**
     * @param ArrayCollection $sales
     */
    public function setSales($sales)
    {
        $this->sales = $sales;

        return $this;
    }

    /**
     * @return int
     */
    public function getEvaluation()
    {
        return $this->evaluation;
    }

    /**
     * @param $evaluation
     *
     * @return vehicle
     */
    public function setEvaluation($evaluation)
    {
        $this->evaluation = $evaluation;

        return $this;
    }

    /**
     * @return string
     */
    public function getPic()
    {
        return $this->pic;
    }

    /**
     * @param $pic
     *
     * @return vehicle
     */
    public function setPic($pic)
    {
        if (!is_array($pic)) $pic = @json_decode($pic, true);

        $this->pic = $pic;

        return $this;
    }

    /**
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * @param $cover
     *
     * @return vehicle
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return bool
     */
    public function isOnSale()
    {
        return $this->onSale;
    }

    /**
     * @param bool $onSale
     * @return Vehicle
     */
    public function setOnSale($onSale)
    {
        $this->onSale = $onSale;
        return $this;
    }

    /**
     * @return string
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param string $kind
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }

    /**
     * @return Vehicle
     */
    public function getMortgage()
    {
        return $this->mortgage;
    }

    /**
     * @param Vehicle $mortgage
     * @return Vehicle
     */
    public function setMortgage($mortgage)
    {
        $this->mortgage = $mortgage;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getOffSaleAt()
    {
        return $this->offSaleAt;
    }

    /**
     * @param DateTime $offSaleAt
     * @return Vehicle
     */
    public function setOffSaleAt($offSaleAt)
    {
        $this->offSaleAt = $offSaleAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getOffSaleNote()
    {
        return $this->offSaleNote;
    }

    /**
     * @param string $offSaleNote
     * @return Vehicle
     */
    public function setOffSaleNote($offSaleNote)
    {
        $this->offSaleNote = $offSaleNote;
        return $this;
    }

    private function getTypeTrade($type)
    {
        $sale = $this->sales[$type];
        return $sale ? $sale->getTrade() : null;
    }

    /**
     *
     * @return Trade|null
     */
    public function getFirstPaidTrade()
    {
        return $this->getTypeTrade(VehicleSale::TYPE_PAID_FIRST);
    }

    /**
     *
     * @return Trade|null
     */
    public function getFrontPaidTrade()
    {
        return $this->getTypeTrade(VehicleSale::TYPE_PAID_FRONT);
    }

    /**
     *
     * @return Trade|null
     */
    public function getFinalPaidTrade()
    {
        return $this->getTypeTrade(VehicleSale::TYPE_PAID_FINAL);
    }

    /**
     *
     * @return Trade|null
     */
    public function getFullPaidTrade()
    {
        return $this->getTypeTrade(VehicleSale::TYPE_PAID_FULL);
    }

    public function __toString()
    {
        $prefix = $this->getDealer() ? '' . $this->getDealer() . '-' : '';
        $prefix .= $this->getModel() ? $this->getModel()->getBrand() : '';
        return $this->id ? $prefix . '-' . $this->vin : '新车辆';
    }
}

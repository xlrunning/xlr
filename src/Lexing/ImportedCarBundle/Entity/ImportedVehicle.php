<?php

namespace Lexing\ImportedCarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * ImportedVehicle
 *
 * @ORM\Table(name="imported_vehicle")
 * @ORM\Entity(repositoryClass="Lexing\ImportedCarBundle\Repository\ImportedVehicleRepository")
 */
class ImportedVehicle
{
    use TimestampableEntity;

    const IMPORTED_VEHICLE = 'imported';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

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
     * @var VehicleDealer
     * 客户归属车城
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\DealerBundle\Entity\VehicleDealer",
     *     inversedBy="vehicles"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dealer;

    // TODO relation sale result.
    ///**
    // *
    // * @var ArrayCollection
    // *
    // * @ORM\OneToMany(
    // *     targetEntity="Lexing\TradeBundle\Entity\VehicleSale",
    // *     mappedBy="vehicle",
    // *     indexBy="type"
    // * )
    // */
    //private $sales;

    ///**
    // * 车型
    // * @var string
    // *
    // * @ORM\Embedded(
    // *     class="Lexing\ImportedCarBundle\Entity\EmbeddableModel",
    // *     columnPrefix="model_"
    // * )
    // */
    //private $model;

    ///**
    // * 关联的VehicleModel
    // * @var VehicleModel
    // *
    // * @ORM\ManyToOne(
    // *     targetEntity="Lexing\VehicleBundle\Entity\VehicleModel"
    // * )
    // * @ORM\JoinColumn(onDelete="SET NULL")
    // */
    //private $assocModel;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="info", type="string", nullable=true)
     */
    private $info;

    /**
     * @var string
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     */
    private $stock;

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
     */
    private $mortgage;

    /**
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\ImportedCarBundle\Entity\ImportedModel"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $model;

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        //$this->galleries = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->sales = new \Doctrine\Common\Collections\ArrayCollection();
        //$this->model = new EmbeddableModel();
    }

    //
    //private function syncModel()
    //{
    //    $assocModel = $this->getAssocModel();
    //    $serieObj   = $assocModel->getSerie();
    //    $this->model->setName($assocModel->getName())
    //        ->setYear($assocModel->getYear())
    //        ->setSerie($serieObj->getName())
    //        ->setMake($serieObj->getMakeName())
    //        ->setBrand($serieObj->getBrand()->getName());
    //}
    //
    ///**
    // * @ORM\PrePersist()
    // */
    //public function prePersist()
    //{
    //    $this->syncModel();
    //}

    ///**
    // * @ORM\PreUpdate()
    // */
    //public function preUpdate(PreUpdateEventArgs $event)
    //{
    //    if ($event->hasChangedField('assocModel')) {
    //        $this->syncModel();
    //    }
    //}

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

    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

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

    ///**
    // * Set assocModel
    // *
    // * @param \Lexing\VehicleBundle\Entity\VehicleModel $assocModel
    // *
    // * @return Vehicle
    // */
    //public function setAssocModel(\Lexing\VehicleBundle\Entity\VehicleModel $assocModel = null)
    //{
    //    $this->assocModel = $assocModel;
    //
    //    return $this;
    //}
    //
    ///**
    // * Get assocModel
    // *
    // * @return \Lexing\VehicleBundle\Entity\VehicleModel
    // */
    //public function getAssocModel()
    //{
    //    return $this->assocModel;
    //}

    public function setDealer(\Lexing\DealerBundle\Entity\VehicleDealer $dealer = null)
    {
        $this->dealer = $dealer;

        return $this;
    }

    public function getDealer()
    {
        return $this->dealer;
    }

    //public function getSales()
    //{
    //    return $this->sales;
    //}
    //
    //public function setSales($sales)
    //{
    //    $this->sales = $sales;
    //
    //    return $this;
    //}

    public function getPic()
    {
        return $this->pic;
    }

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

    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    public function isOnSale()
    {
        return $this->onSale;
    }

    public function setOnSale($onSale)
    {
        $this->onSale = $onSale;
        return $this;
    }

    public function getMortgage()
    {
        return $this->mortgage;
    }

    public function setMortgage($mortgage)
    {
        $this->mortgage = $mortgage;
        return $this;
    }


    /**
     * @return string
     */
    public function getStock(): string
    {
        return $this->stock;
    }

    /**
     * @param string $stock
     */
    public function setStock(string $stock)
    {
        $this->stock = $stock;
    }
    public function __toString()
    {
        return '';
    }
}


<?php

namespace Lexing\VehicleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexing\VehicleBundle\Traits\XinEntity;

/**
 * VehicleSerie
 *
 * @ORM\Table(name="vehicle_serie")
 * @ORM\Entity(repositoryClass="Lexing\VehicleBundle\Repository\VehicleSerieRepository")
 */
class VehicleSerie
{
    use XinEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var VehicleBrand
     *
     * @ORM\ManyToOne(
     *     targetEntity="VehicleBrand",
     *     inversedBy="series"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="make_name", type="string", length=255)
     */
    private $makeName;

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
     * Set brand
     *
     * @param VehicleBrand $brand
     *
     * @return VehicleSerie
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \stdClass
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return VehicleSerie
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
     * Set makeName
     *
     * @param string $makeName
     *
     * @return VehicleSerie
     */
    public function setMakeName($makeName)
    {
        $this->makeName = $makeName;

        return $this;
    }

    /**
     * Get makeName
     *
     * @return string
     */
    public function getMakeName()
    {
        return $this->makeName;
    }
}

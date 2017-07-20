<?php

namespace Lexing\VehicleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexing\VehicleBundle\Traits\XinEntity;
// use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * VehicleModel
 *
 * @ORM\Table(name="vehicle_model")
 * @ORM\Entity(repositoryClass="Lexing\VehicleBundle\Repository\VehicleModelRepository")
 */
class VehicleModel
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
     * @var VehicleSerie
     *
     * @ORM\ManyToOne(
     *     targetEntity="VehicleSerie",
     *     inversedBy="models",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $serie;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_configs", type="string", length=255, nullable=true)
     */
    private $customConfigs;

    /**
     * @var string
     *
     * @ORM\Column(name="extra", type="text", nullable=true)
     */
    private $extra;


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
     * Set serie
     *
     * @param \stdClass $serie
     *
     * @return VehicleModel
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return \stdClass
     */
    public function getSerie()
    {
        return $this->serie;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return VehicleModel
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
     * Set year
     *
     * @param integer $year
     *
     * @return VehicleModel
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set customConfigs
     *
     * @param string $customConfigs
     *
     * @return VehicleModel
     */
    public function setCustomConfigs($customConfigs)
    {
        $this->customConfigs = $customConfigs;

        return $this;
    }

    /**
     * Get customConfigs
     *
     * @return string
     */
    public function getCustomConfigs()
    {
        return $this->customConfigs;
    }

    /**
     * Set extra
     *
     * @param string $extra
     *
     * @return VehicleModel
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }
}

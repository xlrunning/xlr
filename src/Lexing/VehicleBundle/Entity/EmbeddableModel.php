<?php

namespace Lexing\VehicleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmbeddableModel
 * 品牌车系车型的字符串数据
 *
 * @ORM\Embeddable()
 */
class EmbeddableModel
{
    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255, nullable=true)
     */
    private $brand;

    /**
     * @var string
     *
     * @ORM\Column(name="make", type="string", length=255, nullable=true)
     */
    private $make;

    /**
     * @var string
     *
     * @ORM\Column(name="serie", type="string", length=255, nullable=true)
     */
    private $serie;

    /**
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255,nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="custom_configs", type="string", length=255, nullable=true)
     */
    private $customConfigs;

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return EmbeddableModel
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set make
     *
     * @param string $make
     *
     * @return EmbeddableModel
     */
    public function setMake($make)
    {
        $this->make = $make;

        return $this;
    }

    /**
     * Get make
     *
     * @return string
     */
    public function getMake()
    {
        return $this->make;
    }

    /**
     * Set serie
     *
     * @param string $serie
     *
     * @return EmbeddableModel
     */
    public function setSerie($serie)
    {
        $this->serie = $serie;

        return $this;
    }

    /**
     * Get serie
     *
     * @return string
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
     * @return EmbeddableModel
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
     * @return EmbeddableModel
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

    public function __toString()
    {
        return $this->brand . ' ' . $this->make . ' ' . $this->name;
    }
}

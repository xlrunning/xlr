<?php

namespace Lexing\ImportedCarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImportedSeries
 *
 * @ORM\Table(name="imported_series")
 * @ORM\Entity(repositoryClass="Lexing\ImportedCarBundle\Repository\ImportedSeriesRepository")
 */
class ImportedSeries
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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @ORM\ManyToOne(
     *     targetEntity="ImportedBrand",
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

    public function __toString()
    {
        return (string)$this->getName();
    }
}


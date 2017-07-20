<?php

namespace Lexing\ImportedCarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImportedModel
 *
 * @ORM\Table(name="imported_model")
 * @ORM\Entity(repositoryClass="Lexing\ImportedCarBundle\Repository\ImportedModelRepository")
 */
class ImportedModel
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
     * @ORM\ManyToOne(
     *     targetEntity="ImportedSeries",
     *     inversedBy="models",
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $series;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    public function getSeries()
    {
        return $this->series;
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


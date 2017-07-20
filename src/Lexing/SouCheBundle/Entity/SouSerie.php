<?php

namespace Lexing\SouCheBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SouSerie
 *
 * @ORM\Table(name="sou_serie")
 * @ORM\Entity(repositoryClass="Lexing\SouCheBundle\Repository\SouSerieRepository")
 */
class SouSerie
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
     * @ORM\Column(name="sou_brand_id", type="string", length=255)
     */
    private $souBrandId;

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
     * @var string
     *
     * @ORM\Column(name="sou_serie_id", type="string", length=255)
     */
    private $souSerieId;

    /**
     * @var string
     *
     * @ORM\Column(name="serie_id", type="string", length=255,nullable=true)
     */
    private $serieId;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set souBrandId
     *
     * @param string $souBrandId
     *
     * @return SouSerie
     */
    public function setSouBrandId($souBrandId)
    {
        $this->souBrandId = $souBrandId;

        return $this;
    }

    /**
     * Get souBrandId
     *
     * @return string
     */
    public function getSouBrandId()
    {
        return $this->souBrandId;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SouSerie
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
     * @return SouSerie
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

    /**
     * Set souSerieId
     *
     * @param string $souSerieId
     *
     * @return SouSerie
     */
    public function setSouSerieId($souSerieId)
    {
        $this->souSerieId = $souSerieId;

        return $this;
    }

    /**
     * Get souSerieId
     *
     * @return string
     */
    public function getSouSerieId()
    {
        return $this->souSerieId;
    }
}


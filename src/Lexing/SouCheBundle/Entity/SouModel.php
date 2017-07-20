<?php

namespace Lexing\SouCheBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SouModel
 *
 * @ORM\Table(name="sou_model")
 * @ORM\Entity(repositoryClass="Lexing\SouCheBundle\Repository\SouModelRepository")
 */
class SouModel
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="sou_serie_id", type="string", length=255)
     */
    private $souSerieId;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=255, nullable=true)
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="sou_model_id", type="string", length=255)
     */
    private $souModelId;

    /**
     * 对应到xin到model表的id
     * @var string
     *
     * @ORM\Column(name="model_id", type="string", length=255,nullable=true)
     */
    private $modelId;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SouModel
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
     * Set souSerieId
     *
     * @param string $souSerieId
     *
     * @return SouModel
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

    /**
     * Set year
     *
     * @param string $year
     *
     * @return SouModel
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set souModelId
     *
     * @param string $souModelId
     *
     * @return SouModel
     */
    public function setSouModelId($souModelId)
    {
        $this->souModelId = $souModelId;

        return $this;
    }

    /**
     * Get souModelId
     *
     * @return string
     */
    public function getSouModelId()
    {
        return $this->souModelId;
    }
}


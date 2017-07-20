<?php

namespace Lexing\SouCheBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SouBrand
 *
 * @ORM\Table(name="sou_brand")
 * @ORM\Entity(repositoryClass="Lexing\SouCheBundle\Repository\SouBrandRepository")
 */
class SouBrand
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
     * @ORM\Column(name="sou_id", type="string", length=255)
     */
    private $souId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * xin表中品牌的xinid字段
     * @var string
     *
     * @ORM\Column(name="xin_id", type="string", length=255, nullable=true)
     */
    private $xinId;

    /**
     * 品牌的id
     * @var string
     *
     * @ORM\Column(name="brand_id", type="string", length=255, nullable=true)
     */
    private $brandId;

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
     * @return SouBrand
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
     * Set name
     *
     * @param string $name
     *
     * @return SouBrand
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
     * Set xinId
     *
     * @param string $xinId
     *
     * @return SouBrand
     */
    public function setXinId($xinId)
    {
        $this->xinId = $xinId;

        return $this;
    }

    /**
     * Get xinId
     *
     * @return string
     */
    public function getXinId()
    {
        return $this->xinId;
    }
}


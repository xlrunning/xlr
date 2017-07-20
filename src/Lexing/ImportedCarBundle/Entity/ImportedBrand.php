<?php

namespace Lexing\ImportedCarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ImportedBrand
 *
 * @ORM\Table(name="imported_brand")
 * @ORM\Entity(repositoryClass="Lexing\ImportedCarBundle\Repository\ImportedBrandRepository")
 */
class ImportedBrand
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return (string)$this->getName();
    }
}


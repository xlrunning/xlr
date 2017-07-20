<?php

namespace Nnv\TaxonomyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Persistence\Event\PreUpdateEventArgs;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Taxon
 * 
 * @Gedmo\Tree(type="closure")
 * @Gedmo\TreeClosure(class="Nnv\TaxonomyBundle\Entity\TaxonClosure")
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\ClosureTreeRepository")
 */
class Taxon
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
//    /**
//     * @var string
//     *
//     * @ORM\Column(name="code", type="string", length=255, nullable=true)
//     */
//    private $code;
    
    /**
     * @ORM\Column(length=64)
     */
    private $title;

    /**
     * This parameter is optional for the closure strategy
     *
     * @ORM\Column(type="integer", nullable=true)
     * @Gedmo\TreeLevel
     */
    private $level;

    /**
     * 
     * @Gedmo\TreeParent
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     * @ORM\ManyToOne(targetEntity="Taxon", inversedBy="children")
     */
    private $parent;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setParent(Taxon $parent = null)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addClosure(TaxonClosure $closure)
    {
        $this->closures[] = $closure;
    }

    public function setLevel($level)
    {
        $this->level = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }
    
    public function __toString()
    {
        return $this->title;
    }
}

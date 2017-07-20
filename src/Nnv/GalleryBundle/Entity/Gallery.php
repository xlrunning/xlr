<?php

namespace Nnv\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Gallery
 *
 * @ORM\Table(name="gallery")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Gallery
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    // private $usage;

    /**
     * 将作为管理页面的提示、说明内容显示
     * 
     * @var string
     *
     * @ORM\Column(name="about", type="text", nullable=true)
     * @JMS\Exclude
     */
    private $about;
    
    /**
     * 索引名称
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;
    
    /**
     * 一般用于提示APP用在哪里
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;
    
    /**
     * @var ArrayCollection
     * 
     * @ORM\OneToMany(
     *     targetEntity="GalleryItem",
     *     mappedBy="gallery",
     *     indexBy="id",
     *     cascade={"persist", "remove"}
     * )
     * @ORM\OrderBy({"seq"="ASC"})
     */
    private $items;
    
    // @todo item type, like suggestion
    // filename naming strategy
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="nb_items", type="integer")
     * @JMS\Exclude
     */    
    private $nbItems = 0;

    /**
     * 是否关联到某个领域对象上，比如帖子、商品
     * @var bool
     *
     * @ORM\Column(name="attached", type="boolean")
     */
    private $attached = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @JMS\Exclude
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @JMS\Exclude
     */
    private $updatedAt;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * Set about
     *
     * @param string $about
     *
     * @return Gallery
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Gallery
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
     * Set code
     *
     * @param string $code
     *
     * @return Gallery
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * Set items
     *
     * @param ArrayCollection $items
     *
     * @return Gallery
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $item->setGallery($this);
        }
        $this->items = $items;

        return $this;
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }
    
    public function addItem($item)
    {
        $item->setGallery($this);
        $this->items[] = $item;
        
        return $this;
    }
    
    public function removeItem($item)
    {
        $this->items->removeElement($item);
        
        return $this;
    }
    
    public function getNbItems()
    {
        return $this->nbItems;
    }
    
    public function setNbItems($nbItems)
    {
        $this->nbItems = $nbItems;
        
        return $this;
    }
    
    public function incNbItems()
    {
        ++$this->nbItems;
        
        return $this;
    }
    
    public function decNbItems()
    {
        --$this->nbItems;
        
        return $this;
    }

    /**
     * @return bool
     */
    public function isAttached()
    {
        return $this->attached;
    }

    /**
     * @param bool $attached
     * @return Gallery
     */
    public function setAttached($attached)
    {
        $this->attached = $attached;
        return $this;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Gallery
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Gallery
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    
    public function __toString()
    {
        return $this->id ? $this->name : '新图片集合';
    }
}


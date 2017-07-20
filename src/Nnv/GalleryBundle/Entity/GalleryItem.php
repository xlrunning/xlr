<?php

namespace Nnv\GalleryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * GalleryItem
 *
 * @ORM\Table(name="gallery_item")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class GalleryItem
{
    /**
     * 默认类型
     */
    const META_TYPE_LINK = 'link';
    const META_TYPE_NOTE = 'note';
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Gallery
     * 
     * @ORM\ManyToOne(
     *     targetEntity="Gallery",
     *     inversedBy="items",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(onDelete="CASCADE", nullable=false)
     */
    private $gallery;

    /**
     * @var string
     *
     * @ORM\Column(name="pic", type="string", length=255)
     * @JMS\Type("NnvGallery")
     */
    private $pic;
    
    /**
     * 网页链接还是系统内的entity
     * 
     * @var string
     *
     * @ORM\Column(name="meta_type", type="string", length=255, nullable=true)
     * @JMS\SerializedName("type")
     */
    private $metaType = self::META_TYPE_NOTE;
    
    /**
     * @var string
     *
     * @ORM\Column(name="meta_content", type="text", nullable=true)
     * @JMS\SerializedName("content")
     */
    private $metaContent;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="seq", type="integer")
     * @JMS\Exclude
     */
    private $seq = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="width", type="integer")
     */
    private $width = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="height", type="integer")
     */
    private $height = 0;
    
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
     * 
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->gallery->incNbItems();
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
     * @ORM\PreRemove()
     */
    public function preRemove()
    {
        $this->gallery->decNbItems();
        $picPath = __DIR__ . '/../../../../web/uploads/gallery/' . $this->getPic();
        if (file_exists($picPath)) {
            @unlink($picPath);
        }
    }

    /**
     * Set gallery
     *
     * @param Gallery $gallery
     *
     * @return GalleryItem
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;

        return $this;
    }

    /**
     * Get gallery
     *
     * @return Gallery
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set pic
     *
     * @param string $pic
     *
     * @return GalleryItem
     */
    public function setPic($pic)
    {
        $this->pic = $pic;

        return $this;
    }

    /**
     * Get pic
     *
     * @return string
     */
    public function getPic()
    {
        return $this->pic;
    }
    
    /**
     * Set metaType
     *
     * @param string $metaType
     *
     * @return GalleryItem
     */
    public function setMetaType($metaType)
    {
        $this->metaType = $metaType;

        return $this;
    }

    /**
     * Get metaType
     *
     * @return string
     */
    public function getMetaType()
    {
        return $this->metaType;
    }
    
    /**
     * Set metaContent
     *
     * @param string $metaContent
     *
     * @return GalleryItem
     */
    public function setMetaContent($metaContent)
    {
        $this->metaContent = $metaContent;

        return $this;
    }

    /**
     * Get metaContent
     *
     * @return string
     */
    public function getMetaContent()
    {
        return $this->metaContent;
    }

    /**
     * Set seq
     *
     * @param integer $seq
     *
     * @return GalleryItem
     */
    public function setSeq($seq)
    {
        $this->seq = $seq;

        return $this;
    }

    /**
     * Get seq
     *
     * @return integer
     */
    public function getSeq()
    {
        return $this->seq;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return GalleryItem
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
     * @return GalleryItem
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

    /**
     * Set width
     *
     * @param integer $width
     *
     * @return GalleryItem
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     *
     * @return GalleryItem
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer
     */
    public function getHeight()
    {
        return $this->height;
    }
}

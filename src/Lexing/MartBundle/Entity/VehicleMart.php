<?php

namespace Lexing\MartBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * VehicleMart
 *
 * @ORM\Table(name="vehicle_mart")
 * @ORM\Entity(repositoryClass="Lexing\MartBundle\Repository\VehicleMartRepository")
 */
class VehicleMart
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
     * @ORM\Column(name="addr", type="string", length=255)
     */
    private $addr;

    /**
     * 车城的管理人员
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Lexing\UserBundle\Entity\User",
     *     mappedBy="adminMart"
     * )
     */
    private $admins;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->admins = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return VehicleMart
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
     * Set addr
     *
     * @param string $addr
     *
     * @return VehicleMart
     */
    public function setAddr($addr)
    {
        $this->addr = $addr;

        return $this;
    }

    /**
     * Get addr
     *
     * @return string
     */
    public function getAddr()
    {
        return $this->addr;
    }

    /**
     * Add admin
     *
     * @param \Lexing\UserBundle\Entity\User $admin
     *
     * @return VehicleMart
     */
    public function addAdmin(\Lexing\UserBundle\Entity\User $admin)
    {
        $admin->setAdminMart($this);
        $this->admins[] = $admin;

        return $this;
    }

    /**
     * Remove admin
     *
     * @param \Lexing\UserBundle\Entity\User $admin
     */
    public function removeAdmin(\Lexing\UserBundle\Entity\User $admin)
    {
        $admin->setAdminMart(null);
        $this->admins->removeElement($admin);
    }

    /**
     * Get admins
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAdmins()
    {
        return $this->admins;
    }

    public function __toString()
    {
        return $this->id ? $this->name : '新车城';
    }
}

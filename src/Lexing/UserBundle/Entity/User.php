<?php

namespace Lexing\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexing\DealerBundle\Entity\VehicleDealer;
use Nnv\UserBundle\Entity\User as NnvUser;
use Lexing\MartBundle\Entity\VehicleMart;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="Lexing\UserBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Gedmo\Loggable(logEntryClass="Lexing\AssistBundle\Entity\LogEntry")
 */
class User extends NnvUser
{
    const ROLE_OPERATOR    = 'ROLE_OPERATOR';
    const ROLE_ACCOUNTANT  = 'ROLE_ACCOUNTANT';
    const ROLE_CONTRIBUTOR = 'ROLE_CONTRIBUTOR';

    /**
     * 如果设置了，该账户将可以进入车城管理端口
     *
     * @var VehicleMart
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\MartBundle\Entity\VehicleMart",
     *     inversedBy="admins"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $adminMart;

    /**
     * 车商的管理人员，属于哪个车城
     * // @todo 权限，不一定是管理人员，根据role确定
     *
     * @var VehicleDealer
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\DealerBundle\Entity\VehicleDealer",
     *     inversedBy="admins"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $adminDealer;

    /**
     *
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function preSave()
    {
        if (!$this->adminMart) {
            $this->removeRole('ROLE_MART_ADMIN');
        } else {
            $this->addRole('ROLE_MART_ADMIN');
        }
    }

    /**
     * Set adminMart
     *
     * @param \Lexing\MartBundle\Entity\VehicleMart $adminMart
     *
     * @return User
     */
    public function setAdminMart(VehicleMart $adminMart = null)
    {
        $this->adminMart = $adminMart;

        return $this;
    }

    /**
     * Get adminMart
     *
     * @return \Lexing\MartBundle\Entity\VehicleMart
     */
    public function getAdminMart()
    {
        return $this->adminMart;
    }

    /**
     * @return VehicleDealer
     */
    public function getAdminDealer()
    {
        return $this->adminDealer;
    }

    /**
     *
     * @param VehicleDealer $adminDealer
     * @return VehicleDealer
     */
    public function setAdminDealer(VehicleDealer $adminDealer)
    {
        $this->adminDealer = $adminDealer;

        return $this;
    }

    public function setUserRoleType($role)
    {
        $roles = array_keys(self::getUserRoleTypes());
        if (!in_array($role, $roles)) {
            return $this;
        }

        $this->addRole($role);
        return $this;
    }

    public function getUserRoleType()
    {
        $roles = array_keys(self::getUserRoleTypes());
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return $role;
            }
        }
        return null;
    }

    public static function getUserRoleTypes()
    {
        return [
            static::ROLE_OPERATOR    => '车辆上下架操作员',
            static::ROLE_ACCOUNTANT  => '财务',
            static::ROLE_CONTRIBUTOR => '资金方',
            #static::ROLE_SUPER_ADMIN => '超级管理员',
        ];
    }
}

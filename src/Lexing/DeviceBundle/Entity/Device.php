<?php

namespace Lexing\DeviceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Lexing\UserBundle\Entity\User;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity(repositoryClass="Lexing\DeviceBundle\Repository\DeviceRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Device
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * android|ios|wpos
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="uuid", type="string", length=255)
     */
    private $uuid;

    /**
     * @var User
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\UserBundle\Entity\User"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="secret", type="string", length=255)
     */
    private $secret;

    /**
     * 密钥过期时间
     * @var DateTime
     *
     * @ORM\Column(name="expired_at", type="datetime")
     */
    private $expiredAt;

    /**
     * 最近一次请求密钥的IP
     * @var string
     *
     * @ORM\Column(name="request_ip", type="string", length=255, nullable=true)
     */
    private $requestIp;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->expiredAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        // $this->updatedAt = new \DateTime();
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Device
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set uuid
     *
     * @param string $uuid
     *
     * @return Device
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * Get uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Device
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param string $secret
     * @return Device
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @param DateTime $expiredAt
     * @return Device
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getRequestIp()
    {
        return $this->requestIp;
    }

    /**
     * @param string $requestIp
     * @return Device
     */
    public function setRequestIp($requestIp)
    {
        $this->requestIp = $requestIp;

        return $this;
    }
}


<?php

namespace Nnv\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Nnv\SmsBundle\Validator\Constraints as NnvAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints as DoctrineAssert;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 * with wx and so on
 * 
 * @ORM\MappedSuperclass
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="openid", type="string", length=255, nullable=true)
     */
    protected $openid;

    /**
     * @var string
     *
     * @ORM\Column(name="nick", type="string", length=255, nullable=true)
     */
    protected $nick;

    /**
     * @var string
     * 
     * @Assert\Regex(
     *      pattern = "/^\d{11}$/",
     *      message = "手机号应为11位数字"
     * )
     * #NnvAssert\MobileVerified(groups={"Registration"})
     * @ORM\Column(name="mobile", type="string", length=32, nullable=true)
     */
    protected $mobile;

    /**
     * URL
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     */
    protected $avatar;

    /**
     * 姓名
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;
    
    /**
     * 账户余额
     * @var float
     *
     * @ORM\Column(name="balance", type="float")
     */
    private $balance = 0.0;
    
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
     * Set openid
     *
     * @param string $openid
     *
     * @return User
     */
    public function setOpenid($openid)
    {
        $this->openid = $openid;

        return $this;
    }

    /**
     * Get openid
     *
     * @return string
     */
    public function getOpenid()
    {
        return $this->openid;
    }

    /**
     * Set nick
     *
     * @param string $nick
     *
     * @return User
     */
    public function setNick($nick)
    {
        $this->nick = $nick;

        return $this;
    }

    /**
     * Get nick
     *
     * @return string
     */
    public function getNick()
    {
        return $this->nick;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     *
     * @return User
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return User
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
     * Set balance
     *
     * @param float $balance
     *
     * @return User
     */
    public function setBalance($balance)
    {
        if ($balance < 0) {
            $balance = 0;
        }
        $this->balance = $balance;
        
        return $this;
    }

    /**
     * Get balance
     *
     * @return float
     */
    public function getBalance()
    {
        return round($this->balance, 2);
    }

    /**
     * 
     * @param float $amount
     * @return User
     */
    public function deposit($amount)
    {
        if ($amount > 0) {
            $this->balance += $amount;
        }
        
        return $this;
    }
    
    /**
     * 
     * @param float $amount
     * @return User
     */
    public function consume($amount)
    {
        if ($amount > 0) {
            $this->balance -= $amount;
        }
        
        return $this;
    }
    
    public function __toString()
    {
        if ($this->name) {
            return $this->name;
        }
        if ($this->nick) {
            return $this->nick;
        }
        return $this->username ? $this->username : '新用户';
    }
}


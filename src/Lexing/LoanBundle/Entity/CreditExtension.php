<?php

namespace Lexing\LoanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexing\DealerBundle\Entity\VehicleDealer;

/**
 * CreditExtension
 *
 * 授信
 * // @todo 有效期，到某个期限需要重新授信？
 *
 * @ORM\Table(name="credit_extension")
 * @ORM\Entity(repositoryClass="Lexing\LoanBundle\Repository\CreditExtensionRepository")
 */
class CreditExtension
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
     *
     * @var VehicleDealer
     *
     * @ORM\OneToOne(
     *     targetEntity="Lexing\DealerBundle\Entity\VehicleDealer",
     *     mappedBy="creditExtension"
     * )
     */
    private $dealer;

    /**
     * 授信额度
     *
     * @var int
     *
     * @ORM\Column(name="quota", type="integer")
     */
    private $quota;

    /**
     * @var int
     *
     * @ORM\Column(name="used_amount", type="integer")
     */
    private $usedAmount = 0;


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
     * Set dealer
     *
     * @param VehicleDealer $dealer
     *
     * @return CreditExtension
     */
    public function setDealer($dealer)
    {
        $this->dealer = $dealer;
        $dealer->setCreditExtension($this);

        return $this;
    }

    /**
     * Get dealer
     *
     * @return VehicleDealer
     */
    public function getDealer()
    {
        return $this->dealer;
    }

    /**
     * Set quota
     *
     * @param integer $quota
     *
     * @return CreditExtension
     */
    public function setQuota($quota)
    {
        $this->quota = $quota;

        return $this;
    }

    /**
     * Get quota
     *
     * @return int
     */
    public function getQuota()
    {
        return $this->quota;
    }

    /**
     * Set usedAmount
     *
     * @param integer $usedAmount
     *
     * @return CreditExtension
     */
    public function setUsedAmount($usedAmount)
    {
        $this->usedAmount = $usedAmount;

        return $this;
    }

    /**
     * Get usedAmount
     *
     * @return int
     */
    public function getUsedAmount()
    {
        return $this->usedAmount;
    }
}


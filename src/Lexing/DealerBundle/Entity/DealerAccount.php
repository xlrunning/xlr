<?php

namespace Lexing\DealerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * DealerAccount
 *
 * @ORM\Table(name="dealer_account")
 * @ORM\Entity(repositoryClass="Lexing\DealerBundle\Repository\DealerAccountRepository")
 */
class DealerAccount
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
     *
     * @var VehicleDealer
     *
     * @ORM\OneToOne(
     *     targetEntity="Lexing\DealerBundle\Entity\VehicleDealer"
     * )
     */
    private $dealer;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_name", type="string", length=255)
     */
    private $bankName;

    /**
     * @var string
     *
     * @ORM\Column(name="account_name", type="string", length=255)
     */
    private $accountName;

    /**
     * @var string
     *
     * @ORM\Column(name="account_number", type="string", length=255)
     */
    private $accountNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="account_mobile", type="string", length=255, nullable=true)
     */
    private $accountMobile;


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
     * @return mixed
     */
    public function getDealer()
    {
        return $this->dealer;
    }

    /**
     * @param mixed $dealer
     * @return DealerAccount
     */
    public function setDealer($dealer)
    {
        $this->dealer = $dealer;
        return $this;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     *
     * @return DealerAccount
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set accountName
     *
     * @param string $accountName
     *
     * @return DealerAccount
     */
    public function setAccountName($accountName)
    {
        $this->accountName = $accountName;

        return $this;
    }

    /**
     * Get accountName
     *
     * @return string
     */
    public function getAccountName()
    {
        return $this->accountName;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     *
     * @return DealerAccount
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set accountMobile
     *
     * @param string $accountMobile
     *
     * @return DealerAccount
     */
    public function setAccountMobile($accountMobile)
    {
        $this->accountMobile = $accountMobile;

        return $this;
    }

    /**
     * Get accountMobile
     *
     * @return string
     */
    public function getAccountMobile()
    {
        return $this->accountMobile;
    }
}


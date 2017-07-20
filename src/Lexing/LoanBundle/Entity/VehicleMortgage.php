<?php

namespace Lexing\LoanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexing\VehicleBundle\Entity\Vehicle;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * VehicleMortgage
 * 借款和车辆绑定，一辆车借一份钱
 * @todo 批准抵押贷款时对应的车辆评估价格
 *
 * @ORM\Table(name="vehicle_mortgage")
 * @ORM\Entity(repositoryClass="Lexing\LoanBundle\Repository\VehicleMortgageRepository")
 * @Gedmo\Loggable(logEntryClass="Lexing\AssistBundle\Entity\LogEntry")
 */
class VehicleMortgage
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
     * @var Vehicle
     *
     * @ORM\OneToOne(
     *     targetEntity="Lexing\VehicleBundle\Entity\Vehicle",
     *     mappedBy="mortgage"
     * )
     */
    private $vehicle;

    /**
     * 元
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     * @Gedmo\Versioned
     */
    private $amount;

    /**
     * 借款ID用于银行打款备注，方便对账
     * @var string
     *
     * @ORM\Column(name="loan_remark_identifier", type="string", length=16)
     */
    private $loanRemarkIdentifier;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="repaid_at", type="datetime", nullable=true)
     * @Gedmo\Versioned
     */
    private $repaidAt;
 
    /**
     * 还款银行交易号
     * @var string
     *
     * @ORM\Column(name="repayment_trans_no", type="string", length=128, nullable=true)
     * @Gedmo\Versioned
     */
    private $repaymentTransNo;

    /**
     * 备注
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $note;

    /**
     * 还款金额
     * @var float
     *
     * @ORM\Column(name="repaid_amount", type="float", nullable=true)
     * @Gedmo\Versioned
     */
    private $repaidAmount;

    /**
     * 关联LoanProduct
     * @var LoanProduct
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\LoanBundle\Entity\LoanProduct",
     *     inversedBy="products"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $loanProduct;

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
     * Set vehicle
     *
     * @param Vehicle $vehicle
     *
     * @return VehicleMortgage
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;
        $vehicle->setMortgage($this);

        return $this;
    }

    /**
     * Get vehicle
     *
     * @return Vehicle
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return VehicleMortgage
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set amountIn10K
     *
     * @param float $amountIn10K
     *
     * @return VehicleMortgage
     */
    public function setAmountIn10K($amountIn10K)
    {
        $this->amount = $amountIn10K*10000;

        return $this;
    }

    /**
     * Get amountIn10K
     *
     * @return float
     */
    public function getAmountIn10K()
    {
        return $this->amount/10000;
    }

    /**
     * @return string
     */
    public function getLoanRemarkIdentifier()
    {
        return $this->loanRemarkIdentifier;
    }

    /**
     * @param string $loanRemarkIdentifier
     * @return VehicleMortgage
     */
    public function setLoanRemarkIdentifier($loanRemarkIdentifier)
    {
        $this->loanRemarkIdentifier = $loanRemarkIdentifier;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getRepaidAt()
    {
        return $this->repaidAt;
    }

    /**
     * @param DateTime $repaidAt
     * @return VehicleMortgage
     */
    public function setRepaidAt($repaidAt)
    {
        $this->repaidAt = $repaidAt;
        return $this;
    }

    /**
     * @return string
     */
    public function getRepaymentTransNo()
    {
        return $this->repaymentTransNo;
    }

    /**
     * @param string $repaymentTransNo
     * @return VehicleMortgage
     */
    public function setRepaymentTransNo($repaymentTransNo)
    {
        $this->repaymentTransNo = $repaymentTransNo;
        return $this;
    }

    /**
     * Is repaid
     *
     * @return bool
     */
    public function isRepaid()
    {
        return $this->repaymentTransNo !== null;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     * @return VehicleMortgage
     */
    public function setNote($note)
    {
        $this->note = $note;
        return $this;
    }

    /**
     * @return float
     */
    public function getRepaidAmount()
    {
        return $this->repaidAmount;
    }

    /**
     * @param float $repaidAmount
     * @return VehicleMortgage
     */
    public function setRepaidAmount($repaidAmount)
    {
        $this->repaidAmount = $repaidAmount;
        return $this;
    }

    /**
     * Set loanProduct
     *
     * @param \Lexing\LoanBundle\Entity\LoanProduct $loanProduct
     *
     * @return VehicleMortgage
     */
    public function setLoanProduct(\Lexing\LoanBundle\Entity\LoanProduct $loanProduct = null)
    {
        $this->loanProduct = $loanProduct;

        return $this;
    }

    /**
     * Get loanProduct
     *
     * @return \Lexing\LoanBundle\Entity\LoanProduct
     */
    public function getLoanProduct()
    {
        return $this->loanProduct;
    }

    public function __toString()
    {
        return $this->repaymentTransNo ? '车辆借还款' : '车辆还款';
    }

}


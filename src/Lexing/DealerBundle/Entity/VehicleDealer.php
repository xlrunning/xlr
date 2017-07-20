<?php

namespace Lexing\DealerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexing\LoanBundle\Entity\CreditExtension;
use Lexing\MartBundle\Entity\VehicleMart;
use Lexing\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * VehicleDealer
 *
 * @ORM\Table(name="vehicle_dealer")
 * @ORM\Entity(repositoryClass="Lexing\DealerBundle\Repository\VehicleDealerRepository")
 * @Gedmo\Loggable(logEntryClass="Lexing\AssistBundle\Entity\LogEntry")
 */
class VehicleDealer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    // type类型，私人、个体户、公司

    /**
     * 客户名称
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * 客户简称
     * @var string
     *
     * @ORM\Column(name="short_name", type="string", length=255)
     */
    private $shortName;


    /**
     * @var VehicleMart
     * 客户归属车城
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\MartBundle\Entity\VehicleMart",
     *     inversedBy="dealers"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $mart;

    /**
     * 客户编码
     * @var string
     *
     * @ORM\Column(name="customer_no", type="string", length=255, nullable=true)
     */
    private $customerNo;

    /**
     * 场地编码
     * @var string
     *
     * @ORM\Column(name="site_no", type="string", length=255, nullable=true)
     */
    private $siteNo;

    /**
     * 联系电话
     * @var string
     *
     * @ORM\Column(name="contact_phone", type="json_array", length=255, nullable=true)
     */
    private $contactPhone;

    /**
     * 平方单价
     * @var float
     *
     * @ORM\Column(name="square_price", type="float", nullable=true)
     */
    private $squarePrice;

    /**
     * 租用面积
     * @var float
     *
     * @ORM\Column(name="rented", type="float", nullable=true)
     */
    private $rented;

    /**
     * 保证金
     * @var float
     *
     * @ORM\Column(name="deposit", type="float", nullable=true)
     */
    private $deposit;

    /**
     * 授信金额
     * @var float
     *
     * @ORM\Column(name="credit_extension_amount", type="float", nullable=true)
     * @Gedmo\Versioned()
     */
    private $creditExtensionAmount;

    /**
     * 商城车辆总数
     * @var integer
     *
     * @ORM\Column(name="nb_total_vehicles", type="integer", nullable=true)
     */
    private $nbTotalVehicles = 0;

    /**
     * 商城车辆上架总数
     * @var integer
     *
     * @ORM\Column(name="nb_on_sale_vehicles", type="integer", nullable=true)
     */
    private $nbOnSaleVehicles = 0;

    /**
     * 商城车辆下架总数
     * @var integer
     *
     * @ORM\Column(name="nb_off_sale_vehicles", type="integer", nullable=true)
     */
    private $nbOffSaleVehicles = 0;

    /**
     * 商城已借款总额
     * @var float
     *
     * @ORM\Column(name="used_credit", type="float", nullable=true)
     */
    private $usedCredit = 0;

    /**
     * @todo 车商的管理人员还是员工，用户角色限定
     *
     * @var User
     *
     * @ORM\OneToMany(
     *     targetEntity="Lexing\UserBundle\Entity\User",
     *     mappedBy="adminDealer"
     * )
     */
    private $admins;

    //private $nb

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
     * @return VehicleDealer
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
     * Set mart
     *
     * @param VehicleMart $mart
     *
     * @return VehicleDealer
     */
    public function setMart(VehicleMart $mart)
    {
        $this->mart = $mart;

        return $this;
    }

    /**
     * Get mart
     *
     * @return VehicleMart
     */
    public function getMart()
    {
        return $this->mart;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getCustomerNo()
    {
        return $this->customerNo;
    }

    /**
     * @param string $customerNo
     */
    public function setCustomerNo($customerNo)
    {
        $this->customerNo = $customerNo;
    }

    /**
     * @return string
     */
    public function getSiteNo()
    {
        return $this->siteNo;
    }

    /**
     * @param string $siteNo
     */
    public function setSiteNo($siteNo)
    {
        $this->siteNo = $siteNo;
    }



    /**
     * @return float
     */
    public function getSquarePrice()
    {
        return $this->squarePrice;
    }

    /**
     * @param float $squarePrice
     */
    public function setSquarePrice($squarePrice)
    {
        $this->squarePrice = $squarePrice;
    }

    /**
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * @param string $contactPhone
     */
    public function setContactPhone($contactPhone)
    {
        $this->contactPhone = $contactPhone;
    }

    /**
     * @return float
     */
    public function getRented()
    {
        return $this->rented;
    }

    /**
     * @param float $rented
     */
    public function setRented($rented)
    {
        $this->rented = $rented;
    }

    /**
     * @return float
     */
    public function getDeposit()
    {
        return $this->deposit;
    }

    /**
     * @param float $deposit
     */
    public function setDeposit($deposit)
    {
        $this->deposit = $deposit;
    }

    /**
     * @return CreditExtensionAmount
     */
    public function getCreditExtensionAmount()
    {
        return $this->creditExtensionAmount;
    }

    /**
     * @param CreditExtensionAmount $creditExtensionAmount
     * @return VehicleDealer
     */
    public function setCreditExtensionAmount($creditExtensionAmount)
    {
        $this->creditExtensionAmount = $creditExtensionAmount;
        return $this;
    }
    
    /**
     * @return CreditExtensionAmountIn10K
     */
    public function getCreditExtensionAmountIn10K()
    {
        return $this->creditExtensionAmount/10000;
    }
    
    /**
     * @param CreditExtensionAmountIn10K $creditExtensionAmountIn10K
     * @return VehicleDealer
     */
    public function setCreditExtensionAmountIn10K($creditExtensionAmountIn10K)
    {
        $this->creditExtensionAmount = 10000*$creditExtensionAmountIn10K;
        return $this;
    }
    
    /**
     * Add admin
     *
     * @param \Lexing\UserBundle\Entity\User $admin
     *
     * @return VehicleMart
     */
    public function addAdmin(User $admin)
    {
        $admin->setAdminDealer($this);
        $this->admins[] = $admin;

        return $this;
    }

    /**
     * Remove admin
     *
     * @param \Lexing\UserBundle\Entity\User $admin
     */
    public function removeAdmin(User $admin)
    {
        $admin->setAdminDealer(null);
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
        return $this->id ? $this->name : '新车商';
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->admins = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set nbTotalVehicles
     *
     * @param integer $nbTotalVehicles
     *
     * @return VehicleDealer
     */
    public function setNbTotalVehicles($nbTotalVehicles)
    {
        $this->nbTotalVehicles = $nbTotalVehicles;

        return $this;
    }

    /**
     * Get nbTotalVehicles
     *
     * @return integer
     */
    public function getNbTotalVehicles()
    {
        return $this->nbTotalVehicles;
    }

    /**
     * Set nbOnSaleVehicles
     *
     * @param integer $nbOnSaleVehicles
     *
     * @return VehicleDealer
     */
    public function setNbOnSaleVehicles($nbOnSaleVehicles)
    {
        $this->nbOnSaleVehicles = $nbOnSaleVehicles;

        return $this;
    }

    /**
     * Get nbOnSaleVehicles
     *
     * @return integer
     */
    public function getNbOnSaleVehicles()
    {
        return $this->nbOnSaleVehicles;
    }

    /**
     * Set nbOffSaleVehicles
     *
     * @param integer $nbOffSaleVehicles
     *
     * @return VehicleDealer
     */
    public function setNbOffSaleVehicles($nbOffSaleVehicles)
    {
        $this->nbOffSaleVehicles = $nbOffSaleVehicles;

        return $this;
    }

    /**
     * Get nbOffSaleVehicles
     *
     * @return integer
     */
    public function getNbOffSaleVehicles()
    {
        return $this->nbOffSaleVehicles;
    }

    /**
     * Set usedCredit
     *
     * @param float $usedCredit
     *
     * @return VehicleDealer
     */
    public function setUsedCredit($usedCredit)
    {
        $this->usedCredit = $usedCredit;

        return $this;
    }

    /**
     * Get usedCredit
     *
     * @return float
     */
    public function getUsedCredit()
    {
        return $this->usedCredit;
    }
    
    /**
     * Get usedCreditIn10K
     *
     * @return float
     */
    public function getUsedCreditIn10K()
    {
        return $this->usedCredit/10000;
    }
    
    /**
     * Get availableCredit
     *
     * @return float
     */
    public function getAvailableCredit()
    {
        return $this->creditExtensionAmount - $this->usedCredit;
    }
    
    /**
     * Get availableCreditIn10K
     *
     * @return float
     */
    public function getAvailableCreditIn10K()
    {
        return ($this->creditExtensionAmount - $this->usedCredit)/10000;
    }
    
    public function incNbTotalVehicles()
    {
        ++$this->nbTotalVehicles;
        return $this;
    }

    public function incNbOnSaleVehiclesOnly()
    {
        ++$this->nbOnSaleVehicles;
        return $this;
    }

    public function incNbOnSaleVehicles()
    {
        ++$this->nbOnSaleVehicles;
        --$this->nbOffSaleVehicles;
        return $this;
    }

    public function incNbOffSaleVehicles()
    {
        ++$this->nbOffSaleVehicles;
        --$this->nbOnSaleVehicles;
        return $this;
    }

    public function changeUsedCredit($mortgageAmount)
    {
        $this->usedCredit = $this->usedCredit + $mortgageAmount;
        return $this;
    }
}

<?php

namespace Lexing\LoanBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Lexing\DealerBundle\Entity\VehicleDealer;

/**
 * CreditApplication
 *
 * // @todo user，实际哪个用户发起的
 *
 * @ORM\Table(name="credit_application")
 * @ORM\Entity(repositoryClass="Lexing\LoanBundle\Repository\CreditApplicationRepository")
 */
class CreditApplication
{
    use TimestampableEntity;

    const STATE_APPROVED = 'approved';
    const STATE_UNDERWAY = 'underway';
    // 等待申请者提供更多材料？工作流
    const STATE_REJECTED = 'rejected';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var VehicleDealer
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\DealerBundle\Entity\VehicleDealer",
     *     inversedBy="creditApplications"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $dealer;

    /**
     * 是首次授信申请还是提升额度申请
     * @var bool
     *
     * @ORM\Column(name="initial", type="boolean")
     */
    private $initial = true;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state = self::STATE_UNDERWAY;

    /**
     * 演示用 -- 工作流的任务
     * @var array
     *
     * @ORM\Column(name="steps", type="array")
     */
    private $steps;

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
     * @return CreditApplication
     */
    public function setDealer($dealer)
    {
        $this->dealer = $dealer;

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
     * Set initial
     *
     * @param boolean $initial
     *
     * @return CreditApplication
     */
    public function setInitial($initial)
    {
        $this->initial = $initial;

        return $this;
    }

    /**
     * Get initial
     *
     * @return bool
     */
    public function getInitial()
    {
        return $this->initial;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return CreditApplication
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set steps
     *
     * @param array $steps
     *
     * @return CreditApplication
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;

        return $this;
    }

    /**
     * Get steps
     *
     * @return array
     */
    public function getSteps()
    {
        return $this->steps;
    }

    public function isApproved()
    {
        return $this->state == self::STATE_APPROVED;
    }

    public function __toString()
    {
        return $this->id ? $this->dealer . '的授信申请' : '新授信申请';
    }
}


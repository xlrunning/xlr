<?php

namespace Lexing\TradeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Lexing\VehicleBundle\Entity\Vehicle;

/**
 * VehicleSale
 * 消费者买车相关交易
 * 定金、首付、尾款、全款
 *
 * @ORM\Table(name="vehicle_sale")
 * @ORM\Entity
 */
class VehicleSale
{
    use TimestampableEntity;

    /**
     * 定金
     * 一般几千元
     */
    const TYPE_PAID_FRONT = 'paid.front';

    /**
     * 首付款
     * 一般10%-50%
     */
    const TYPE_PAID_FIRST = 'paid.first';

    /**
     * 尾款
     * @todo 需要和首付款呼应
     */
    const TYPE_PAID_FINAL = 'paid.final';

    /**
     * 全款
     */
    const TYPE_PAID_FULL  = 'paid.full';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    protected $type;

    /**
     * @var Vehicle
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\VehicleBundle\Entity\Vehicle",
     *     inversedBy="sales"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $vehicle;

    /**
     * @var Trade
     * 只关联成功支付的订单
     *
     * @ORM\OneToOne(
     *     targetEntity="Lexing\TradeBundle\Entity\Trade",
     *     fetch="EAGER",
     *     cascade={"persist"}
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $trade;

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     *
     * @param string $type
     * @return VehicleSale
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Set vehicle
     *
     * @param Vehicle $vehicle
     *
     * @return VehicleTrade
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;

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
     *
     * @param Trade $trade
     * @return Trade
     */
    public function setTrade($trade)
    {
        $this->trade = $trade;

        return $this;
    }

    /**
     *
     * @return Trade
     */
    public function getTrade()
    {
        return $this->trade;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->getTrade()->getTotalFee();
    }
}


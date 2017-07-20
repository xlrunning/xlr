<?php

namespace Lexing\VehicleBundle\Traits;

trait XinEntity
{
    /**
     * 优信数据中的id，方便抓取校验
     * @var int
     *
     * @ORM\Column(name="xin_id", type="integer", unique=true, nullable=false)
     */
    private $xinId;

    /**
     * Set xinId
     *
     * @param integer $xinId
     *
     * @return $this
     */
    public function setXinId($xinId)
    {
        $this->xinId = intval($xinId);

        return $this;
    }

    /**
     * Get xinId
     *
     * @return int
     */
    public function getXinId()
    {
        return $this->xinId;
    }
}
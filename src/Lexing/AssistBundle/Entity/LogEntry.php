<?php

namespace Lexing\AssistBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;

/**
 * LogEntry
 *
 * @ORM\Table(
 *     name="log_entry",
 *  indexes={
 *      @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *      @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="Lexing\AssistBundle\Repository\LogEntryRepository")
 */
class LogEntry extends AbstractLogEntry
{
    /**
     * @var string $action
     *
     * @ORM\Column(type="string", length=64)
     */
    protected $action;

    /**
     * 谁干的
     * @var User
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\UserBundle\Entity\User"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $user;

    /**
     * @var Vehicle|VehicleDealer|VehicleMortgage
     */
    private $object;

    /**
     * Set user
     *
     * @param User $user
     *
     * @return LogEntry
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

    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    public function getObject()
    {
        return $this->object;
    }

    public function getDataByKey($key)
    {

    }
}


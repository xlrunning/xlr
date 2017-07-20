<?php

namespace Lexing\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexing\UserBundle\Entity\User;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * TaskEntry
 *
 * @ORM\Table(name="task_entry")
 * @ORM\Entity(repositoryClass="Lexing\WorkflowBundle\Repository\TaskEntryRepository")
 */
class TaskEntry
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
     * @var Task
     * 所属task
     * @ORM\ManyToOne(
     *     targetEntity="Task",
     *     inversedBy="entries"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $task;

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
     * @ORM\Column(name="identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @var bool
     *
     * @ORM\Column(name="rejected", type="boolean")
     */
    private $rejected = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="overrided", type="boolean")
     */
    private $overrided = false;


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
     * Set identifier
     *
     * @param string $identifier
     *
     * @return TaskEntry
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return TaskEntry
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return TaskEntry
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return TaskEntry
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set rejected
     *
     * @param boolean $rejected
     *
     * @return TaskEntry
     */
    public function setRejected($rejected)
    {
        $this->rejected = $rejected;

        return $this;
    }

    /**
     * Get rejected
     *
     * @return bool
     */
    public function getRejected()
    {
        return $this->rejected;
    }

    /**
     * Set overrided
     *
     * @param boolean $overrided
     *
     * @return TaskEntry
     */
    public function setOverrided($overrided)
    {
        $this->overrided = $overrided;

        return $this;
    }

    /**
     * Get overrided
     *
     * @return bool
     */
    public function getOverrided()
    {
        return $this->overrided;
    }

    /**
     * Set task
     *
     * @param \Lexing\WorkflowBundle\Entity\Task $task
     *
     * @return TaskEntry
     */
    public function setTask(\Lexing\WorkflowBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \Lexing\WorkflowBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set user
     *
     * @param \Lexing\UserBundle\Entity\User $user
     *
     * @return TaskEntry
     */
    public function setUser(\Lexing\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Lexing\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}

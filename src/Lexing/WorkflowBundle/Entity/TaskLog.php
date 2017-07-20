<?php

namespace Lexing\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * TaskLog
 *
 * @ORM\Table(name="task_log")
 * @ORM\Entity(repositoryClass="Lexing\WorkflowBundle\Repository\TaskLogRepository")
 */
class TaskLog
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
     *     inversedBy="logs"
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
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;


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
     * Set type
     *
     * @param string $type
     *
     * @return TaskLog
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
     * Set content
     *
     * @param string $content
     *
     * @return TaskLog
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
     * Set task
     *
     * @param \Lexing\WorkflowBundle\Entity\Task $task
     *
     * @return TaskLog
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
     * @return TaskLog
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

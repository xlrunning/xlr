<?php

namespace Lexing\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * TaskAssignment
 * 绝大多数都是一个任务一个执行人
 *
 * @ORM\Table(name="task_assignment")
 * @ORM\Entity(repositoryClass="Lexing\WorkflowBundle\Repository\TaskAssignmentRepository")
 */
class TaskAssignment
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
     *     inversedBy="assignments"
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
     * 谁分派了任务，NULL如果是系统根据workflow和task定义自动分配
     *
     * @var User
     *
     * @ORM\ManyToOne(
     *     targetEntity="Lexing\UserBundle\Entity\User"
     * )
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $assignedBy;

    /**
     * @var int
     *
     * @ORM\Column(name="priority", type="integer")
     */
    private $priority = 10;

    /**
     * @var bool
     *
     * @ORM\Column(name="abandoned", type="boolean")
     */
    private $abandoned = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = false;


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
     * Set priority
     *
     * @param integer $priority
     *
     * @return TaskAssignment
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set abandoned
     *
     * @param boolean $abandoned
     *
     * @return TaskAssignment
     */
    public function setAbandoned($abandoned)
    {
        $this->abandoned = $abandoned;

        return $this;
    }

    /**
     * Get abandoned
     *
     * @return bool
     */
    public function getAbandoned()
    {
        return $this->abandoned;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return TaskAssignment
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set task
     *
     * @param \Lexing\WorkflowBundle\Entity\Task $task
     *
     * @return TaskAssignment
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
     * @return TaskAssignment
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

    /**
     * Set assignedBy
     *
     * @param \Lexing\UserBundle\Entity\User $assignedBy
     *
     * @return TaskAssignment
     */
    public function setAssignedBy(\Lexing\UserBundle\Entity\User $assignedBy = null)
    {
        $this->assignedBy = $assignedBy;

        return $this;
    }

    /**
     * Get assignedBy
     *
     * @return \Lexing\UserBundle\Entity\User
     */
    public function getAssignedBy()
    {
        return $this->assignedBy;
    }
}

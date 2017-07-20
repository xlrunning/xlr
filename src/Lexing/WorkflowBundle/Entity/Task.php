<?php

namespace Lexing\WorkflowBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="Lexing\WorkflowBundle\Repository\TaskRepository")
 *
 * // @todo unique index of (workflow_id, identifier)
 */
class Task
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
     * @var Workflow
     * 所属workflow
     * @ORM\ManyToOne(
     *     targetEntity="Workflow",
     *     inversedBy="tasks"
     * )
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $workflow;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Lexing\WorkflowBundle\Entity\TaskAssignment",
     *     mappedBy="task"
     * )
     */
    private $assignments;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Lexing\WorkflowBundle\Entity\TaskEntry",
     *     mappedBy="task",
     *     indexBy="identifier"
     * )
     */
    private $entries;

    /**
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="Lexing\WorkflowBundle\Entity\TaskLog",
     *     mappedBy="task"
     * )
     */
    private $logs;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=255)
     */
    private $identifier;

    /**
     * @var int
     *
     * @ORM\Column(name="progress", type="integer")
     */
    private $progress = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="completed_at", type="datetime", nullable=true)
     */
    private $completedAt;

    /**
     * 耗时，秒
     * @var integer
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * Constructor
     */
    public function __construct($identifier = null)
    {
        $this->identifier = $identifier;

        $this->assignments = new ArrayCollection();
        $this->entries = new ArrayCollection();
        $this->logs = new ArrayCollection();
    }

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
     * @return Task
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
     * Set progress
     *
     * @param integer $progress
     *
     * @return Task
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return int
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set completedAt
     *
     * @param \DateTime $completedAt
     *
     * @return Task
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;

        return $this;
    }

    /**
     * Get completedAt
     *
     * @return \DateTime
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     *
     * @return Task
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return integer
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set workflow
     *
     * @param \Lexing\WorkflowBundle\Entity\Workflow $workflow
     *
     * @return Task
     */
    public function setWorkflow(\Lexing\WorkflowBundle\Entity\Workflow $workflow = null)
    {
        $this->workflow = $workflow;

        return $this;
    }

    /**
     * Get workflow
     *
     * @return \Lexing\WorkflowBundle\Entity\Workflow
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Add entry
     *
     * @param \Lexing\WorkflowBundle\Entity\TaskEntry $entry
     *
     * @return Task
     */
    public function addEntry(\Lexing\WorkflowBundle\Entity\TaskEntry $entry)
    {
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * Remove entry
     *
     * @param \Lexing\WorkflowBundle\Entity\TaskEntry $entry
     */
    public function removeEntry(\Lexing\WorkflowBundle\Entity\TaskEntry $entry)
    {
        $this->entries->removeElement($entry);
    }

    /**
     * Get entries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * Add log
     *
     * @param \Lexing\WorkflowBundle\Entity\TaskLog $log
     *
     * @return Task
     */
    public function addLog(\Lexing\WorkflowBundle\Entity\TaskLog $log)
    {
        $this->logs[] = $log;

        return $this;
    }

    /**
     * Remove log
     *
     * @param \Lexing\WorkflowBundle\Entity\TaskLog $log
     */
    public function removeLog(\Lexing\WorkflowBundle\Entity\TaskLog $log)
    {
        $this->logs->removeElement($log);
    }

    /**
     * Get logs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * Add assignment
     *
     * @param \Lexing\WorkflowBundle\Entity\TaskAssignment $assignment
     *
     * @return Task
     */
    public function addAssignment(\Lexing\WorkflowBundle\Entity\TaskAssignment $assignment)
    {
        $this->assignments[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment
     *
     * @param \Lexing\WorkflowBundle\Entity\TaskAssignment $assignment
     */
    public function removeAssignment(\Lexing\WorkflowBundle\Entity\TaskAssignment $assignment)
    {
        $this->assignments->removeElement($assignment);
    }

    /**
     * Get assignments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignments()
    {
        return $this->assignments;
    }
}

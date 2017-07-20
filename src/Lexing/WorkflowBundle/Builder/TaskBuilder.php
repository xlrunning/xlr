<?php

namespace Lexing\WorkflowBundle\Builder;

use Lexing\WorkflowBundle\Definition\TaskDefinition;
use Lexing\WorkflowBundle\Entity\Task;
use Lexing\WorkflowBundle\Entity\Workflow;

/**
 * Class TaskBuilder
 * @package Lexing\WorkflowBundle\Builder
 */
class TaskBuilder
{
    // build task from its definition

    public function __construct()
    {

    }

    public function build(TaskDefinition $definition, Workflow $workflow)
    {
        $task = new Task($definition->getIdentifier());
        $task->setWorkflow($workflow);
        // @todo 分派参与者

        return $task;
    }
}
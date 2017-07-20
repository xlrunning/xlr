<?php

namespace Lexing\WorkflowBundle\Definition;

use Lexing\WorkflowBundle\Exception\DuplicatedDefinitionException;

/**
 * class WorkflowDefinition
 */
class WorkflowDefinition extends AbstractDefinition
{
    /**
     * @var array
     */
    private $taskDefinitions = [];

    // @todo entry idenfitier也不能重复？
    public function addTaskDefinition(TaskDefinition $taskDef)
    {
        if (isset($this->taskDefinitions[$taskDef->getIdentifier()])) {
            throw new DuplicatedDefinitionException('task definition duplicated');
        }
        $this->taskDefinitions[$taskDef->getIdentifier()] = $taskDef;

        return $this;
    }

    public function addTaskDefinitions($taskDefs)
    {
        foreach ($taskDefs as $taskDef) {
            $this->addTaskDefinition($taskDef);
        }
        return $this;
    }

    public function getTaskDefinition($identifier)
    {
        return isset($this->taskDefinitions[$identifier]) ? $this->taskDefinitions[$identifier] : null;
    }

    public function getTaskDefinitions()
    {
        return $this->taskDefinitions;
    }

    public function getFirstTaskDefinition()
    {
        return reset($this->taskDefinitions);
    }

    public function getNextTaskDefinition()
    {

    }
}
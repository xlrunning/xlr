<?php

namespace Lexing\WorkflowBundle\Definition;

use Lexing\WorkflowBundle\Exception\DuplicatedDefinitionException;

/**
 * class TaskDefinition
 */
class TaskDefinition extends AbstractDefinition
{
    /**
     * @var array
     */
    private $taskEntryDefinitions = [];

    public function addTaskEntryDefinition(TaskEntryDefinition $taskEntryDef)
    {
        if (isset($this->taskEntryDefinitions[$taskEntryDef->getIdentifier()])) {
            throw new DuplicatedDefinitionException('task entry definition duplicated');
        }
        $this->taskEntryDefinitions[$taskEntryDef->getIdentifier()] = $taskEntryDef;

        return $this;
    }

    public function getTaskEntryDefinition($identifier)
    {
        return isset($this->taskEntryDefinitions[$identifier]) ? $this->taskEntryDefinitions[$identifier] : null;
    }

    public function getTaskEntryDefinitions()
    {
        return $this->taskEntryDefinitions;
    }
}
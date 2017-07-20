<?php

namespace Lexing\WorkflowBundle;

use Lexing\WorkflowBundle\Definition\WorkflowDefinition;

/**
 * Class WorkflowRegistry
 * @package Lexing\WorkflowBundle
 *
 * 注册workflow definition
 */
class WorkflowRegistry
{
    /**
     * @var array
     */
    private $workflowDefinitions = [];

    public function addWorkflowDefinition(WorkflowDefinition $wfDef)
    {
        $this->workflowDefinitions[$wfDef->getIdentifier()] = $wfDef;

        return $this;
    }

    public function getWorkflowDefinition($identifier)
    {
        return isset($this->workflowDefinitions[$identifier]) ? $this->workflowDefinitions[$identifier] : null;
    }
}
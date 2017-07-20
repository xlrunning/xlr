<?php

namespace Lexing\WorkflowBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class WorkflowEvent
 * @package Lexing\WorkflowBundle\Event
 */
class WorkflowEvent extends Event
{
    private $identifier;

    public function __construct($workflowIdentifier)
    {
        $this->identifier = $workflowIdentifier;
    }

    public function getWorkflowIdentifier()
    {
        return $this->identifier;
    }
}
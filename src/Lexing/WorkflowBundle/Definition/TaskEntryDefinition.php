<?php

namespace Lexing\WorkflowBundle\Definition;


/**
 * class TaskEntryDefinition
 */
class TaskEntryDefinition extends AbstractDefinition
{
    const TYPE_IMG  = 'img';
    const TYPE_VIDEO  = 'video';
    const TYPE_DOC  = 'doc';
    const TYPE_TEXT = 'text';
    const TYPE_FROM = 'form';

    private $type;

    private $label;

    public function __construct($identifier, $type, $label)
    {
        parent::__construct($identifier);

        $this->type  = $type;
        $this->label = $label;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getLabel()
    {
        return $this->label;
    }
}
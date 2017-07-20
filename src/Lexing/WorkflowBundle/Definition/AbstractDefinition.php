<?php

namespace Lexing\WorkflowBundle\Definition;

// @todo serialization
abstract class AbstractDefinition
{
    /**
     *
     * @var string
     */
    private $identifier;

    /**
     *
     * @var string
     */
    private $description;

    public function __construct($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
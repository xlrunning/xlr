<?php

namespace Lexing\WorkflowBundle\Builder;

use Lexing\WorkflowBundle\Definition\TaskDefinition;
use Lexing\WorkflowBundle\Entity\Task;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TaskFormBuilder
 * @package Lexing\WorkflowBundle\Builder
 */
class TaskFormBuilder
{
    use ContainerAwareTrait;

    public function __construct()
    {

    }

    public function build(TaskDefinition $definition, Task $task)
    {
        $formBuilder = $this->container->get('form.factory')->createBuilder(FormType::class, null, []);
        $formBuilder->setAction($this->container->get('router')->generate('lx_workflow_task', ['id' => $task->getId()]))
            ->setMethod('POST');
        $entryDefinitions = $definition->getTaskEntryDefinitions();

        // @todo add validator
        // @todo form field type - file, text, textarea, custom_formtype::class
        foreach ($entryDefinitions as $entryDef) {
            $formBuilder->add($entryDef->getIdentifier(), 'file', [
                'constraints' => [
                    new Assert\Image([
                        'minWidth' => 200,
                        'maxWidth' => 800,
                        'minHeight' => 200,
                        'maxHeight' => 800,
                    ])
                ]
            ]);
        }
        $formBuilder->add('submit', 'submit', ['label' => '提交']);
        $formBuilder->addEventSubscriber($this->container->get('lx_workflow.task_form_subscriber'));
        return $formBuilder->getForm();
    }
}
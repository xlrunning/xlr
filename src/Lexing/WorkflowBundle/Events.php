<?php

namespace Lexing\WorkflowBundle;

use Symfony\Component\Form\FormEvents;

/**
 * Class Events
 *
 * workflow及task等相关事件
 *
 * @package Lexing\WorkflowBundle
 */
final class Events
{
    /**
     * Private constructor. This class is not meant to be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * 工作流启动
     * 通常第一个任务也会被创建出来
     */
    const WORKFLOW_INITIATED = 'workflow.initiated';

    const TASK_CREATED   = 'task.created';

    const TASK_ASSIGNED  = 'task.assigned';

    const TASK_SUBMIT    = 'task.submit';

    const TASK_COMPLETED = 'task.completed';

    const TASK_CANCELLED = 'task.cancelled';

    const WORKFLOW_COMPLETED = 'workflow.completed';

    const WORKFLOW_CANCELLED = 'workflow.cancelled';
}
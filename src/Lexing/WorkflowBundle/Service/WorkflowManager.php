<?php

namespace Lexing\WorkflowBundle\Service;

use Lexing\WorkflowBundle\Definition\TaskDefinition;
use Lexing\WorkflowBundle\Definition\TaskEntryDefinition;
use Lexing\WorkflowBundle\Definition\WorkflowDefinition;
use Lexing\WorkflowBundle\Event\WorkflowEvent;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Lexing\WorkflowBundle\Events;
use Lexing\WorkflowBundle\Entity\Workflow;
use Lexing\WorkflowBundle\Entity\Task;
use Lexing\WorkflowBundle\Entity\TaskAssignment;
use Lexing\WorkflowBundle\Entity\TaskEntry;
use Lexing\WorkflowBundle\Entity\TaskLog;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class WorkflowManager
 * @package Lexing\WorkflowBundle\Service
 */
class WorkflowManager implements EventSubscriberInterface
{
    use ContainerAwareTrait;

    public function buildWorkflow()
    {
        $workflow = new WorkflowDefinition('loan_request');
        $workflow->setDescription('贷款申请工作流');

        $task1 = new TaskDefinition('asset_secure');
        $task1->setDescription('车城1号岗收取证照钥匙，确认车辆在停并拍照');
        $task1->addTaskEntryDefinition(new TaskEntryDefinition('vehicle_photo', TaskEntryDefinition::TYPE_IMG, '车辆照片'));
        $task2 = new TaskDefinition('asset_secure2');
        $task2->setDescription('风控1号岗负责截取证照钥匙锁入证照箱的视频；截取汽车停在位置且车城1号岗正在拍照的视频');
        $task2->addTaskEntryDefinition(new TaskEntryDefinition('vehicle_photo2', TaskEntryDefinition::TYPE_IMG, '车辆照片'))
            ->addTaskEntryDefinition(new TaskEntryDefinition('step1_video', TaskEntryDefinition::TYPE_VIDEO, '收取证照入柜视频'))
            ->addTaskEntryDefinition(new TaskEntryDefinition('step2_video', TaskEntryDefinition::TYPE_VIDEO, '1号岗拍照视频'));
        $task3 = new TaskDefinition('executive_review');
        $task3->setDescription('领导审核'); // @todo approval?
        $workflow->addTaskDefinitions([$task1, $task2, $task3]);

        $wfRegistry = $this->container->get('lx_workflow.workflow_registry');
        $wfRegistry->addWorkflowDefinition($workflow);

        return $wfRegistry;
    }

    public function onWorkflowInitiated(WorkflowEvent $event)
    {
        $this->buildWorkflow();

        $wfRegistry = $this->container->get('lx_workflow.workflow_registry');
        $workflowDefinition = $wfRegistry->getWorkflowDefinition($event->getWorkflowIdentifier());
        $workflow = new Workflow();
        $workflow->setIdentifier($workflowDefinition->getIdentifier());

        // 工作流的启动总是伴随着第一个任务的创建

        // 创建第一个任务

        // @todo 数据库中同一个workflow不能有重复的task（根据identifier来判断）
        $task1Definition = $workflowDefinition->getFirstTaskDefinition();
        $task1 = $this->container->get('lx_workflow.task_builder')->build($task1Definition, $workflow);

        $em = $this->container->get('doctrine.orm.entity_manager');
        $em->persist($workflow);
        $em->persist($task1);
        $em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::WORKFLOW_INITIATED => 'onWorkflowInitiated'
        ];
    }
}
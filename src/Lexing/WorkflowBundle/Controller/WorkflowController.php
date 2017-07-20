<?php

namespace Lexing\WorkflowBundle\Controller;

use Lexing\WorkflowBundle\Event\WorkflowEvent;
use Lexing\WorkflowBundle\Events;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/workflow")
 */
class WorkflowController extends Controller
{
    /**
     * @Route("/test")
     */
    public function testAction()
    {
        // @todo workflow and its definition
//        $wfManager = $this->get('lx_workflow.workflow_manager');
//        $wfRegistry = $wfManager->buildWorkflow();
//        dump($wfRegistry->getWorkflowDefinition('loan_request'));exit;
        // 创建测试工作流
        $this->get('event_dispatcher')->dispatch(Events::WORKFLOW_INITIATED, new WorkflowEvent('loan_request'));
        exit;
        return $this->render('LexingWorkflowBundle:Default:index.html.twig');
    }

    // @todo 根据identifier翻译出名字？

    /**
     * @Route("/{id}")
     * @Template("workflow/workflow.html.twig")
     */
    public function indexAction($id)
    {
        // @todo workflow and its definition
        // @todo 当前进度，到哪一步了，如何知道？？？
        $em = $this->getDoctrine()->getManager();
        $workflow = $em->getRepository('LexingWorkflowBundle:Workflow')->find($id);

        $workflowManager = $this->get('lx_workflow.workflow_manager');
        $workflowManager->buildWorkflow(); // @todo 什么时候build，需要的时候
        $wfRegistry = $this->get('lx_workflow.workflow_registry');
        $workflowDefinition = $wfRegistry->getWorkflowDefinition($workflow->getIdentifier());

        return [
            'workflow' => $workflow,
            'workflowDefinition' => $workflowDefinition
        ];
    }

    /**
     * @Route("/task/{id}", name="lx_workflow_task")
     * @Template("workflow/task.html.twig")
     */
    public function taskAction($id, Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('LexingWorkflowBundle:Task')->find($id);

        // @todo use some service to deal with task submission

        $workflowManager = $this->get('lx_workflow.workflow_manager');
        $workflowManager->buildWorkflow(); // @todo 什么时候build，需要的时候
        $wfRegistry = $this->get('lx_workflow.workflow_registry');
        $workflow = $task->getWorkflow();
        $workflowDefinition = $wfRegistry->getWorkflowDefinition($workflow->getIdentifier());
        $taskDefinition = $workflowDefinition->getTaskDefinition($task->getIdentifier());
        // @todo task and its definition
        // @todo 是否已经分派给工作人员(assignment)
        // @todo 根据entry definitions 构造和显示 form
        // @todo 监听表单提交事件
        $formBuilder = $this->get('lx_workflow.task_form_builder');
        $form = $formBuilder->build($taskDefinition, $task);

        $form->handleRequest($req);

        return [
            'task' => $task,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/task/{id}/submit", name="lx_workflow_task_submit")
     */
    public function taskSubmitAction($id, Request $req)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('LexingWorkflowBundle:Task')->find($id);

        // @todo 根据task definition检查是否所有entry都有内容了
        // @todo task能否完成
        // @todo move to next step or finish the workflow
    }
}

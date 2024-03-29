<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskFormType;
use App\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    public function __construct(
        private TaskService $taskService,
    ) {
    }

    #[Route('/tasks', name: 'task_list')]
    public function index(): Response
    {
        $result = $this->taskService->getAll();
        return $this->render('task/list.html.twig', [
            'controller_name' => 'TaskController',
            'tasks' => $result,
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/tasks/create', name: 'task_create')]
    public function form(Request $request): Response
    {
        $task = new Task();
        $formTask = $this->createForm(TaskFormType::class, $task);
        $formTask->handleRequest($request);
        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $this->taskService->saveTask($task, $this->getUser());
            $this->addFlash('success', 'La tâche a été bien été ajoutée.');
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/create.html.twig', [
            'form' => $formTask->createView(),
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/task/{id}/edit', name: 'task_edit')]
    public function formEdit(Task $task, int $id, Request $request): Response
    {
        $formTask = $this->createForm(TaskFormType::class, $task);
        $formTask->handleRequest($request);
        if ($formTask->isSubmitted() && $formTask->isValid()) {
            $this->taskService->saveTask($task);
            $this->addFlash('success', 'La tâche a bien été modifiée.');
            return $this->redirectToRoute('task_list');
        }
        return $this->render('task/edit.html.twig', [
            'form' => $formTask->createView(),
            'task' => $task,
            'user' => $this->getUser()
        ]);
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle')]
    public function toggleTaskAction(Task $task): Response
    {
        $this->taskService->toggleTask($task);
        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));
        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete')]
    public function deleteTaskAction(Task $task = null): Response
    {
        $this->taskService->deleteTask($task);
        $this->addFlash('success', 'La tâche a bien été supprimée.');
        return $this->redirectToRoute('task_list');
    }
}

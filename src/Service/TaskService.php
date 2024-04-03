<?php

namespace App\Service;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;

class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
    ) {
    }

    public function getAll(): Task|array
    {
        return $this->taskRepository->findAll();
    }

    public function saveTask(Task $task, User $user = null): void
    {
        $task->setCreatedAt(new DateTime);
        $task->setIsDone(false);
        if ($user) {
            $task->setUser($user);
        }
        $this->taskRepository->saveTask($task);
    }

    public function toggleTask(Task $task): void
    {
        $isDone = $task->getIsDone();
        $isDone = $isDone == true ? false : true;
        $task->setIsDone($isDone);
        $this->taskRepository->saveTask($task);
    }

    public function deleteTask(Task $task): bool
    {
        if ($this->taskRepository->findOneById($task->getId()) === null) {
            return false;
        }
        $this->taskRepository->delete($task);
        return true;
    }
}

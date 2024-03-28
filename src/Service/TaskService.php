<?php

namespace App\Service;

use DateTime;
use App\Entity\Task;
use App\Model\TaskModel;
use App\Mapper\TaskMapper;
use App\Repository\TaskRepository;

class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private TaskMapper $mapper,
    ) {
    }

    public function getAll(): Task|array
    {
        return $this->taskRepository->findAll();
    }

    public function getById(int $id): TaskModel
    {
        $taskEntity = $this->taskRepository->findOneById($id);
        return $this->mapper->EntityToModel($taskEntity);
    }

    public function saveTask(Task $task, ): void
    {
        $task->setCreatedAt(new DateTime);
        $task->setIsDone(false);
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

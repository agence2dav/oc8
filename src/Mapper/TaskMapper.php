<?php

namespace App\Mapper;

use App\Model\TaskModel;

class TaskMapper
{
    public function EntityToModel(object $taskEntity): TaskModel
    {
        $taskModel = new TaskModel();
        $taskModel->setId($taskEntity->getId());
        $taskModel->setTitle($taskEntity->getTitle());
        $taskModel->setContent($taskEntity->getContent());
        $taskModel->setCreatedAt($taskEntity->getCreatedAt());
        $taskModel->setIsDone($taskEntity->getIsDone());
        return $taskModel;
    }

    public function EntitiesToModels(array $taskEntities): array
    {
        $taskModels = [];
        foreach ($taskEntities as $taskEntity) {
            $taskModels[] = $this->EntityToModel($taskEntity);
        }
        return $taskModels;
    }

}

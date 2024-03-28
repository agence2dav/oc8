<?php

namespace App\Mapper;

use App\Model\UserModel;

class UserMapper
{
    public function EntityToModel(object $userEntity): UserModel
    {
        $userModel = new UserModel();
        $userModel->setId($userEntity->getId());
        $userModel->setUsername($userEntity->getUsername());
        $userModel->setPassword($userEntity->getPassword());
        $userModel->setEmail($userEntity->getEmail());
        $userModel->setRoles($userEntity->getRoles());
        return $userModel;
    }

    public function EntitiesToModels(array $userEntities): array
    {
        $userModels = [];
        foreach ($userEntities as $userEntity) {
            $userModels[] = $this->EntityToModel($userEntity);
        }
        return $userModels;
    }

}

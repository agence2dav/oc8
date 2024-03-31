<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Model\UserModel;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $userRepository,
    ) {
    }

    public function getAll(): User|array
    {
        return $this->userRepository->findAll();
    }

    public function getById(int $id): UserModel
    {
        return $this->userRepository->findOneById($id);
    }

    public function saveUser(User $user, string $plainPassword, array $role): void
    {
        $password = $this->userPasswordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($password);
        $user->setRoles($role);
        $this->userRepository->saveUser($user);
    }

    public function deleteUser(User $user): bool
    {
        if ($this->userRepository->findOneById($user->getId()) === null) {
            return false;
        }
        $this->userRepository->delete($user);
        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Service;

use DateTime;
use App\Entity\User;
use App\Model\UserModel;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserService
{
    public function __construct(
        //private readonly EntityManagerInterface $manager,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        //private readonly TokenGeneratorInterface $tokenGenerator,
        //private readonly ParameterBagInterface $parameterBag,
        //private readonly JwtService $JwtService,
        private UserRepository $userRepository,
        //private UserModel $userModel,
        private UserMapper $userMapper,
    ) {
    }

    public function getAll(): User|array
    {
        return $this->userRepository->findAll();
    }

    public function getAllModels(): User|array
    {
        return $this->userMapper->EntitiesToModels($this->getAll());
    }

    public function getById(int $id): UserModel
    {
        return $this->userRepository->findOneById($id);
    }

    public function getModelById(int $id): UserModel
    {
        return $this->userMapper->EntityToModel($this->getById($id));
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

    /*
    public function isUserVerifiedYet(User $user): bool
    {
        return $user->isVerified();
    }

    public function newRegisterToken(UserModel $user): string
    {
        $header = [
            'alg' => 'HS256',
            'typ' => 'JWT'
        ];
        $payload = [
            'UserId' => $user->getId()
        ];
        $param = $this->parameterBag->get('app.jwtsecret');
        return $this->JwtService->generate($header, $payload, $param);
    }

    public function getUserModel(User $user): UserModel
    {
        $this->UserModel->setId($user->getId());
        $this->UserModel->setUsername($user->getUsername());
        $this->UserModel->setEmail($user->getEmail());
        return $this->UserModel;
    }*/
}

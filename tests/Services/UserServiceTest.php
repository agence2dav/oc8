<?php

namespace App\Tests\Service;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Service\UserService;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class UserServiceTest extends WebTestCase
{
    private KernelBrowser $client;
    private Container $container;
    private UserService $userService;
    private UserRepository $userRepository;
    public Generator $faker;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->userService = $this->container->get(UserService::class);
        $this->userRepository = $this->container->get(UserRepository::class);
        $this->faker = Factory::create('fr_FR');
    }

    public function testUserServiceGetAll(): void
    {
        $dataTest = $this->userService->getAll();
        $this->assertContainsOnlyInstancesOf(User::class, $dataTest);
    }

    public function testUserServiceGetById(): void
    {
        $user = $this->userRepository->findOne();
        $dataTest = $this->userService->getById($user->getId());
        $this->assertEquals($user, $dataTest);
    }

    public function testUserServiceSaveUser(): void
    {
        //given
        $numberOfUsers = $this->userRepository->countUsers();
        $user = $this->userRepository->findOneByUsername('u2');
        $newUser = (new User())
            ->setUsername($this->faker->username())
            ->setEmail($this->faker->email());
        //when
        $this->userService->saveUser($newUser, 'password', ['ROLE_USER']);
        $res = $this->userRepository->findAll();
        //then
        $this->assertCount($numberOfUsers + 1, $res);
    }
}

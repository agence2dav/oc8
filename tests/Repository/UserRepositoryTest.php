<?php

namespace App\Tests\Repository;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class UserRepositoryTest extends WebTestCase
{
    private KernelBrowser $client;
    private Container $container;
    private UserRepository $userRepository;
    public Generator $faker;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->userRepository = $this->container->get(UserRepository::class);
        $this->faker = Factory::create('fr_FR');
    }

    public function testUserRepositoryFindOne(): void
    {
        $dataTest = $this->userRepository->findOne();
        $this->assertIsObject($dataTest);
    }

    public function testUserRepositoryCountUsers(): void
    {
        $dataTest = $this->userRepository->countUsers();
        $this->assertGreaterThanOrEqual(1, $dataTest);
    }

    public function testUserRepositorySaveUser(): void
    {
        //given
        $numberOfUsers = $this->userRepository->countUsers();
        $user = $this->userRepository->findOneByUsername('u2');
        $newUser = (new User())
            ->setUsername($this->faker->username())
            ->setEmail($this->faker->email())
            ->setRoles(['ROLE_USER'])
            ->setPassword('password');
        //when
        $this->userRepository->saveUser($newUser);
        $res = $this->userRepository->findAll();
        //then
        $this->assertCount($numberOfUsers + 1, $res);
    }
}

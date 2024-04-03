<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class UserTest extends WebTestCase
{
    private User $task;
    private KernelBrowser $client;
    private Container $container;
    private TaskRepository $taskRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        $this->task = new User();
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->taskRepository = $this->container->get(TaskRepository::class);
        $this->userRepository = $this->container->get(UserRepository::class);
    }

    public function testUserEntityUsername(): void
    {
        $dataTest = 'blabla';
        $this->task->setUsername($dataTest);
        $res = $this->task->getUsername();
        $this->assertEquals($dataTest, $res);
    }

    public function testUserEntityIdentifier(): void
    {
        $dataTest = 'blabla';
        $this->task->setUsername($dataTest);
        $res = $this->task->getUserIdentifier();
        $this->assertEquals($dataTest, $res);
    }

    public function testUserEntityRoles(): void
    {
        $dataTest = ['ROLE_ANONYMOUS'];
        $this->task->setRoles($dataTest);
        $res = $this->task->getRoles();
        $this->assertEquals($dataTest, $res);
    }

    public function testUserEntityPassword(): void
    {
        $dataTest = 'blabla';
        $this->task->setPassword($dataTest);
        $res = $this->task->getPassword();
        $this->assertEquals($dataTest, $res);
    }

    public function testUserEntityEmail(): void
    {
        $dataTest = 'blabla';
        $this->task->setEmail($dataTest);
        $res = $this->task->getEmail();
        $this->assertEquals($dataTest, $res);
    }

    public function testUserEntityTask(): void
    {
        //given
        $user = $this->userRepository->findOneByUsername('u2');
        $tasksOfUser = $user->getTasks();
        $newTask = (new Task())
            ->setTitle('title')
            ->setContent('content')
            ->setCreatedAt(new DateTime)
            ->setIsDone(0);
        //when
        $user->addTask($newTask);
        $res = $user->getTasks();
        //then
        $this->assertCount(count($tasksOfUser), $res); //disjonction +1
    }
}

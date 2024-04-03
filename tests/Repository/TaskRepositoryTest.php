<?php

namespace App\Tests\Repository;

use DateTime;
use Faker\Factory;
use App\Entity\Task;
use Faker\Generator;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class TaskRepositoryTest extends WebTestCase
{
    private KernelBrowser $client;
    private Container $container;
    private UserRepository $userRepository;
    private TaskRepository $taskRepository;
    public Generator $faker;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->taskRepository = $this->container->get(TaskRepository::class);
        $this->userRepository = $this->container->get(UserRepository::class);
        $this->faker = Factory::create('fr_FR');
    }

    public function testTaskRepositoryFindOneByUsername(): void
    {
        $user = $this->userRepository->findOne();
        $dataTest = $this->taskRepository->findOneByUsername($user);
        $this->assertIsObject($dataTest);
    }

    public function testTaskRepositoryFindOne(): void
    {
        $dataTest = $this->taskRepository->findOne();
        $this->assertIsObject($dataTest);
    }

    public function testTaskRepositoryCountTasks(): void
    {
        $dataTest = $this->taskRepository->countTasks();
        $this->assertGreaterThanOrEqual(1, $dataTest);
    }

    public function testTaskRepositorySaveTask(): void
    {
        //given
        $numberOfTasks = $this->taskRepository->countTasks();
        $user = $this->userRepository->findOneByUsername('u2');
        $newTask = (new Task())
            ->setTitle('title')
            ->setContent('content')
            ->setCreatedAt(new DateTime)
            ->setIsDone(0)
            ->setUser($user);
        //when
        $this->taskRepository->saveTask($newTask);
        $res = $this->taskRepository->findAll();
        //then
        $this->assertCount($numberOfTasks + 1, $res);
    }

    public function testTaskRepositoryDelete(): void
    {
        //given
        $task = $this->taskRepository->findOne();
        //when
        $this->taskRepository->delete($task);
        $allTasks = $this->taskRepository->findAll();
        $res = $this->taskRepository->findOneById($task->getId());
        //then
        $this->assertNotContains($res, $allTasks, 'deleted task no longer exists');
    }
}

<?php

namespace App\Tests\Service;

use DateTime;
use Faker\Factory;
use App\Entity\Task;
use Faker\Generator;
use App\Service\TaskService;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class TaskServiceTest extends WebTestCase
{
    private KernelBrowser $client;
    private Container $container;
    private TaskService $taskService;
    private TaskRepository $taskRepository;
    private UserRepository $userRepository;
    public Generator $faker;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->taskService = $this->container->get(TaskService::class);
        $this->userRepository = $this->container->get(UserRepository::class);
        $this->taskRepository = $this->container->get(TaskRepository::class);
        $this->faker = Factory::create('fr_FR');
    }

    public function testTaskServiceGetAll(): void
    {
        $dataTest = $this->taskService->getAll();
        $this->assertContainsOnlyInstancesOf(Task::class, $dataTest);
    }

    public function testTaskServiceSaveTask(): void
    {
        //given
        $numberOfTasks = $this->taskRepository->countTasks();
        $user = $this->userRepository->findOneByUsername('u2');
        $newTask = (new Task())
            ->setTitle('title')
            ->setContent('content')
            ->setCreatedAt(new DateTime)
            ->setIsDone(0);
        //when
        $this->taskService->saveTask($newTask, $user);
        $res = $this->taskRepository->findAll();
        //then
        $this->assertCount($numberOfTasks + 1, $res);
    }

    public function testTaskServiceToggleTask(): void
    {
        //given
        $task = $this->taskRepository->findOne();
        //when
        $this->taskService->toggleTask($task);
        $res = $this->taskRepository->findOneById($task->getId());
        //then
        $newStatus = $res->getIsDone() == 0 ? false : true;
        $this->assertEquals($task->getIsDone(), $newStatus);
    }

    public function testTaskServiceDeleteTask(): void
    {
        //given
        $task = $this->taskRepository->findOne();
        //when
        $isDeleted = $this->taskService->deleteTask($task);
        $allTasks = $this->taskService->getAll();
        $res = $this->taskRepository->findOneById($task->getId());
        //then
        $this->assertNotContains($res, $allTasks, 'deleted task no longer exists');
    }
}

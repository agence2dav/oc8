<?php

namespace App\Tests\Controller;

use Faker\Factory;
use Faker\Generator;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class TaskControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private Container $container;
    private Router $urlGenerator;
    private UserRepository $userRepository;
    private TaskRepository $taskRepository;
    public Generator $faker;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->container = $this->client->getContainer();
        $this->userRepository = $this->container->get(UserRepository::class);
        $this->taskRepository = $this->container->get(TaskRepository::class);
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->faker = Factory::create('fr_FR');
    }

    //ok
    public function testTaskIndex(): void
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'list_tasks');
    }

    //ok because - { path: '^/tasks/create', roles: ROLE_USER }
    public function testCreateTaskNotLogged(): void
    {
        $this->client->request('GET', '/tasks/create');
        $this->assertResponseStatusCodeSame(302);
        $this->assertPageTitleContains('Redirecting');
        $this->assertResponseRedirects('/login');
    }

    //not ok
    public function testCreateTask(): void
    {
        $user = $this->userRepository->findOneByUsername('u1');
        $this->client->loginUser($user, 'secured_area');
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'create_task');
        $title = $this->faker->sentence(4);
        $crawler = $this->client->submitForm('Ajouter', [
            'task_form[title]' => $title,
            'task_form[content]' => $this->faker->sentence(8),
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'list_tasks');
        $createdEntity = $this->taskRepository->findOneByTitle($title);
        $this->assertEquals($title, $createdEntity->getTitle());
    }

    //ok
    public function testEditTask(): void
    {
        $taskEntity = $this->taskRepository->findOne();
        $user = $this->userRepository->findOneByUsername('u1');
        $this->client->loginUser($user, 'secured_area');
        $crawler = $this->client->request('GET', '/task/' . $taskEntity->getId() . '/edit');
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'edit_task');
        $crawler = $this->client->submitForm('Modifier', [
            'task_form[title]' => $taskEntity->getTitle(),
            'task_form[content]' => $taskEntity->getContent() . 'test',
        ]);
        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();
        $modifiedEntity = $this->taskRepository->findOneById($taskEntity->getId());
        $this->assertEquals($taskEntity->getContent() . 'test', $modifiedEntity->getContent());
    }

    //ok
    public function testToggleTask(): void
    {
        // given
        $taskEntity = $this->taskRepository->findOne();
        $user = $this->userRepository->findOneByUsername('u2');
        $this->client->loginUser($user, 'secured_area');
        // when
        $crawler = $this->client->request('GET', '/tasks/' . $taskEntity->getId() . '/toggle');
        // then
        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();
        $this->assertAnySelectorTextSame('div', 'list_tasks');
        $modifiedEntity = $this->taskRepository->findOneById($taskEntity->getId());
        $oldStatus = $taskEntity->getIsDone();
        $newStatus = $oldStatus == 0 ? false : true;
        $this->assertEquals($oldStatus, $newStatus);
    }

    //ok
    public function testDeleteTaskByItsAuthor(): void
    {
        $numberOfTasks = $this->taskRepository->countTasks();
        $user = $this->userRepository->findOneByUsername('u2');
        $taskEntity = $this->taskRepository->findOneByUser($user);
        $this->client->loginUser($user, 'secured_area');
        $crawler = $this->client->request('GET', '/tasks/' . $taskEntity->getId() . '/delete');
        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();
        $this->assertAnySelectorTextSame('div', 'list_tasks');
        $newNumberOfTasks = $this->taskRepository->countTasks();
        $this->assertEquals($numberOfTasks, $newNumberOfTasks + 1);
    }

    //ok
    public function testDeleteTaskByAdmin(): void
    {
        $numberOfTasks = $this->taskRepository->countTasks();
        $user = $this->userRepository->findOneByUsername('u1');
        $user2 = $this->userRepository->findOneByUsername('u2');
        $taskEntity = $this->taskRepository->findOneByUser($user2);
        $this->client->loginUser($user, 'secured_area');
        $crawler = $this->client->request('GET', '/tasks/' . $taskEntity->getId() . '/delete');
        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();
        $this->assertAnySelectorTextSame('div', 'list_tasks');
        $newNumberOfTasks = $this->taskRepository->countTasks();
        $this->assertEquals($numberOfTasks, $newNumberOfTasks + 1);
    }

    //ok
    public function testDeleteTaskByNotItsAuthor(): void
    {
        $numberOfTasks = $this->taskRepository->countTasks();
        $user = $this->userRepository->findOneByUsername('u2');
        $user2 = $this->userRepository->findOneByUsername('u1');
        $taskEntity = $this->taskRepository->findOneByUser($user2);
        $this->client->loginUser($user, 'secured_area');
        $crawler = $this->client->request('GET', '/tasks/' . $taskEntity->getId() . '/delete');
        $this->assertResponseRedirects('/tasks');
        $crawler = $this->client->followRedirect();
        $this->assertAnySelectorTextSame('div', 'list_tasks');
        $newNumberOfTasks = $this->taskRepository->countTasks();
        $this->assertEquals($numberOfTasks, $newNumberOfTasks);
    }
}

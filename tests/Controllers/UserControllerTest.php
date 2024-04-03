<?php

namespace App\Tests\Controller;

use Faker\Factory;
use Faker\Generator;
use App\Form\UserFormType;
use App\Controller\UserController;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\Routing\Router;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserControllerTest extends WebTestCase
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
        //$this->taskRepository = $this->container->get(TaskRepository::class);
        //$this->urlGenerator = $this->client->getContainer()->get('router.default');
        $this->faker = Factory::create('fr_FR');
    }

    //will refuse to let see /users for not loged
    public function testUserControllerIndex(): void
    {
        $crawler = $this->client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(302);
        $this->assertPageTitleContains('Redirecting');
        $this->assertResponseRedirects('/login');
    }

    //will accept to see /users for ROLE_ADMIN
    public function testUserControllerIndexLogedAsAdmin(): void
    {
        $admin = $this->userRepository->findOneByUsername('u1');
        $this->client->loginUser($admin, 'secured_area');
        $crawler = $this->client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'list_users');
    }

    //will refuse to let see /users for ROLE_USER
    public function testUserControllerIndexLogedAsUser(): void
    {
        $user = $this->userRepository->findOneByUsername('u2');
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(302);
        $this->assertPageTitleContains('Redirecting');
        $this->assertResponseRedirects('/login');
    }

    //will refuse to let see /users for ROLE_USER
    public function testUserControllerCreateUserAsUser(): void
    {
        $user = $this->userRepository->findOneByUsername('u2');
        $this->client->loginUser($user);
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(302);
        $this->assertPageTitleContains('Redirecting');
        $this->assertResponseRedirects('/login');
    }

    //will accept to let see /users for ROLE_ADMIN
    public function testUserControllerCreateUserAsAdmin(): void
    {
        $user = $this->userRepository->findOneByUsername('u1');
        $this->client->loginUser($user, 'secured_area');
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'create_user');
    }

    //create user
    //pb compound value
    /* 
    public function testUserControllerCreateUserFormAsAdmin(): void
    {
        //given: call the page
        $NumberOfUsers = $this->userRepository->countUsers();
        $user = $this->userRepository->findOneByUsername('u1');
        $this->client->loginUser($user, 'secured_area');
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'create_user');
        //when: fill the form
        $fakeUser = $this->faker->username();
        $fakePassword = $this->faker->sentence(1);
        $crawler = $this->client->submitForm('Ajouter', [
            'user_form[_username]' => $this->faker->username,
            'user_form[_password]' => $fakePassword, //Cannot set value on a compound field
            'user_form[_password][second]' => $fakePassword,
            'user_form[email]' => $this->faker->username,
            //'user_form[roles][]' => select('ROLE_USER'),
        ]);
        //then
        $crawler = $this->client->followRedirect();
        $this->assertResponseRedirects('/users/create');
        //$this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        //$this->assertAnySelectorTextSame('div', 'create_user');
        $newSetOfUsers = $this->userRepository->findAll();
        $this->assertCount($NumberOfUsers + 1, $newSetOfUsers);
    }*/

    //will accept to edit users for ROLE_ADMIN
    //Cannot set value on a compound field
    /* 
    public function testUserControllerEditUserAsAdmin(): void
    {
        $userEntity = $this->userRepository->findOne();
        $user = $this->userRepository->findOneByUsername('u1');
        $this->client->loginUser($user, 'secured_area');
        $crawler = $this->client->request('GET', '/users/' . $userEntity->getId() . '/edit');
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'edit_users');
        $crawler = $this->client->submitForm('Modifier', [
            'user_form[_username]' => $userEntity->getUsername(),
            'user_form[_password]' => $userEntity->getPassword(), //Cannot set value on a compound field
            'user_form[_password][second]' => $userEntity->getPassword(),
            'user_form[email]' => $userEntity->getEmail(),
            //'user_form[roles][]' => select('ROLE_USER'),
        ]);
        $crawler = $this->client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertAnySelectorTextSame('div', 'list_tasks');
    }*/
}

<?php

namespace App\Tests\Controller;

use Faker\Factory;
use Faker\Generator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;

class UserControllerTest extends WebTestCase
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
}

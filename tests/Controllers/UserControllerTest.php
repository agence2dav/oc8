<?php

namespace App\Tests\Controller;

use App\Tests\SecurityTrait;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{

    //use SecurityTrait;

    /* 
    public function testUserIndex(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/users');
        $this->assertResponseStatusCodeSame(302);
        $this->assertPageTitleSame('Redirecting to /login');
    }*/


    public function testUserIndexLogedAsAdmin(): void
    {
        $client = static::createClient();
        //$admin = static::getContainer()->get(UserRepository::class)->findOneByUsername('u1');
        $userRepository = static::getContainer()->get(UserRepository::class);
        $admin = $userRepository->find(1);
        $client->loginUser($admin, 'secured_area');
        //$client->
        //$this->login('ROLE_USER', true);
        $crawler = $client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        //$this->assertSelectorTextContains('span.username', $admin->getUsername());
        $this->assertAnySelectorTextSame('div', 'list_users');
    }
    /* 
    public function testUserIndexLogedAsUser(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername('u2');
        $client->loginUser($user);
        $crawler = $client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextSame('div', 'list_users');
    }

    public function createAction(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByUsername('admin');
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertAnySelectorTextSame('div', 'create_user');
    }*/
}

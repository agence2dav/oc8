<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testTaskIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(200);
        //$this->assertSelectorTextContains('div', 'To Do List app');
        $this->assertAnySelectorTextContains('div', 'login');
    }
}

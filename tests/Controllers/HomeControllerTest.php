<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testHomeIndex(): void
    {
        $client = static::createClient(); //self
        $crawler = $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorTextContains('div', 'To Do List app');
    }
}

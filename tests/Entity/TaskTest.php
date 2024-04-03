<?php

namespace App\Tests\Entity;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskTest extends WebTestCase
{
    private Task $task;

    protected function setUp(): void
    {
        $this->task = new Task();
    }

    public function testTaskEntityCreatedAt(): void
    {
        $dataTest = new DateTime();
        $this->task->setCreatedAt($dataTest);
        $res = $this->task->getCreatedAt();
        $this->assertEquals($dataTest, $res);
    }

    public function testTaskEntityTitle(): void
    {
        $dataTest = 'blabla';
        $this->task->setTitle($dataTest);
        $res = $this->task->getTitle();
        $this->assertEquals($dataTest, $res);
    }

    public function testTaskEntityContent(): void
    {
        $dataTest = 'blabla';
        $this->task->setContent($dataTest);
        $res = $this->task->getContent();
        $this->assertEquals($dataTest, $res);
    }

    public function testTaskEntityIsDone(): void
    {
        $dataTest = true;
        $this->task->setIsDone($dataTest);
        $res = $this->task->getIsDone();
        $this->assertEquals($dataTest, $res);
    }

    public function testTaskEntityUser(): void
    {
        $dataTest = new User();
        $this->task->setUser($dataTest);
        $res = $this->task->getUser();
        $this->assertEquals($dataTest, $res);
    }
}

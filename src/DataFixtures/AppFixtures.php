<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public Generator $faker;

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function task(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $task = new Task();
            $task
                ->setTitle($this->faker->sentence($nbWords = 4, $variableNbWords = true))
                ->setContent($this->faker->paragraphs(mt_rand(1, 3), true))
                ->setIsDone(mt_rand(0,1))
                ->setCreatedAt($this->faker->dateTimeBetween('-1 year'));
            $manager->persist($task);
        }

        $manager->flush();
    }

    public function user(ObjectManager $manager): void
    {
        for ($i = 0; $i < 4; $i++) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, 'd');
            $user
                ->setUsername($i == 0 ? 'd' : $this->faker->username)
                ->setEmail($this->faker->email)
                ->setRoles([$i == 0 ? 'ROLE_ADMIN' : ''])
                ->setPassword($password);
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function load(ObjectManager $manager): void
    {
        $this->user($manager);
        $this->task($manager);
    }
}

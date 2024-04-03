<?php

namespace App\Repository;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findOneByUsername(User $user): Task
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user=:userid')
            ->setParameter('userid', $user->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    public function findOne(): Task
    {
        return $this->createQueryBuilder('t')
            ->getQuery()
            ->setMaxResults(1)
            ->getSingleResult();
    }

    public function countTasks(): int
    {
        return $this->createQueryBuilder('t')
            ->select('count(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function saveTask(Task $task): void
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    public function delete(Task $task): void
    {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }
}

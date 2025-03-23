<?php

namespace App\Infrastructure\Persistence\Doctrine\Repository;

use App\Domain\Entity\TaskTag;
use App\Domain\Repository\TaskTagRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineTaskTagRepository extends ServiceEntityRepository implements TaskTagRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskTag::class);
    }

    public function save(TaskTag $taskTag): void
    {
        $this->getEntityManager()->persist($taskTag);
        $this->getEntityManager()->flush();
    }

    public function remove(TaskTag $taskTag): void
    {
        $this->getEntityManager()->remove($taskTag);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?TaskTag
    {
        return $this->find($id);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    public function findByName(string $name): array
    {
        return $this->createQueryBuilder('tt')
            ->andWhere('tt.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getResult();
    }
}

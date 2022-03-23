<?php

namespace App\Repository;

use App\Entity\Programme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programme::class);
    }

    public function getAll(): array
    {
        $query =  $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('App:Programme', 'p')
            ->getQuery();

        return $query->getResult();
    }

    public function getSortedProgrammes(string $name, string $order): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('App:Programme', 'p')
            ->orderBy('$name', $order)
            ->getQuery()
            ->getResult();
    }
}

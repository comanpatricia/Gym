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

    public function getSortedBy(string $ordered, int $maxParticipants): array
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('App:Programme', 'p')
            ->orderBy("p.$maxParticipants", $ordered)
            ->getQuery();

        return $query->execute();
    }
}

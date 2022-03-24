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
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('App:Programme', 'p')
            ->getQuery()
            ->getResult();
    }

    public function getFilters(
        array $paginate,
        array $filters,
        string $sortBy,
        string $orderBy
    ): array {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Programme', 'p')
            ->setFirstResult(($paginate['currentPage'] * $paginate['maxPerPage']) - $paginate['maxPerPage'])
            ->setMaxResults($paginate['maxPerPage']);

        foreach ($filters as $key => $value) {
            if ('' != $value) {
                $query = $query->where("p.$key = :$key");
                $query->setParameter(':key', $value);
            }
        }

        $orderBy = mb_strtoupper($orderBy);
        if (!in_array($orderBy, ['ASC', 'DESC'])) {
            $orderBy = 'ASC';
        }

        if ('' != $sortBy) {
            $query = $query->orderBy("p.$sortBy", $orderBy);
        }

        return $query->getQuery()->getResult();
    }
}

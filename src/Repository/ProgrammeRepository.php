<?php

namespace App\Repository;

use App\Entity\Programme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

class ProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programme::class);
    }

//    public function getAll(): array
//    {
//        return $this->getEntityManager()
//            ->createQueryBuilder()
//            ->select('r')
//            ->from('App:Programme', 'p')
//            ->getQuery()
//            ->getResult();
//    }

    public function findAllFiltered(
        array $paginate,
        array $filters,
        ?string $sortBy,
        string $sortDirection
    ): array {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('p')
            ->from('App\Entity\Programme', 'p')
            ->setFirstResult(($paginate['currentPage'] - 1 ) * $paginate['perPage'])
            ->setMaxResults($paginate['perPage']);

        foreach ($filters as $key => $value) {
            if (null != $value) {
                $query = $query->where("p.$key = :$key");
                $query->setParameter(":$key", $value);
            }
        }

        if (null != $sortBy) {
            $sortDirection = strtoupper($sortDirection);
            if (!in_array($sortDirection, ['ASC', 'DESC'])) {
                throw new InvalidArgumentException('Direction must be ASC or DESC');
            }
            $query = $query->orderBy("p.$sortBy", $sortDirection);
        }

        return $query->getQuery()->getResult();
    }
}

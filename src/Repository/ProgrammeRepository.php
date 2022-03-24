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

//    public function getSortedData(string $field): array
//    {
//        return $this->getEntityManager()
//            ->createQueryBuilder()
//            ->select('p')
//            ->from('App\Entity\Programme', 'p')
//            ->orderBy("p.$field", 'ASC')
//            ->getQuery()
//            ->getResult();
//    }
//
//    public function exactSearchByName($exactName): array
//    {
//        return $this->getEntityManager()
//            ->createQueryBuilder()
//            ->select('DISTINCT p')
//            ->select('p')
//            ->from('App\Entity\Programme', 'p')
//            ->where('p.name LIKE :str')
//            ->setParameter('exactName', $exactName)
//            ->getQuery()
//            ->execute();
//    }

    public function getPaginatedFilteredSorted(
        array $paginate,
        array $filters,
        string $sort,
        string $direction
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
        $direction = mb_strtoupper($direction);

        if (!in_array($direction, ['ASC', 'DESC'])) {
            $direction = 'ASC';
        }

        if ('' != $sort) {
            $query = $query->orderBy("p.$sort", $direction);
        }

        return $query->getQuery()->getResult();
    }
}

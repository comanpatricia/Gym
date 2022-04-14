<?php

namespace App\Repository;

use App\Entity\Programme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

/**
 * @method Programme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programme[]    findAll()
 * @method Programme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programme::class);
    }

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
            ->setFirstResult(($paginate['currentPage'] - 1) * $paginate['perPage'])
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

    public function countBusyProgrammes(): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT
                    col.day,
                    col.hour,
                    col.participants
                FROM
                    ( SELECT
                            DATE_FORMAT(p.start_time, "%d-%m-%Y") as day,
                            HOUR(p.start_time) as hour,
                            COUNT(pc.user_id) as participants,
                            RANK() OVER (
                                PARTITION BY DATE_FORMAT(p.start_time, "%d-%m-%Y") ORDER BY COUNT(pc.user_id) DESC
                                ) as position
                    FROM programme p
                             LEFT JOIN programmes_customers pc ON p.id = pc.programme_id
                    GROUP BY day, hour) AS col
                WHERE col.position = 1
                ORDER BY col.participants DESC
                LIMIT 5
                ';

        return $conn->prepare($sql)->executeQuery()->fetchAllAssociative();
    }
}

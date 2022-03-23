<?php

namespace App\Repository;

use App\Entity\Room;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function getAll(): array
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('App:Room', 'r')
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findFirstAvailable(DateTime $startTime, DateTime $endTime, int $maxParticipants): Room
    {
        var_dump($this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT r')
            ->setMaxResults(1)
            ->from('App\Entity\Room', 'r')
            ->leftJoin('App\Entity\Programme', 'p')
            ->where('p.startTime >= :endTime')
            ->orWhere('p.endTime <= :startTime')
            ->groupBy('r.id')
            ->having('r.capacity >= :maxParticipants')
            ->setParameter('endTime', $endTime)
            ->setParameter('startTime', $startTime)
            ->setParameter('maxParticipants', $maxParticipants)
            ->getQuery()
            ->getResult()
        );
        die;

        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT r')
            ->setMaxResults(1)
            ->from('App\Entity\Room', 'r')
            ->leftJoin('App\Entity\Programme', 'p')
            ->where('p.startTime >= :endTime')
            ->orWhere('p.endTime <= :startTime')
            ->groupBy('r.id')
            ->having('r.capacity >= :maxParticipants')
            ->setParameter('endTime', $endTime)
            ->setParameter('startTime', $startTime)
            ->setParameter('maxParticipants', $maxParticipants)
            ->getQuery()
            ->getSingleResult();
    }
}

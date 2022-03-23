<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findFirstAvailable(\DateTime $startTime, \DateTime $endTime, int $maxParticipants): Room
    {
        $query = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('DISTINCT r')
            ->setMaxResults(1)
            ->from('App\Entity\Room', 'r')
            ->join('App\Entity\Programme', 'p')
            ->where('p.startTime >= :endTime')
            ->groupBy('r.id')
            ->having('r.capacity >= :maxParticipants')
            ->setParameter('endTime', $endTime)
            ->setParameter('startTime', $startTime)
            ->setParameter('maxParticipants', $maxParticipants)
            ->getQuery();

        return $query->getResult();
    }
}

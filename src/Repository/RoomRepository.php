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
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('App:Room', 'r')
            ->getQuery()
            ->getResult();
    }

    //TODO another complex query
    public function findOne(): ?Room
    {
        return $this->createQueryBuilder('r')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}

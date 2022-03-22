<?php

namespace App\Repository;

use App\Entity\Programme;
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
        $query =  $this->getEntityManager()
            ->createQueryBuilder()
            ->select('r')
            ->from('App:Room', 'r')
            ->getQuery();

        return $query->getResult();
    }

//    public function assignARoom(Programme $programme)
//    {
//        $entityManager = $this->getEntityManager();
//
//        $rooms = $this->getAll();
//
//        $queryBuilder = $this->entityManager
//            ->createQueryBuilder()
//            ->select('r.id')
//            ->from('Programme', 'p')
//            ->leftJoin('p.room', 'r');
//
//        $query = $queryBuilder->getQuery();
//        $test = $query->execute();
//        var_dump($test);
//
//        foreach ($rooms as $room) {
//            if ($programme->maxParticipants < $room->capacity) {
//                $programme->setRoom($room);
//
//                return;
//            }
//        }
//    }
}

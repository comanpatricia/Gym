<?php
//
//namespace App\Repository;
//
//use App\Entity\Programme;
//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Symfony\Bridge\Doctrine\ManagerRegistry;
//
//class ProgrammeRepository extends ServiceEntityRepository
//{
//    public function __construct(ManagerRegistry $registry)
//    {
//        parent::__construct($registry, Programme::class);
//    }
//
//    public function getNotAvailableRoom($startTime, $endTime): array
//    {
//        $entityManager = $this->getEntityManager();
//
//        $queryBuilder = $this
//            ->createQueryBuilder()
//            ->select('r.id')
//            ->from('Programme', 'p')
//            ->leftJoin('p.room', 'r')
//            ->where('p.startTime < :endTime')
//            ->andWhere('p.endTime > :startTime');
//
//        $query = $queryBuilder->getQuery();
//        $test = $query->execute();
//        var_dump($test);
////            = $entityManager->createQuery(
////            'SELECT p
////            FROM App\Entity\Programme p
////            JOIN p.room
////            '
////        )->setParameter('startDate', $startDate);
////        $query->setParameter('endDate', $endDate);
////
////        // returns an array of Programme objects
//        return $query->getResult();
//    }
//}

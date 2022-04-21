<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\ProgrammeRepository;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\SoftDeleteable\SoftDeleteableListener;

class SoftDeleteUserSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    private ProgrammeRepository $programmeRepository;

    public function __construct(EntityManagerInterface $entityManager, ProgrammeRepository $programmeRepository)
    {
        $this->entityManager = $entityManager;
        $this->programmeRepository = $programmeRepository;
    }

    public function getSubscribedEvents(): array
    {
        return [SoftDeleteableListener::POST_SOFT_DELETE];
    }

    public function postSoftDelete(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $user = $lifecycleEventArgs->getObject();
        $programmes = $this->programmeRepository->findBy(['trainer' => $user]);

        if (!$user instanceof User || !\in_array('ROLE_TRAINER', $user->getRoles(), true)) {
            return;
        }

        foreach ($programmes as $programme) {
            $programme->setTrainer(null);
            $this->entityManager->persist($programme);
        }

        $this->entityManager->flush();
    }
}

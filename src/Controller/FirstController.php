<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route(path="/users", methods={"GET"})
     */
    public function getAllAction(Request $request): Response
    {
        $customer = new User();
        $customer->setRoles(new ArrayCollection(['customer', 'user']));
//
//        $programme = new Programme();
//        $programme->setStartTime(new \DateTime('now'));
//        $programme->setEndTime(new \DateTime('+2 hours'));
//
//        $programme->setCustomers(new ArrayCollection([$customer]));

        $userRepository = $this->entityManager->getRepository(User::class);


        $programme = new Programme();
        $programme->setStartTime(new \DateTime('now'));
        $programme->setEndTime(new \DateTime('+2 hours'));

        /** @var User $customer */
        $customer = $userRepository->find(1);
        $this->entityManager->persist($programme);

        /** @var Programme $programme */
//        $programme->setCustomers($programme->getCustomers()->add($programme));

        $customer->addProgramme($programme);

        var_dump(count($customer->getProgrammes()));
        var_dump(count($programme->getCustomers()));

        $this->entityManager->flush();

        return new Response('Hello Im Patricia', Response::HTTP_OK, []);
    }
}


<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route(path="/api/programmes")
 */
class ProgrammeController
{
    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route(methods={"GET"})
     */
    public function showProgrammes(SerializerInterface $serializer)
    {
        $json = $serializer->serialize(
//            $product,
            'json',
            ['groups' => 'api:programme:all']
        );
    }
}
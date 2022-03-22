<?php

namespace App\Controller;

use App\Repository\ProgrammeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(path="/api/programmes")
 */
class ProgrammeController
{
    private ProgrammeRepository $programmeRepository;

    private SerializerInterface $serializer;

    public function __construct(ProgrammeRepository $programmeRepository, SerializerInterface $serializer)
    {
        $this->programmeRepository = $programmeRepository;
        $this->serializer = $serializer;
    }

    /**
     * @Route(methods={"GET"})
     */
    public function showProgrammes(): JsonResponse
    {
        $json = $this->serializer->serialize(
            $this->programmeRepository->findAll(),
            'json',
            ['groups' => 'api:programme:all']
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

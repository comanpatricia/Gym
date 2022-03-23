<?php

namespace App\Controller;

use App\Repository\ProgrammeRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route(methods={"GET"})
     */
    public function sortProgrammes(Request $request): Response
    {
        $sortBy = $request->query->get('by');
        $sortOrder = $request->query->get('order');

        $data = $this->programmeRepository->getSortedProgrammes($sortBy, $sortOrder);
        $sortedProgrammes = $this->serializer->serialize($data, 'json', ['groups' => 'api:programme:all']);

        return new JsonResponse($sortedProgrammes, Response::HTTP_OK, [], true);
    }
}

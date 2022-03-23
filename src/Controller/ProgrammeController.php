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
    public function showAll(): JsonResponse
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
    public function showFilters(Request $request): Response
    {
        $sortedBy = $request->query->get('sortBy', '');
//        $orderedBy = $request->query->get('orderBy', 'ASC');

        $result = $this->programmeRepository->getSortedData($sortedBy);

        $json = $this->serializer->serialize(
            $result,
            'json',
            ['groups' => 'api:programme:all']
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

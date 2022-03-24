<?php

namespace App\Controller;

use App\Repository\ProgrammeRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(path="/api/programmes")
 */
class ProgrammeController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ProgrammeRepository $programmeRepository;

    private SerializerInterface $serializer;

    private int $maxPerPage;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        SerializerInterface $serializer,
        string $maxPerPage
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->serializer = $serializer;
        $this->maxPerPage = (int) $maxPerPage;
    }

//    /**
//     * @Route(methods={"GET"})
//     */
//    public function showAll(): JsonResponse
//    {
//        $json = $this->serializer->serialize(
//            $this->programmeRepository->findAll(),
//            'json',
//            ['groups' => 'api:programme:all']
//        );
//
//        return new JsonResponse($json, Response::HTTP_OK, [], true);
//    }

    /**
     * @Route(methods={"GET"})
     */
    public function showFilters(Request $request): Response
    {
        $paginate = [];
        $paginate['currentPage'] = $request->query->get('page', 1);
        $paginate['maxPerPage'] = $request->query->get('size', $this->maxPerPage);

        $filters = [];
        $filters['name'] = $request->query->get('name', '');
        $filters['id'] = $request->query->get('id', '');

        $sortBy = $request->query->get('sortBy', '');
        $direction = $request->query->get('sortType', '');

        $result = $this->programmeRepository->getPaginatedFilteredSorted(
            $paginate,
            $filters,
            $sortBy,
            $direction
        );

        $json = $this->serializer->serialize(
            $result,
            'json',
            ['groups' => 'api:programme:all']
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

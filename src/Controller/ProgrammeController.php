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

    private int $defaultPerPage;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        SerializerInterface $serializer,
        string $defaultPerPage
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->serializer = $serializer;
        $this->defaultPerPage = (int) $defaultPerPage;
    }

    /**
     * @Route(methods={"GET"})
     */
    public function listFilteredProgrammes(Request $request): Response
    {
        $paginate['currentPage'] = $request->query->get('page', 1);
        $paginate['perPage'] = $request->query->get('perPage', $this->defaultPerPage);

        $filters['name'] = $request->query->get('name');
        $filters['id'] = $request->query->get('id');

        $sortBy = $request->query->get('sortBy');
        $sortDirection = $request->query->get('sortDirection', 'ASC');

        $result = $this->programmeRepository->findAllFiltered(
            $paginate,
            $filters,
            $sortBy,
            $sortDirection
        );

        $json = $this->serializer->serialize(
            $result,
            'json',
            ['groups' => 'api:programme:all']
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

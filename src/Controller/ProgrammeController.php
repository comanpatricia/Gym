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

    private int $viewOnly;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        SerializerInterface $serializer,
        string $viewOnly
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->serializer = $serializer;
        $this->viewOnly = (int) $viewOnly;
    }

    /**
     * @Route(methods={"GET"})
     */
    public function showFilters(Request $request): Response
    {
        $paginate['currentPage'] = $request->query->get('page', 1);
        $paginate['maxPerPage'] = $request->query->get('size', $this->viewOnly);

        $filters['name'] = $request->query->get('name', '');
        $filters['id'] = $request->query->get('id', '');

        $sortBy = $request->query->get('sortBy', '');
        $orderBy = $request->query->get('orderBy', '');

        $result = $this->programmeRepository->getFilters(
            $paginate,
            $filters,
            $sortBy,
            $orderBy
        );

        $json = $this->serializer->serialize(
            $result,
            'json',
            ['groups' => 'api:programme:all']
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}

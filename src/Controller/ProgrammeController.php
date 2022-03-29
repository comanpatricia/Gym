<?php

namespace App\Controller;

use App\Repository\ProgrammeRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\AcceptHeader;
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
        $acceptXMLHeader = AcceptHeader::fromString($request->headers->get('Accept'));
        if (!$acceptXMLHeader->has('application/xml')) {
            return new Response("Header not accepted", Response::HTTP_BAD_REQUEST);
        }

        $this->logger->info('List programmes.');

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

        if ($acceptXMLHeader->has('application/xml')) {
            $xml = $this->serializer->serialize(
                $result,
                'xml',
                ['groups' => 'api:programme:all']
            );

            return new Response($xml, Response::HTTP_OK, ['Content-Type' => 'application/xml']);
        }

        return new Response('BAD REQUEST', Response::HTTP_BAD_REQUEST);
    }
}

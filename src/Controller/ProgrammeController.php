<?php

namespace App\Controller;

use App\Repository\ProgrammeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route(path="/api/programme", name="api_programme")
 */
class ProgrammeController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private ProgrammeRepository $programmeRepository;

    private UserRepository $userRepository;

    private SerializerInterface $serializer;

    private EntityManagerInterface $entityManager;

    private int $defaultPerPage;

    public function __construct(
        ProgrammeRepository $programmeRepository,
        UserRepository $userRepository,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        string $defaultPerPage
    ) {
        $this->programmeRepository = $programmeRepository;
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->entityManager = $entityManager;
        $this->defaultPerPage = (int)$defaultPerPage;
    }

    /**
     * @Route(methods={"GET"})
     */
    public function listFilteredProgrammes(Request $request): Response
    {
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

        $json = $this->serializer->serialize(
            $result,
            'json',
            ['groups' => 'api:programme:all']
        );

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }


    /**
     * @Route(path="/attend", name="api_attend_programme", methods={"POST"})
     */
    public function attendAProgramme(Request $request): Response
    {
        $this->logger->info('A user attend a programme');

        $programme = $request->query->get('id');
        $programmeToAttend = $this->programmeRepository->findOneBy(['id' => $programme]);

        if (null === $programmeToAttend) {
            return new Response('Programme does not exist', Response::HTTP_NOT_FOUND);
        }

        $token = $request->headers->get('X-AUTH-TOKEN');
        $user = $this->userRepository->findOneBy(['token' => $token]);

        if (null === $user) {
            return new Response('User does not exist', Response::HTTP_NOT_FOUND);
        }

        $programmeToAttend->addCustomer($user);
        $this->entityManager->flush();

        return new Response('User attended successfully', Response::HTTP_OK);
    }
}

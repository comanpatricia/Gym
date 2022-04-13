<?php

namespace App\Controller\Admin;

use App\Repository\ProgrammeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProgrammeController extends AbstractController
{
    private ProgrammeRepository $programmeRepository;

    public function __construct(ProgrammeRepository $programmeRepository)
    {
        $this->programmeRepository = $programmeRepository;
    }

    /**
     * @Route("admin/programmes", name="admin_programmes", methods={"GET"})
     */
    public function getAllProgrammes(Request $request): Response
    {
        $allProgrammes = $this->programmeRepository->findAll();

        return $this->render('Admin/allProgrammes.html.twig', ['allProgrammes' => $allProgrammes ]);
    }

    /**
     * @Route("admin/days", name="busy_days", methods={"GET"})
     */
    public function showBusyProgrammes(): Response
    {
        $programmes = $this->programmeRepository->countBusyProgrammes();

        return $this->render('Admin/busyDays.html.twig', [
            'programmes' => $programmes
        ]);
    }
}

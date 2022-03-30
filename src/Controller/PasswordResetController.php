<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\PasswordResetRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PasswordResetController extends AbstractController
{
//    private EntityManagerInterface $entityManager;
//
//    public function __construct(EntityManagerInterface $entityManager)
//    {
//        $this->entityManager = $entityManager;
//    }

    /**
     * @Route(path="/reset/password")
     */
    public function resetPassword(Request $request): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $passwordReset = $form->getData();

            return $this->redirectToRoute('task_success', [
            'passwordReset' => $passwordReset,
            ]);
        }

        return $this->render('resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

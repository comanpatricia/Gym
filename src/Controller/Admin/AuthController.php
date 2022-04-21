<?php

namespace App\Controller\Admin;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @Route(path="/admin/login", name="admin_login")
     */
    public function loginAdmin(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $this->logger->info('An admin was logged-in');

        return $this->render('Admin/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/admin/logout", name="admin_logout")
     */
    public function logoutAdmin(AuthenticationUtils $authenticationUtils): void
    {
    }
}

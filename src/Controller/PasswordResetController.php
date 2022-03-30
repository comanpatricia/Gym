<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordRequestType;
use App\Form\Type\PasswordResetRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Uid\Uuid;

class PasswordResetController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private MailerInterface $mailer;

    private RouterInterface $router;

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        RouterInterface $router,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @Route(path="/reset/password", name="reset_password")
     */
    public function sendEmail(Request $request): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class)->handleRequest($request);
//        $token = $this->user =
//        $url = $this->urlGenerator->generate('url_password_reset', [], UrlGeneratorInterface::ABSOLUTE_URL);
//        $tokenReset = "$url?tokenReset$tokenReset";

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user !== null) {
                $emailUser = (new TemplatedEmail())
                    ->to(new Address($user->email))
                    ->subject('Reset your password')
                    ->text('Here is the link to reset your password')
                    ->context([
                        'link' => $this->router->generate(
                            'change_password',
                            ['token' => $user->getTokenReset()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        )
                    ])
                    ->htmlTemplate('email.html.twig');

                $tokenReset = Uuid::v4();
                $user->setTokenReset($tokenReset);
                $user->setTokenResetCreatedAt(new \DateTime('now'));

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->mailer->send($emailUser);
            }

            return $this->render('signup.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/change/password", name="change_password")
     */
    public function changePassword(Request $request): Response
    {
        $form = $this->createForm(ChangePasswordRequestType::class)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passwordChange = $form->getData();

            return $this->redirectToRoute('task_success', [
                'passwordChange' => $passwordChange,
            ]);
        }

        return $this->render('resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

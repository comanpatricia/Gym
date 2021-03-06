<?php

namespace App\Controller;

use App\Form\Type\ChangePasswordRequestType;
use App\Form\Type\PasswordResetRequestType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Uid\Uuid;

class PasswordResetController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private EntityManagerInterface $entityManager;

    private MailerInterface $mailer;

    private RouterInterface $router;

    private UserRepository $userRepository;

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(
        EntityManagerInterface $entityManager,
        MailerInterface $mailer,
        RouterInterface $router,
        UserRepository $userRepository,
        UserPasswordHasherInterface $userPasswordHasher
    ) {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->userPasswordHasher = $userPasswordHasher;
    }

    /**
     * @Route(path="/reset/password", name="reset_password")
     * @throws TransportExceptionInterface
     */
    public function sendEmail(Request $request): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();

            $user = $this->userRepository->findOneBy(['email' => $email]);

            if ($user !== null) {
                $tokenReset = Uuid::v4();
                $user->setTokenReset($tokenReset);
                $user->setTokenResetCreatedAt(new \DateTime('now'));

                $this->entityManager->persist($user);
                $this->entityManager->flush();

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

                $this->mailer->send($emailUser);

                $this->logger->info('An email was sent');
            }

            return $this->render('signup.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('sendMail.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(path="/change/password", name="change_password")
     * @throws Exception
     */
    public function changePassword(Request $request): Response
    {
        $user = $this->userRepository->compareTokensWhenChangingPassword($request->get('token'));
        if (null === $user) {
            return new Response('token is not valid', Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(ChangePasswordRequestType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();

            $this->userRepository->upgradePassword($user, $this->userPasswordHasher->hashPassword($user, $newPassword));

            $this->logger->info('A password was changed');

            return $this->render('success.html.twig', [
                'form' => $form->createView(),
                ]);
        }

        return $this->render('resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

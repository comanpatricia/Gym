<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\PasswordResetRequestType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class PasswordResetController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private MailerInterface $mailer;

//    private UserRepository $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
//        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->mailer = $mailer;
//        $this->userRepository = $userRepository;
    }

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

    /**
     * @Route(path="/send/email")
     */
    public function sendEmail(Request $request): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData();
//            $user = $this->entityManager->find(User::class, $email);


//            $obj = $em->getRepository('AcmeSampleBundle:User')->functionInRepository()


            $user = $this->entityManager->getRepository(User::class)->findBy($email);

            if (null !== $user) {
                $email = (new Email())
                    ->from('comanpatricia27@gmail.com')
                    ->to(new Address($email))
                    ->subject('Reset your password')
                    ->context(['expiration_date' => new \DateTime('+2 minutes')])
                    ->text('Here is the link to reset your password')
                    ->html('<p>Password changer!</p>');

                $tokenReset = Uuid::v4();
                $user->setTokenReset($tokenReset);
                $user->setTokenResetCreatedAt(new \DateTime('now'));

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $this->mailer->send($email);
            }

            return $this->render('resetPassword.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        return $this->render('resetPassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

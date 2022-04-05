<?php

namespace App\Controller;

use App\Message\NewsletterNotification;
use App\Message\SmsNotification;
use App\Repository\UserRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiSendNewsletterController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private MailerInterface $mailer;

    private UserRepository $userRepository;

    private MessageBusInterface $messageBus;

    public function __construct(
        MailerInterface $mailer,
        UserRepository $userRepository,
        MessageBusInterface $messageBus
    ) {
        $this->mailer = $mailer;
        $this->userRepository = $userRepository;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route(path="api/users/newsletter", methods={"POST"})
     */
    public function sendNewsletterNotification(Request $request, string $email): Response
    {
        $userToSend = $this->userRepository->findOneBy(['email' => $email]);
        if (null === $userToSend) {
            $this->logger->info('Email was not found');

            return new Response('Email not found', Response::HTTP_NOT_FOUND);
        }

//        $this->newsletterNotification->sendEmailNotification($userToSend->email);

        $this->messageBus->dispatch(new NewsletterNotification($userToSend->get);
        $this->messageBus->dispatch(new SmsNotification('hello', $userToSend->getPhoneNumber()));
//        $smsNotification = new SmsNotification($phoneNumber)

        return new Response('Email was sent successfully', Response::HTTP_OK);
    }
}

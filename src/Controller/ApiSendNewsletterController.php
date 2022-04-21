<?php

namespace App\Controller;

use App\Mailer\NewsletterNotification;
use App\Message\SmsNotification;
use App\Repository\UserRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ApiSendNewsletterController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private UserRepository $userRepository;

    private MessageBusInterface $messageBus;

    private NewsletterNotification $newsletterNotification;

    public function __construct(
        UserRepository $userRepository,
        MessageBusInterface $messageBus,
        NewsletterNotification $newsletterNotification
    ) {
        $this->userRepository = $userRepository;
        $this->messageBus = $messageBus;
        $this->newsletterNotification = $newsletterNotification;
    }

    /**
     * @Route(path="api/user/newsletter", name="api_newsletter", methods={"POST"})
     * @throws TransportExceptionInterface
     */
    public function sendNewsletterNotification(Request $request): Response
    {
        $userToSend = $this->userRepository->findAll();

        foreach ($userToSend as $user) {
            $this->newsletterNotification->sendEmailNotification($user->email);
            $this->messageBus->dispatch(new SmsNotification('hello', $user->getPhoneNumber()));
        }

        return new Response('Email and sms were sent successfully', Response::HTTP_OK);
    }
}

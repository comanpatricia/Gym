<?php

namespace App\Controller;

use App\Message\SmsNotification;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(path="/api/users")
 */
class ApiSendNotifications extends AbstractController
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route(path="/sendEmail", methods={"POST"})
     */
    public function sendEmailNotification(MessageBusInterface $messageBus): Response
    {
        $userToSend = $this->userRepository->findAll();

        if ($userToSend !== null) {
            $messageBus->dispatch(new SmsNotification('Look! I created a message!'));
        }

        return new JsonResponse('', Response::HTTP_OK);
    }
}

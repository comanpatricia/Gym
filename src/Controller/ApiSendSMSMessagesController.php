<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Routing\Annotation\Route;

class ApiSendSMSMessagesController extends AbstractController
{
//    private UserRepository $userRepository;
//
//    public function __construct(UserRepository $userRepository)
//    {
//        $this->userRepository = $userRepository;
//    }
//
//    /**
//     * @Route(path="/api/sendSms", methods={"POST"})
//     */
//    public function sendSmsNotification(int $id, string $phoneNumber): Response
//    {
//        $userToSendSms = $this->userRepository->findOneBy(['id' => $id]);
//        if (null === $userToSendSms) {
//            return new Response('User does not exist', Response::HTTP_NOT_FOUND);
//        }
//
//        $sms = new SmsMessage(
//            $phoneNumber,
//            // the message
//            'My first try!'
//        );
//
//
//        $this->userRepository->
//    }
}

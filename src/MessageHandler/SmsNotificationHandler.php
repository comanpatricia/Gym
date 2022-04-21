<?php

namespace App\MessageHandler;

use App\Http\Client\SmsNotificationClient;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{
    private SmsNotificationClient $smsNotificationClient;

    public function __construct(SmsNotificationClient $smsNotificationClient)
    {
        $this->smsNotificationClient = $smsNotificationClient;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SmsNotification $notification)
    {
        $this->smsNotificationClient->sendSmsNotification($notification->getContent(), $notification->getPhoneNumber());
    }
}

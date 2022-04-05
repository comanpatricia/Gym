<?php

namespace App\MessageHandler;

use App\Http\Client\SmsNotificationClient;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{
    private SmsNotificationClient $smsNotificationClient;

    public function __construct(SmsNotificationClient $smsNotificationClient)
    {
        $this->smsNotificationClient = $smsNotificationClient;
    }

    public function __invoke(SmsNotification $notification)
    {
        $this->smsNotificationClient->sendSmsNotification($notification->getContent(), $notification->getPhoneNumber());
    }
}

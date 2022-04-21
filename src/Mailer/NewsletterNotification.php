<?php

namespace App\Mailer;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class NewsletterNotification
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendEmailNotification(string $email): void
    {
        $emailToSend = (new Email())
            ->to(new Address($email))
            ->subject('Here is your newsletter')
            ->text('Find our news by reading this');

        $this->mailer->send($emailToSend);
    }
}

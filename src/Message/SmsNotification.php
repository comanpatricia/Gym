<?php

namespace App\Message;

use Symfony\Component\Validator\Constraints as Assert;

class SmsNotification
{
    private string $content;

    /**
     * @Assert\Length(min: 4, max: 20)
     * @Assert\Regex(pattern: '/^[0-9+ ()-]*$/')
     */
    private string $phoneNumber;

    public function __construct(string $content, string $phoneNumber)
    {
        $this->content = $content;
        $this->phoneNumber = $phoneNumber;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }
}

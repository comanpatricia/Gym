<?php

namespace App\Message;

class SmsNotification
{
    private string $content;

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

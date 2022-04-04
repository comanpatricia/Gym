<?php

namespace App\Message;

use App\Repository\UserRepository;

class SmsNotification
{
    private $content;

    private UserRepository $userRepository;

    public function __construct($content, UserRepository $userRepository)
    {
        $this->content = $content;
        $this->userRepository = $userRepository;
    }

    public function getContent(): string
    {
        return $this->content;
    }

}
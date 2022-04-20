<?php

namespace App\Tests\Integration\Message;

use App\Message\SmsNotification;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SmsNotificationTest extends KernelTestCase
{
    private ?SmsNotification $smsNotification;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();

        $this->client = $container->get(SmsNotification::class);
    }

    public function testGetContent()
    {
        $message = 'hello';
        $content = $this->smsNotification->getContent($message);

        self::assertEquals($message, $content);
    }

    public function testGetContentNull()
    {
        $message = '';
        $content = $this->smsNotification->getContent($message);

        self::assertEquals($message, $content);
    }

    public function testGetPhoneNumber()
    {
        $phoneNumber = $this->smsNotification->getPhoneNumber();

        self::assertEquals('0753479397', $phoneNumber);
    }

    public function testGetPhoneNumberNull()
    {
        $phoneNumber = $this->smsNotification->getPhoneNumber();

        self::assertEquals('', $phoneNumber);
    }
}

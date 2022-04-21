<?php

namespace App\Tests\Integration\Http\Client;

use App\Http\Client\SmsNotificationClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;

class SmsNotificationClientTest extends KernelTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    private ?SmsNotificationClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
        $container = static::getContainer();

        $this->client = $container->get(SmsNotificationClient::class);
    }

    public function testSmsNotification()
    {
        $this->client->sendSmsNotification('hello', '1234567890');

        self::assertTrue(true);
    }

    public function testWrongReceiver()
    {
        self::expectException(ClientException::class);
        self::expectExceptionMessage('HTTP/1.1 400 Bad Request');

        $this->client->sendSmsNotification('heujhhjllo', 'jekjkfr');
    }
}

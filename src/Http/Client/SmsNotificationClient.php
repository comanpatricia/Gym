<?php

namespace App\Http\Client;

use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SmsNotificationClient
{
    public HttpClientInterface $httpClient;

    public function __construct(
        HttpClientInterface $smsNotification
    ) {
        $this->httpClient = $smsNotification;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function sendSmsNotification(string $content, string $phoneNumber): void
    {
        $data = [
            'receiver' => $phoneNumber,
            'body' => $content
        ];

        $encodedData = json_encode($data);

        $this->httpClient->request('POST', '', [
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            'body' => $encodedData,
        ]);
    }
}

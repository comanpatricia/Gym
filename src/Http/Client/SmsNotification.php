<?php

namespace App\Http\Client;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class SmsNotification
{
    protected HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }


}
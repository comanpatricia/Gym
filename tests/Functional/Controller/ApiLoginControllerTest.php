<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiLoginControllerTest extends WebTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    public function testApiLogin(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testApiLoginWrongEmail(): void
    {
        $email = 'patri@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $client->jsonRequest('POST', 'http://internship.local/api/login', [
        'email' => $email,
        'password' => $password,
        ]);

        $this->assertResponseStatusCodeSame(401, 'Wrong credentials');
    }

    public function testApiLoginWrongPassword(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patriciaa';

        $client = static::createClient();
        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseStatusCodeSame(401, 'Wrong credentials');
    }
}

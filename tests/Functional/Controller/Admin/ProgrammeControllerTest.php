<?php

namespace App\Tests\Functional\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProgrammeControllerTest extends WebTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    public function testGetAllProgrammes(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();

        $client->jsonRequest('POST', 'http://internship.local/admin/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $client->request('GET', 'http://internship.local/admin/programmes');

        $this->assertResponseIsSuccessful();
    }
}

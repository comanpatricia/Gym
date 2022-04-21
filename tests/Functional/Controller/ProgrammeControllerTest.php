<?php

namespace App\Tests\Functional\Controller;

use App\Repository\ProgrammeRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProgrammeControllerTest extends WebTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    public function testAttendProgramme(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $programme = static::getContainer()->get(ProgrammeRepository::class)->findOneBy(['name' => 'Some stuff']);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];

        $client->jsonRequest(
            'POST',
            'http://internship.local/api/programmes/attend?id=' . $programme->getId(),
            [],
            [
                'HTTP_X-AUTH-TOKEN' => $token,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertResponseStatusCodeSame(200, 'Attended successfully');
    }

    public function testAttendNonExistingProgramme(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $programme = static::getContainer()->get(ProgrammeRepository::class)->findOneBy(['name' => 'Stuff not exists']);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];

        $client->getRequest()->query->set('id', null);
        $client->jsonRequest(
            'POST',
            'http://internship.local/api/programmes/attend?id=null',
            [],
            [
                'HTTP_X-AUTH-TOKEN' => $token,
                'HTTP_ACCEPT' => 'application/json',
            ]
        );

        $this->assertResponseStatusCodeSame(404, 'Programme not found');
    }

    public function testAttendWithWrongCredentials(): void
    {
        $email = 'patri@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $programme = static::getContainer()->get(ProgrammeRepository::class)->findOneBy(['name' => 'Some stuff']);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];

        $client->jsonRequest(
            'POST',
            'http://internship-project.local/api/programmes/attend?id=' . $programme->getId(),
            [],
            [
                'HTTP_X-AUTH-TOKEN' => $token,
            ]
        );

        $this->assertResponseStatusCodeSame(401, 'Programme not found');
    }

    public function testlistProgrammes(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];

        $client->request('GET', 'http://internship.local/api/programmes', [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertResponseIsSuccessful();
    }
}

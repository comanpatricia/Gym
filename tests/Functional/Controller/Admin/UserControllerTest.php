<?php

namespace App\Tests\Functional\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    public function testGetAllUsers(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();

        $client->jsonRequest('POST', 'http://internship.local/admin/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $client->request('GET', 'http://internship.local/admin/users');

        $this->assertResponseIsSuccessful();
    }

    public function testSoftDeleteAnUser(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'patri@example.com']);

        $client->jsonRequest('POST', 'http://internship.local/admin/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $userToDelete = $decodedContent['user'];

        $this->assertEquals($email, $userToDelete);

        $client->request('DELETE', 'http://internship.local/api/users/' . $user->getId(), [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $this->assertResponseStatusCodeSame(200, 'User deleted successfully');

    }
}

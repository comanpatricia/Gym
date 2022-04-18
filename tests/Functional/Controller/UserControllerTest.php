<?php

namespace App\Tests\Functional\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    protected function runTest(): void
    {
        $this->markTestSkipped('Skipped test');
    }

    public function testSoftDeleteUser(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => $email]);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $userToDelete = $decodedContent['user'];

        $this->assertEquals($email, $userToDelete);

        $client->request('DELETE', 'http://internship-project.local/api/users/delete/' . $user->getId(), [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $this->assertResponseStatusCodeSame(200, 'User deleted successfully');
    }

    public function testSoftDeleteNotExistingUser(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'patri@example.com']);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $userToDelete = $decodedContent['user'];

        $this->assertEquals($email, $userToDelete);

        $client->request('DELETE', 'http://internship-project.local/api/users/delete/' . $user->getId(), [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $this->assertResponseStatusCodeSame(404, 'User not found');
    }

    public function testRecoverAccount(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => $email]);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $accountToRecover = $decodedContent['user'];

        $this->assertEquals($email, $accountToRecover);

        $client->request('POST', 'http://internship-project.local/api/users/recover/' . $user->email, [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $this->assertResponseStatusCodeSame(200, 'Account recovered successfully');
    }

    public function testRecoverNonExistingAccount(): void
    {
        $email = 'patricia@example.com';
        $password = 'Patricia';

        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => $email]);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $accountToRecover = $decodedContent['user'];

        $this->assertEquals($email, $accountToRecover);

        $client->request('POST', 'http://internship-project.local/api/users/recover/' . $user->null, [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $this->assertResponseStatusCodeSame(404, 'Account recovered successfully');
    }
}

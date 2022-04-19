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
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'patri@example.com']);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $userToDelete = $decodedContent['user'];

        $this->assertEquals($email, $userToDelete);

        $client->request('DELETE', 'http://internship.local/api/users/' . $user->getId(), [], [], [
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
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'patriiii@example.com']);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $userToDelete = $decodedContent['user'];

        $this->assertEquals($email, $userToDelete);

        $client->getResponse()->headers->set('email', null);
        $client->request('DELETE', 'http://internship-project.local/api/null', [], [], [
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
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'patri@example.com']);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $accountToRecover = $decodedContent['user'];

        $this->assertEquals($email, $accountToRecover);

        $client->request('DELETE', 'http://internship.local/api/users/' . $user->getId(), [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $this->assertResponseStatusCodeSame(200, 'User deleted successfully');

        $client->request('POST', 'http://internship.local/api/users/recover/' . $user->email, [], [], [
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
        $user = static::getContainer()->get(UserRepository::class)->findOneBy(['email' => 'patriiii@example.com']);

        $client->jsonRequest('POST', 'http://internship.local/api/login', [
            'email' => $email,
            'password' => $password,
        ]);

        $this->assertResponseIsSuccessful();

        $decodedContent = \json_decode($client->getResponse()->getContent(), true);
        $token = $decodedContent['token'];
        $accountToRecover = $decodedContent['user'];

        $this->assertEquals($email, $accountToRecover);

        $client->getResponse()->headers->set('email', null);
        $client->request('POST', 'http://internship.local/api/users/recover/null', [], [], [
            'HTTP_X-AUTH-TOKEN' => $token,
            'HTTP_ACCEPT' => 'application/json',
        ]);
        $this->assertResponseStatusCodeSame(404, 'Account recovered successfully');
    }

//    public function testRegisterAccount(): void
//    {
//        $email = 'patri@example.com';
//        $firstName = 'Patricia';
//        $lastName = 'Coman';
//        $password = 'Patricia1';
//        $confirmPassword = 'Patricia1';
//        $cnp = '2830420175843';
//        $phoneNumber = '0753479397';
//
//        $client = static::createClient();
//
//        $client->jsonRequest('POST', 'http://internship.local/api/users/register', [
//            'email' => $email,
//            'firstName' => $firstName,
//            'lastName' => $lastName,
//            'password' => $password,
//            'confirmPassword' => $confirmPassword,
//            'cnp' => $cnp,
//            'phoneNumber' => $phoneNumber
//        ]);
//
//        $this->assertResponseStatusCodeSame(201, 'Account created successfully');
//    }
}

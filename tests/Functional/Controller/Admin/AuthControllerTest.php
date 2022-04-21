<?php

namespace App\Tests\Functional\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/admin/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Use your credentials to login as an administrator');

        self::assertSame($crawler->filter('input[name=_username]')->count(), 1);
        self::assertSame($crawler->filter('input[name=_password]')->count(), 1);
        self::assertSame($crawler->filter('button[type=submit]')->count(), 1);
    }
}

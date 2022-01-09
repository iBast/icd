<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testIndexNotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testIndexConnected()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAccountPage()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/mon-compte');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAccountPageNotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/mon-compte');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }
}

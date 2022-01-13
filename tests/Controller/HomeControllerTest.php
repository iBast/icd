<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAccountPage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
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

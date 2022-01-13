<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShopControllerTest extends WebTestCase
{
    public function testIndexNotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/boutique');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testIndexConnected()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCategoryPage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/Category');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testProductPage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/Category/Product');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

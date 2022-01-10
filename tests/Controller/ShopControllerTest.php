<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ShopControllerTest extends WebTestCase
{
    use NeedLogin;

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
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testCategoryPage()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/Category');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testProductPage()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/Category/Product');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

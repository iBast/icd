<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function testIndexNotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/boutique/panier');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testIndexConnected()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/panier');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAddFromProductPage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $crawler = $client->request('GET', '/boutique/Category/Product');
        $form = $crawler->selectButton('Ajouter au panier')->form();
        $client->submit($form, [
            'product[quantity]' => 1,
            'product[variant]' => '1'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testAddFromCartPage()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/panier/ajout?id=1&returnToCart=true');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testAddWithoutProduct()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/panier/ajout?id=&returnToCart=true');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDelete()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/panier/ajout?id=1&returnToCart=true');
        $client->followRedirect();
        $client->request('GET', '/boutique/panier/supprimer/1');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteWithoutProduct()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/panier/supprimer/120397');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDecrement()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/panier/ajout?id=1&returnToCart=true');
        $client->followRedirect();
        $client->request('GET', '/boutique/panier/retirer/1');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDecrementWitoutProduct()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/panier/retirer/666666');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}

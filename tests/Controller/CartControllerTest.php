<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Form\ProductType;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Component\DomCrawler\Link;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    use NeedLogin;

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
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/panier');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAddFromProductPage()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $crawler = $client->request('GET', '/boutique/Category/Product');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter au panier');
        $form = $buttonCrawlerNode->form();
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
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/panier/ajout?id=1&returnToCart=true');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testAddWithoutProduct()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/panier/ajout?id=&returnToCart=true');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDelete()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/panier/ajout?id=1&returnToCart=true');
        $client->followRedirect();
        $client->request('GET', '/boutique/panier/supprimer/1');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDeleteWithoutProduct()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/panier/supprimer/120397');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testDecrement()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/panier/ajout?id=1&returnToCart=true');
        $client->followRedirect();
        $client->request('GET', '/boutique/panier/retirer/1');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDecrementWitoutProduct()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/boutique/panier/retirer/666666');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}

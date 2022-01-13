<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PurcahseControllerTest extends WebTestCase
{
    public function testIndexNotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/boutique/commandes');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testIndexConnected()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/commandes');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testConfirm()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/panier/ajout?id=1&returnToCart=true');
        $crawler = $client->followRedirect();
        $buttonCrawlerNode = $crawler->selectButton('Je confirme !');
        $form = $buttonCrawlerNode->form();
        $client->submit($form);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testConfirmWithoutForm()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/boutique/commande/confirmation');
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-warning');
    }
}

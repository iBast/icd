<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testIndexNotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/inscription');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRegistration()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/inscription');
        $buttonCrawlerNode = $crawler->selectButton('S\'inscrire');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'registration_form[email]' => 'newuser@email.com',
            'registration_form[lastName]' => 'User Last Name',
            'registration_form[firstName]' => 'User First Name',
            'registration_form[plainPassword]' => 'password'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}

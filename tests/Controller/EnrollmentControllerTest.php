<?php

namespace App\Tests\Controller;

use App\Entity\Member;
use App\Entity\Season;
use App\Entity\User;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EnrollmentControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testIndexNotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/adhesion');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testIndexConnected()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/adhesion');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEnrollAdult()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $season = $doctrine->getRepository(Season::class)->findOneBy(['year' => 'Current Season']);
        $member = $doctrine->getRepository(Member::class)->findOneBy(['email' => 'email@dmain.com']);
        $crawler = $client->request('GET', '/adhesion/saison-' . $season->getId() . '/' . $member->getFirstName() . '.' . $member->getLastName());
        $buttonCrawlerNode = $crawler->selectButton('Passer à l\'étape suivante');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'enrollment_step1[licence]' => '1'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Récapitulatif de l\'adhésion');
    }

    public function testEnrollYoung()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $crawler = $client->request('GET', '/membre/edit');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter le membre');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'member[firstName]' => 'new',
            'member[lastName]' => 'young',
            'member[email]' => 'newmember@email.com',
            'member[adress]' => '12 lot panorama',
            'member[postCode]' => '68130',
            'member[city]' => 'Altkirch',
            'member[birthday][day]' => '15',
            'member[birthday][month]' => '2',
            'member[birthday][year]' => '2020'
        ]);
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/adhesion/saison-1/new.young');
        $buttonCrawlerNode = $crawler->selectButton('Passer à l\'étape suivante');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'enrollment_young[licence]' => '2'
        ]);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorTextContains('h1', 'Récapitulatif de l\'adhésion');
    }

    public function testEnrollNotOwned()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $season = $doctrine->getRepository(Season::class)->findOneBy(['year' => 'Current Season']);
        $member = $doctrine->getRepository(Member::class)->findOneBy(['email' => 'email@dmain.fr']);
        $client->request('GET', '/adhesion/saison-' . $season->getId() . '/' . $member->getFirstName() . '.' . $member->getLastName());
        $crawler = $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
        $this->assertSame($crawler->getUri(), 'http://localhost/');
    }

    public function testFinalise()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $season = $doctrine->getRepository(Season::class)->findOneBy(['year' => 'Current Season']);
        $member = $doctrine->getRepository(Member::class)->findOneBy(['email' => 'email@dmain.com']);
        $crawler = $client->request('GET', '/adhesion/saison-' . $season->getId() . '/' . $member->getFirstName() . '.' . $member->getLastName());
        $buttonCrawlerNode = $crawler->selectButton('Passer à l\'étape suivante');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'enrollment_step1[licence]' => '1'
        ]);
        $crawler = $client->followRedirect();
        $buttonCrawlerNode = $crawler->selectButton('Soumettre la demande d\'adhésion');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'enrollment_step2[info_verified]' => 1
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testFinaliseYoung()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $season = $doctrine->getRepository(Season::class)->findOneBy(['year' => 'Current Season']);
        $member = $doctrine->getRepository(Member::class)->findOneBy(['email' => 'young@dmain.fr']);
        $crawler = $client->request('GET', '/adhesion/saison-' . $season->getId() . '/' . $member->getFirstName() . '.' . $member->getLastName());
        $buttonCrawlerNode = $crawler->selectButton('Passer à l\'étape suivante');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'enrollment_young[licence]' => '2'
        ]);
        $crawler = $client->followRedirect();
        $buttonCrawlerNode = $crawler->selectButton('Soumettre la demande d\'adhésion');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'enrollment_step2[info_verified]' => 1
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}

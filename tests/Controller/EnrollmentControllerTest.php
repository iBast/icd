<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use App\Repository\MemberRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EnrollmentControllerTest extends WebTestCase
{
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/adhesion');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEnrollAdult()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $season = static::getContainer()->get(SeasonRepository::class)->findOneBy(['year' => 'Current Season']);
        $member = static::getContainer()->get(MemberRepository::class)->findOneBy(['email' => 'email@dmain.com']);
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $season = static::getContainer()->get(SeasonRepository::class)->findOneBy(['year' => 'Current Season']);
        $member = static::getContainer()->get(MemberRepository::class)->findOneBy(['email' => 'email@dmain.fr']);
        $client->request('GET', '/adhesion/saison-' . $season->getId() . '/' . $member->getFirstName() . '.' . $member->getLastName());
        $crawler = $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
        $this->assertSame($crawler->getUri(), 'http://localhost/');
    }

    public function testFinalise()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $season = static::getContainer()->get(SeasonRepository::class)->findOneBy(['year' => 'Current Season']);
        $member = static::getContainer()->get(MemberRepository::class)->findOneBy(['email' => 'email@dmain.com']);
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
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $season = static::getContainer()->get(SeasonRepository::class)->findOneBy(['year' => 'Current Season']);
        $member = static::getContainer()->get(MemberRepository::class)->findOneBy(['email' => 'young@dmain.fr']);
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

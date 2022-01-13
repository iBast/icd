<?php

namespace App\Tests\Controller;

use App\Entity\Season;
use App\Repository\UserRepository;
use App\Repository\MemberRepository;
use App\Repository\SeasonRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MemberControllerTest extends WebTestCase
{
    public function testIndexNotConnected()
    {
        $client = static::createClient();
        $client->request('GET', '/membres');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testIndexConnected()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/membres');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdd()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $client->request('GET', '/membre/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEdit()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $member = static::getContainer()->get(MemberRepository::class)->findOneBy(['firstName' => 'First Name']);
        $client->request('GET', '/membre/edit/' . $member->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditUncontrolledMember()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        $member = static::getContainer()->get(MemberRepository::class)->findOneBy(['firstName' => 'John']);
        $client->request('GET', '/membre/edit/' . $member->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testCreateMember()
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
            'member[lastName]' => 'member',
            'member[email]' => 'newmember@email.com',
            'member[adress]' => '12 lot panorama',
            'member[postCode]' => '68130',
            'member[city]' => 'Altkirch',
            'member[birthday][day]' => '15',
            'member[birthday][month]' => '2',
            'member[birthday][year]' => '1986'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testCreateMemberNoSeason()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneBy(['email' => 'email@domain.com']);
        $client->loginUser($testUser);
        /** @var Season */
        $season = static::getContainer()->get(SeasonRepository::class)->findOneBy(['year' => 'Current Season']);
        $season->setEnrollmentStatus(false);
        $doctrine = $client->getContainer()->get('doctrine');
        $doctrine->getManager()->persist($season);
        $doctrine->getManager()->flush();
        $crawler = $client->request('GET', '/membre/edit');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter le membre');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'member[firstName]' => 'new',
            'member[lastName]' => 'member',
            'member[email]' => 'newmember2@email.com',
            'member[adress]' => '12 lot panorama',
            'member[postCode]' => '68130',
            'member[city]' => 'Altkirch',
            'member[birthday][day]' => '15',
            'member[birthday][month]' => '2',
            'member[birthday][year]' => '1986'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }
}

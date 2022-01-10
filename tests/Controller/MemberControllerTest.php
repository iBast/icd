<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Member;
use App\Entity\Season;
use App\Tests\Toolbox\NeedLogin;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MemberControllerTest extends WebTestCase
{
    use NeedLogin;

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
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/membres');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdd()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/membre/edit');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEdit()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $member = $doctrine->getRepository(Member::class)->findOneBy(['firstName' => 'First Name']);
        $client->request('GET', '/membre/edit/' . $member->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testEditUncontrolledMember()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $member = $doctrine->getRepository(Member::class)->findOneBy(['firstName' => 'John']);
        $client->request('GET', '/membre/edit/' . $member->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-danger');
    }

    public function testCreateMember()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
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
        /** @var  Registry */
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        /** @var Season */
        $season = $doctrine->getRepository(Season::class)->findOneBy(['year' => 'Current Season']);
        $season->setEnrollmentStatus(false);
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

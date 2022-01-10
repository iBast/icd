<?php

namespace App\Tests\Controller;

use App\Entity\EventComment;
use App\Entity\Member;
use App\Entity\User;
use App\Tests\Toolbox\NeedLogin;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RacesControllerTest extends WebTestCase
{
    use NeedLogin;

    public function testNotConnectedIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/courses');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testNotConnectedAdd()
    {
        $client = static::createClient();
        $client->request('GET', '/courses/ajouter');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testNotConnectedRace()
    {
        $client = static::createClient();
        $client->request('GET', '/courses/Race-Name');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/login');
    }

    public function testIndex()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/courses');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdd()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $crawler = $client->request('GET', '/courses/ajouter');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter la course');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'race[name]' => 'New race',
            'race[date][day]' => '10',
            'race[date][month]' => '10',
            'race[date][year]' => '2022'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testRace()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $client->request('GET', '/courses/Race-Name');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testRaceEdit()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $crawler = $client->request('GET', '/courses/edition/New-race');
        $buttonCrawlerNode = $crawler->selectButton('Modifier la course');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'race[name]' => 'New race update',
            'race[date][day]' => '10',
            'race[date][month]' => '10',
            'race[date][year]' => '2022'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testPinComment()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $comment = $doctrine->getRepository(EventComment::class)->findOneBy(['content' => 'Comment']);
        $client->request('GET', '/courses/epingler-commentaire/' . $comment->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testDeleteComment()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $comment = $doctrine->getRepository(EventComment::class)->findOneBy(['content' => 'Comment']);
        $client->request('GET', '/courses/supprimer-commentaire/' . $comment->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testAddParticipant()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $member = $doctrine->getRepository(Member::class)->findOneBy(['lastName' => 'Last Name']);
        $client->request('GET', '/courses/Race-Name/ajouter-participant/' . $member->getId());
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testAddReview()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $member = $doctrine->getRepository(Member::class)->findOneBy(['lastName' => 'Last Name']);
        $crawler = $client->request('GET', '/courses/Race-Name/ajout-resume/' . $member->getId());
        $buttonCrawlerNode = $crawler->selectButton('Envoyer mon résumé');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'race_report[content]' => 'Raport'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testReviews()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        /**@var User */
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']);
        $user->setRoles(['ROLE_COMMUNITY']);
        $this->login($client, $user);
        $client->request('GET', '/courses/Race-Name/resumes');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAddComment()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        $this->login($client, $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']));
        $crawler = $client->request('GET', '/courses/Race-Name');
        $buttonCrawlerNode = $crawler->selectButton('Ajouter mon commentaire');
        $form = $buttonCrawlerNode->form();
        $client->submit($form, [
            'race_comment[content]' => 'Commentaire'
        ]);
        $client->followRedirect();
        $this->assertSelectorExists('.alert.alert-success');
    }

    public function testDelete()
    {
        $client = static::createClient();
        $doctrine = $client->getContainer()->get('doctrine');
        /** @var User */
        $user = $doctrine->getRepository(User::class)->findOneBy(['email' => 'email@domain.com']);
        $user->setRoles(['ROLE_COMITE']);
        $this->login($client, $user);
        $client->request('GET', '/courses/Race-Name/delete');
        $crawler = $client->followRedirect();
        $this->assertSame($crawler->getUri(), 'http://localhost/courses');
    }
}

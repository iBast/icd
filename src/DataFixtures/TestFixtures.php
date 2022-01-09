<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {

        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $hash = $this->encoder->hashPassword($user, 'password');
        $user->setEmail('email@domain.com')
            ->setFirstName('First Name')
            ->setLastName('Last Name')
            ->setIsVerified(true)
            ->setPassword($hash)
            ->setCreatedAt(new DateTimeImmutable());
        $manager->persist($user);
        $manager->flush();
    }
}

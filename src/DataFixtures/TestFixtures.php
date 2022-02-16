<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\EventComment;
use App\Entity\Licence;
use App\Entity\Member;
use App\Entity\Race;
use App\Entity\Season;
use App\Entity\ShopCategory;
use App\Entity\ShopProduct;
use App\Entity\ShopProductVariant;
use App\Entity\User;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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

        $member = new Member();
        $member->setFirstName('First Name')
            ->setLastName('Last Name')
            ->setEmail('email@dmain.com')
            ->setAdress('4, Privet Drive')
            ->setPostCode('68130')
            ->setCity('Altkirch')
            ->setBirthday(new DateTime('1990-11-16'));
        $member->addUser($user);
        $manager->persist($member);

        $member = new Member();
        $member->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('email@dmain.fr')
            ->setAdress('4, Privet Drive')
            ->setPostCode('68130')
            ->setCity('Altkirch')
            ->setBirthday(new DateTime('1990-11-16'));
        $manager->persist($member);

        $young = new Member();

        $young->setFirstName('young')
            ->setLastName('member')
            ->setEmail('young@dmain.fr')
            ->setAdress('4, Privet Drive')
            ->setPostCode('68130')
            ->setCity('Altkirch')
            ->setBirthday(new DateTime('15 years ago'));
        $young->addUser($user);
        $manager->persist($young);

        $race = new Race();
        $race->setName('Race Name')
            ->setDate(new DateTime('+ 1day'))
            ->setDescription('Description')
            ->setLink('http://link.com')
            ->setLocation('Location')
            ->setSignInLink('http://link.com')
            ->setSlug('Race-Name');
        $manager->persist($race);

        $comment = new EventComment();
        $comment->setUser($user)
            ->setEvent($race)
            ->setCreatedAt(new DateTimeImmutable())
            ->setContent('Comment')
            ->setIsPinned(false);
        $manager->persist($comment);

        $category = new ShopCategory();
        $category->setName('Category')
            ->setSlug('Category');
        $manager->persist($category);

        $product = new ShopProduct();
        $product->setCategory($category)
            ->setDescription('Description')
            ->setPrice(1000)
            ->setSlug('Product')
            ->setName('Product')
            ->setIsVisible(true);
        $manager->persist($product);

        $variant = new ShopProductVariant();
        $variant->setProduct($product)
            ->setStock(1000)
            ->setName('Varianrt Name');
        $manager->persist($variant);

        $season = new Season();
        $season->setYear('Current Season')
            ->setEnrollmentStatus(true)
            ->setCurrent(true)
            ->setMembershipCost(5000)
            ->setSwimCost(6000)
            ->setYoungCost(2000);
        $manager->persist($season);

        $licence = new Licence();
        $licence->setCost(1000)
            ->setIsActive(true)
            ->setName('Adult Licence')
            ->setForYoung(false);
        $manager->persist($licence);

        $licence = new Licence();
        $licence->setCost(1000)
            ->setIsActive(true)
            ->setName('Young Licence')
            ->setForYoung(true);
        $manager->persist($licence);

        $manager->flush();
    }
}

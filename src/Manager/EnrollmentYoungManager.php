<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Member;
use App\Entity\Season;
use DateTimeImmutable;
use App\Entity\EnrollmentYoung;
use App\Entity\EntityInterface;
use App\Manager\AbstractManager;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EnrollmentYoungRepository;

class EnrollmentYoungManager extends AbstractManager
{
    protected $enrollmentYoungRepository;
    protected $seasonRepository;

    public function __construct(EnrollmentYoungRepository $enrollmentYoungRepository, SeasonRepository $seasonRepository, EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->enrollmentYoungRepository = $enrollmentYoungRepository;
        $this->seasonRepository = $seasonRepository;
    }

    public function initialise(EntityInterface $entity)
    {
        //interface   
    }

    public function enroll(Member $member, User $user, Season $season)
    {
        $enrollment = new EnrollmentYoung;
        $enrollment
            ->setIsMember(false)
            ->setHasPoolAcces(false)
            ->setHasCareAuthorization(false)
            ->setHasPhotoAuthorization(false)
            ->setHasLeaveAloneAuthorization(false)
            ->setHasTreatment(false)
            ->setHasAllergy(false)
            ->setOwner($member)
            ->setUser($user)
            ->setSeason($season)
            ->setStatus(null);
        $this->save($enrollment);
    }

    public function draft(EnrollmentYoung $enrollment)
    {
        $season = $this->seasonRepository->findOneBy(['year' => $enrollment->getSeason()->getYear()]);

        $totalAmount = $season->getMembershipCost() + $enrollment->getLicence()->getCost();
        if ($enrollment->getHasPoolAcces() == true) {
            $totalAmount = $totalAmount + $season->getSwimCost();
        }

        $enrollment->setStatus(EnrollmentYoung::STATUS['Dossier créé'])
            ->setIsMember(true)
            ->setTotalAmount($totalAmount)
            ->setCreatedAt(new DateTimeImmutable());
        $this->save($enrollment);
    }

    public function finalise(EnrollmentYoung $enrollment)
    {
        $enrollment->setStatus(EnrollmentYoung::STATUS['En Attente de validation']);
        $this->save($enrollment);
    }

    public function validate(EnrollmentYoung $enrollment)
    {
        $enrollment->setIsDocsValid(true);
        $this->save($enrollment);
    }

    public function paymentOk(EnrollmentYoung $enrollment)
    {
        $enrollment->setPaymentAt(new DateTimeImmutable());
        $this->save($enrollment);
    }
}

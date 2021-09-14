<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Member;
use App\Entity\Season;
use DateTimeImmutable;
use App\Entity\Enrollment;
use App\Entity\EntityInterface;
use App\Manager\AbstractManager;
use App\Repository\SeasonRepository;
use App\Repository\EnrollmentRepository;
use Doctrine\ORM\EntityManagerInterface;

class EnrollmentManager extends AbstractManager
{
    protected $enrollmentRepository;
    protected $seasonRepository;

    public function __construct(EnrollmentRepository $enrollmentRepository, SeasonRepository $seasonRepository, EntityManagerInterface $em)
    {
        parent::__construct($em);
        $this->enrollmentRepository = $enrollmentRepository;
        $this->seasonRepository = $seasonRepository;
    }

    public function initialise(EntityInterface $entity)
    {
        //interface   
    }

    public function enroll(Member $member, User $user, Season $season)
    {
        $enrollment = new Enrollment;
        $enrollment
            ->setIsMember(false)
            ->setHasPoolAcces(false)
            ->setHasCareAuthorization(false)
            ->setHasPhotoAuthorization(false)
            ->setHasLeaveAloneAuthorization(false)
            ->setHasTreatment(false)
            ->setHasAllergy(false)
            ->setMemberId($member)
            ->setUser($user)
            ->setSeason($season)
            ->setStatus(null);
        $this->save($enrollment);
    }

    public function draft(Enrollment $enrollment)
    {
        $season = $this->seasonRepository->findOneBy(['year' => $enrollment->getSeason()->getYear()]);

        $totalAmount = $season->getMembershipCost() + $enrollment->getLicence()->getCost();
        if ($enrollment->getHasPoolAcces() == true) {
            $totalAmount = $totalAmount + $season->getSwimCost();
        }

        $enrollment->setStatus(Enrollment::STATUS['Dossier crée'])
            ->setIsMember(true)
            ->setTotalAmount($totalAmount)
            ->setCreatedAt(new DateTimeImmutable());
        $this->save($enrollment);
    }

    public function finalise(Enrollment $enrollment)
    {
        $enrollment->setStatus(Enrollment::STATUS['En Attente de validation']);
        $this->save($enrollment);
    }

    public function pending(Enrollment $enrollment)
    {
        $enrollment->setStatus(Enrollment::STATUS['En Attente de la FFTri']);
        $this->save($enrollment);
    }

    public function validate(Enrollment $enrollment)
    {
        $enrollment->setStatus(Enrollment::STATUS['Dossier validé']);
        $this->save($enrollment);
    }
}

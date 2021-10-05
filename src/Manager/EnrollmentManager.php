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
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EnrollmentManager extends AbstractManager
{
    protected $enrollmentRepository;
    protected $seasonRepository;
    protected $mailer;

    public function __construct(EnrollmentRepository $enrollmentRepository, SeasonRepository $seasonRepository, EntityManagerInterface $em, MailerInterface $mailer)
    {
        parent::__construct($em);
        $this->enrollmentRepository = $enrollmentRepository;
        $this->seasonRepository = $seasonRepository;
        $this->mailer = $mailer;
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

        $enrollment->setStatus(Enrollment::STATUS['Dossier créé'])
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

    public function finalValidation(Enrollment $enrollment)
    {
        $enrollment->setStatus(Enrollment::STATUS['Dossier validé']);
        $this->save($enrollment);
    }

    public function validate(Enrollment $enrollment)
    {
        $enrollment->setIsDocsValid(true);
        $this->save($enrollment);
    }

    public function paymentOk(Enrollment $enrollment)
    {
        $enrollment->setPaymentAt(new DateTimeImmutable());
        $this->save($enrollment);
    }

    public function sendEmailMissingDocs(Enrollment $enrollment)
    {
        $items = [];
        if ($enrollment->getIsDocsValid() == false) {
            $items[] = 'Les documents n\'ont pas pu être validés ou sont manquants';
        }
        if ($enrollment->getPaymentAt() == null) {
            $items[] = 'Le paiement n\'a pas été réceptionné';
        }
        $email = (new TemplatedEmail())
            ->from(new Address('hello@bastienmunck.fr', 'Iron Club'))
            ->to($enrollment->getUser()->getEmail())
            ->subject('Adhésion Iron - Eléments manquants')
            ->htmlTemplate('email/missingEnroll.html.twig')
            ->context(['items' => $items, 'member' => $enrollment->getMemberId(), 'season' => $enrollment->getSeason()]);

        if ($enrollment->getMemberId()->getEmail() != $enrollment->getUser()->getEmail()) {
            $email->cc($enrollment->getMemberId()->getEmail());
        }

        $this->mailer->send($email);
    }
}

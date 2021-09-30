<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Member;
use App\Entity\Season;
use DateTimeImmutable;
use App\Entity\EnrollmentYoung;
use App\Entity\EntityInterface;
use App\Manager\AbstractManager;
use Symfony\Component\Mime\Address;
use App\Repository\SeasonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Repository\EnrollmentYoungRepository;
use Symfony\Component\Mailer\MailerInterface;

class EnrollmentYoungManager extends AbstractManager
{
    protected $enrollmentYoungRepository;
    protected $seasonRepository;
    protected $mailer;

    public function __construct(EnrollmentYoungRepository $enrollmentYoungRepository, SeasonRepository $seasonRepository, EntityManagerInterface $em, MailerInterface $mailer)
    {
        parent::__construct($em);
        $this->enrollmentYoungRepository = $enrollmentYoungRepository;
        $this->seasonRepository = $seasonRepository;
        $this->mailer = $mailer;
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

    public function sendEmailMissingDocs(EnrollmentYoung $enrollment)
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
            ->context(['items' => $items, 'member' => $enrollment->getOwner(), 'season' => $enrollment->getSeason()]);

        if ($enrollment->getOwner()->getEmail() != $enrollment->getUser()->getEmail()) {
            $email->cc($enrollment->getOwner()->getEmail());
        }

        $this->mailer->send($email);
    }
}

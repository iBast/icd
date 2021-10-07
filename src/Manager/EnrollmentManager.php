<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Member;
use App\Entity\Season;
use DateTimeImmutable;
use App\Entity\Enrollment;
use App\Entity\EntityInterface;
use App\Helper\ParamsInService;
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
    protected $paramsInService;

    public function __construct(
        EnrollmentRepository $enrollmentRepository,
        SeasonRepository $seasonRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        ParamsInService $paramsInService
    ) {
        parent::__construct($em);
        $this->enrollmentRepository = $enrollmentRepository;
        $this->seasonRepository = $seasonRepository;
        $this->mailer = $mailer;
        $this->paramsInService = $paramsInService;
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
            ->setHasPhotoAuthorization(false)
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

        $enrollment->setStatus($this->paramsInService->get(ParamsInService::APP_ENROLLMENT_NEW))
            ->setIsMember(true)
            ->setTotalAmount($totalAmount)
            ->setCreatedAt(new DateTimeImmutable());
        $this->save($enrollment);
    }

    public function finalise(Enrollment $enrollment)
    {
        $enrollment->setStatus($this->paramsInService->get(ParamsInService::APP_ENROLLMENT_PENDING));
        $this->save($enrollment);
    }

    public function finalValidation(Enrollment $enrollment)
    {
        $enrollment
            ->setEndedAt(new DateTimeImmutable())
            ->setStatus($this->paramsInService->get(ParamsInService::APP_ENROLLMENT_DONE));
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
            ->from(new Address($this->paramsInService->get(ParamsInService::APP_EMAIL_ADRESS), $this->paramsInService->get(ParamsInService::APP_EMAIL_NAME)))
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

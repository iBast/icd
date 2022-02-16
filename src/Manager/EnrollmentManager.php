<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Entity\Enrollment;
use App\Entity\EntityInterface;
use App\Entity\Member;
use App\Entity\Season;
use App\Entity\User;
use App\Helper\ParamsInService;
use App\Repository\EnrollmentRepository;
use App\Repository\SeasonRepository;
use DateTimeImmutable;
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
    protected $accountManager;

    public function __construct(
        EnrollmentRepository $enrollmentRepository,
        SeasonRepository $seasonRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        ParamsInService $paramsInService,
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
        $enrollment = new Enrollment();
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
        if (true === $enrollment->getHasPoolAcces()) {
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
            ->setStatus($this->paramsInService->get(ParamsInService::APP_ENROLLMENT_DONE))
            ->setEndedAt(new DateTimeImmutable());
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
        if (false === $enrollment->getIsDocsValid()) {
            $items[] = 'Les documents n\'ont pas pu être validés ou sont manquants';
        }
        if (null === $enrollment->getPaymentAt()) {
            $items[] = 'Le paiement n\'a pas été réceptionné';
        }
        $email = (new TemplatedEmail())
            ->from(new Address($this->paramsInService->get(ParamsInService::APP_EMAIL_ADRESS), $this->paramsInService->get(ParamsInService::APP_EMAIL_NAME)))
            ->to($enrollment->getUser()->getEmail())
            ->subject('Adhésion Iron - Eléments manquants')
            ->htmlTemplate('email/missingEnroll.html.twig')
            ->context(['items' => $items, 'member' => $enrollment->getMemberId(), 'season' => $enrollment->getSeason()]);

        if ($enrollment->getMemberId()->getEmail() !== $enrollment->getUser()->getEmail()) {
            $email->cc($enrollment->getMemberId()->getEmail());
        }

        $this->mailer->send($email);
    }
}

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

use App\Entity\EnrollmentYoung;
use App\Entity\EntityInterface;
use App\Entity\Member;
use App\Entity\Season;
use App\Entity\User;
use App\Helper\ParamsInService;
use App\Repository\EnrollmentYoungRepository;
use App\Repository\SeasonRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class EnrollmentYoungManager extends AbstractManager
{
    protected $enrollmentYoungRepository;
    protected $seasonRepository;
    protected $mailer;
    protected $params;
    protected $accountManager;

    public function __construct(
        EnrollmentYoungRepository $enrollmentYoungRepository,
        SeasonRepository $seasonRepository,
        EntityManagerInterface $em,
        MailerInterface $mailer,
        ParamsInService $params,
    ) {
        parent::__construct($em);
        $this->enrollmentYoungRepository = $enrollmentYoungRepository;
        $this->seasonRepository = $seasonRepository;
        $this->mailer = $mailer;
        $this->params = $params;
    }

    public function initialise(EntityInterface $entity)
    {
        //interface
    }

    public function enroll(Member $member, User $user, Season $season)
    {
        $enrollment = new EnrollmentYoung();
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

        $totalAmount = $season->getYoungCost() + $enrollment->getLicence()->getCost();
        if (true === $enrollment->getHasPoolAcces()) {
            $totalAmount = $totalAmount + $season->getSwimCost();
        }

        $enrollment->setStatus($this->params->get(ParamsInService::APP_ENROLLMENT_NEW))
            ->setIsMember(true)
            ->setTotalAmount($totalAmount)
            ->setCreatedAt(new DateTimeImmutable());
        $this->save($enrollment);
    }

    public function finalValidation(EnrollmentYoung $enrollment)
    {
        $enrollment->setStatus($this->params->get(ParamsInService::APP_ENROLLMENT_DONE))
            ->setEndedAt(new DateTimeImmutable());
        $this->save($enrollment);
    }

    public function finalise(EnrollmentYoung $enrollment)
    {
        $enrollment->setStatus($this->params->get(ParamsInService::APP_ENROLLMENT_PENDING));
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
        if (false === $enrollment->getIsDocsValid()) {
            $items[] = 'Les documents n\'ont pas pu être validés ou sont manquants';
        }
        if (null === $enrollment->getPaymentAt()) {
            $items[] = 'Le paiement n\'a pas été réceptionné';
        }
        $email = (new TemplatedEmail())
            ->from(new Address($this->params->get(ParamsInService::APP_EMAIL_ADRESS), $this->params->get(ParamsInService::APP_EMAIL_NAME)))
            ->to($enrollment->getUser()->getEmail())
            ->subject('Adhésion Iron - Eléments manquants')
            ->htmlTemplate('email/missingEnroll.html.twig')
            ->context(['items' => $items, 'member' => $enrollment->getOwner(), 'season' => $enrollment->getSeason()]);

        if ($enrollment->getOwner()->getEmail() !== $enrollment->getUser()->getEmail()) {
            $email->cc($enrollment->getOwner()->getEmail());
        }

        $this->mailer->send($email);
    }
}

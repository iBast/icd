<?php

namespace App\Controller;

use App\Entity\Member;
use App\Entity\Enrollment;
use App\Form\EnrollmentStep1Type;
use App\Form\EnrollmentStep2Type;
use App\Form\MemberType;
use App\Manager\AccountManager;
use App\Manager\EnrollmentManager;
use App\Repository\AccountRepository;
use App\Repository\MemberRepository;
use App\Repository\SeasonRepository;
use App\Repository\EnrollmentRepository;
use App\Repository\LicenceRepository;
use DateTime;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class EnrollmentController extends AbstractController
{
    protected $manager;
    protected $seasonRepository;
    protected $enrollmentRepository;
    protected $memberRepository;

    public function __construct(EnrollmentManager $manager, SeasonRepository $seasonRepository, EnrollmentRepository $enrollmentRepository, MemberRepository $memberRepository)
    {
        $this->manager = $manager;
        $this->seasonRepository = $seasonRepository;
        $this->enrollmentRepository = $enrollmentRepository;
        $this->memberRepository = $memberRepository;
    }

    #[Route('/adhesion', name: 'enrollment')]
    public function index()
    {
        $season = $this->seasonRepository->findOneBy(['enrollmentStatus' => 1]);

        foreach ($this->getUser()->getMembers() as $member) {
            if ($this->enrollmentRepository->findBy(['memberId' => $member, 'Season' => $season]) == null) {
                $this->manager->enroll($member, $this->getUser(), $season);
            }
        }

        $enrollments = $this->enrollmentRepository->findBySeasonAndUser($season, $this->getUser());

        return $this->render('enrollment/index.html.twig', [
            'enrollments' => $enrollments,
            'season' => $season,
        ]);
    }

    #[Route('/adhesion/saison-{id}-{firstName}-{lastName}', name: 'enrollment_member')]
    public function enrollment($id, $firstName, $lastName, LicenceRepository $licenceRepository, Request $request): Response
    {
        $season = $this->seasonRepository->find($id);
        $member = $this->memberRepository->findOneBy(['firstName' => $firstName, 'lastName' => $lastName]);
        $enrollment = $this->enrollmentRepository->findOneBy(['memberId' => $member, 'Season' => $season]);


        if ($member->getBirthday() < new DateTime('-18years')) {
            $form = $this->createForm(EnrollmentStep1Type::class, $enrollment);
        } else {
            $form = $this->createForm(MemberType::class);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->draft($enrollment);
            return $this->redirectToRoute('enrollment_finalise', ['id' => $enrollment->getId()]);
        }

        return $this->render('enrollment/enroll.html.twig', [
            'season' => $season,
            'member' => $member,
            'form' => $form->createView(),
            'licences' => $licenceRepository->findAll()
        ]);
    }

    #[Route('/adhesion/validation/{id}', name: 'enrollment_finalise')]
    public function finalise(Enrollment $enrollment, Request $request, AccountManager $accountManager, AccountRepository $accountRepository)
    {
        $form = $this->createForm(EnrollmentStep2Type::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->finalise($enrollment);
            $accountManager->debit(
                $accountRepository->findOneBy(['number' => '411' . str_pad($enrollment->getMemberId()->getId(), 3, '0', STR_PAD_LEFT)]),
                'Adhésion saison ' . $enrollment->getSeason()->getYear(),
                $enrollment->getTotalAmount()
            );
            $accountManager->credit(
                $accountRepository->findOneBy(['number' => '756000']),
                'Adhésion saison ' . $enrollment->getSeason()->getYear() . ' - ' . $enrollment->getMemberId()->getFirstName() . ' ' . $enrollment->getMemberId()->getLastName(),
                $enrollment->getTotalAmount()
            );
        }
        return $this->render('enrollment/finalise.html.twig', [
            'enrollment' => $enrollment,
            'form' => $form->createView()
        ]);
    }
}

/*
* Statut : 1. Demande crée, 2.Demande faite, 
*/

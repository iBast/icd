<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Enrollment;
use App\Entity\EnrollmentYoung;
use App\Form\EnrollmentStep1Type;
use App\Form\EnrollmentStep2Type;
use App\Form\EnrollmentYoungType;
use App\Manager\EnrollmentManager;
use App\Manager\EnrollmentYoungManager;
use App\Repository\EnrollmentRepository;
use App\Repository\EnrollmentYoungRepository;
use App\Repository\LicenceRepository;
use App\Repository\MemberRepository;
use App\Repository\SeasonRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EnrollmentController extends AbstractController
{
    protected $manager;
    protected $seasonRepository;
    protected $enrollmentRepository;
    protected $memberRepository;
    protected $enrollmentYoungRepository;
    protected $youngManager;

    public function __construct(
        EnrollmentManager $manager,
        EnrollmentYoungManager $youngManager,
        SeasonRepository $seasonRepository,
        EnrollmentRepository $enrollmentRepository,
        MemberRepository $memberRepository,
        EnrollmentYoungRepository $enrollmentYoungRepository
    ) {
        $this->manager = $manager;
        $this->seasonRepository = $seasonRepository;
        $this->enrollmentRepository = $enrollmentRepository;
        $this->memberRepository = $memberRepository;
        $this->enrollmentYoungRepository = $enrollmentYoungRepository;
        $this->youngManager = $youngManager;
    }

    #[Route('/adhesion', name: 'enrollment')]
    public function index()
    {
        $season = $this->seasonRepository->findOneBy(['enrollmentStatus' => 1]);

        foreach ($this->getUser()->getMembers() as $member) {
            if ($member->getBirthday() < new DateTime('-18years')) {
                if (null === $this->enrollmentRepository->findOneBy(['memberId' => $member, 'Season' => $season])) {
                    $this->manager->enroll($member, $this->getUser(), $season);
                }
            } else {
                if (null === $this->enrollmentYoungRepository->findOneBy(['owner' => $member, 'season' => $season])) {
                    $this->youngManager->enroll($member, $this->getUser(), $season);
                }
            }
        }

        $enrollments = $this->enrollmentRepository->findBySeasonAndUser($season, $this->getUser());

        return $this->render('enrollment/index.html.twig', [
            'enrollments' => $enrollments,
            'youngs' => $this->enrollmentYoungRepository->findBySeasonAndUser($season, $this->getUser()),
            'season' => $season,
        ]);
    }

    #[Route('/adhesion/saison-{id}/{firstName}.{lastName}', name: 'enrollment_member')]
    public function enrollment($id, $firstName, $lastName, LicenceRepository $licenceRepository, Request $request): Response
    {
        $season = $this->seasonRepository->find($id);
        $member = $this->memberRepository->findOneBy(['firstName' => $firstName, 'lastName' => $lastName]);

        if (!$this->getUser()->getMembers()->contains($member)) {
            $this->addFlash('danger', 'Tu n\'es pas autoris?? ?? modifier ce membre !');

            return $this->redirectToRoute('home');
        }

        if ($member->getBirthday() < new DateTime('-18years')) {
            if (null === $this->enrollmentRepository->findOneBy(['memberId' => $member, 'Season' => $season])) {
                $this->manager->enroll($member, $this->getUser(), $season);
            }
            $enrollment = $this->enrollmentRepository->findOneBy(['memberId' => $member, 'Season' => $season]);
            $form = $this->createForm(EnrollmentStep1Type::class, $enrollment);
        } else {
            if (null === $this->enrollmentYoungRepository->findOneBy(['owner' => $member, 'season' => $season])) {
                $this->youngManager->enroll($member, $this->getUser(), $season);
            }
            $enrollment = $this->enrollmentYoungRepository->findOneBy(['owner' => $member, 'season' => $season]);

            $form = $this->createForm(EnrollmentYoungType::class, $enrollment);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($member->getBirthday() < new DateTime('-18years')) {
                $this->manager->draft($enrollment);

                return $this->redirectToRoute('enrollment_finalise', ['id' => $enrollment->getId()]);
            }
            $this->youngManager->draft($enrollment);

            return $this->redirectToRoute('enrollment_finalise_young', ['id' => $enrollment->getId()]);
        }

        return $this->render('enrollment/enroll.html.twig', [
            'season' => $season,
            'member' => $member,
            'form' => $form->createView(),
            'licences' => $licenceRepository->findAll(),
        ]);
    }

    #[Route('/adhesion/validation/adulte/{id}', name: 'enrollment_finalise')]
    public function finalise(Enrollment $enrollment, Request $request)
    {
        $form = $this->createForm(EnrollmentStep2Type::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->finalise($enrollment);
            $this->addFlash('success', 'Ton adh??sion est enregistr??e, elle sera valid?? prochainement, sous r??serve de r??ception du paiement ainsi que des documents');

            return $this->redirectToRoute('home');
        }

        return $this->render('enrollment/finalise.html.twig', [
            'enrollment' => $enrollment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/adhesion/validation/jeune/{id}', name: 'enrollment_finalise_young')]
    public function finaliseYoung(EnrollmentYoung $enrollment, Request $request, EnrollmentYoungManager $manager)
    {
        $form = $this->createForm(EnrollmentStep2Type::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager->finalise($enrollment);
            $this->addFlash('success', 'Ton adh??sion est enregistr??e, elle sera valid?? prochainement, sous r??serve de r??ception du paiement ainsi que des documents');

            return $this->redirectToRoute('home');
        }

        return $this->render('enrollment/finalise.html.twig', [
            'enrollment' => $enrollment,
            'form' => $form->createView(),
        ]);
    }
}

/*
* Statut : 1. Demande cr??e, 2.Demande faite,
*/

<?php

namespace App\Controller;

use App\Entity\Race;
use App\Entity\User;
use App\Entity\Member;
use App\Form\RaceType;
use App\Entity\EventComment;
use App\Entity\RaceReport;
use App\Form\RaceReportType;
use App\Manager\RaceManager;
use App\Form\RaceCommentType;
use App\Repository\RaceRepository;
use App\Security\EventCommentVoter;
use App\Repository\MemberRepository;
use App\Repository\EventCommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RacesController extends AbstractController
{
    private $manager;

    public function __construct(RaceManager $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/courses', name: 'races_home')]
    public function index(): Response
    {
        $races = $this->manager->getRaceRepository()->findUpComingRaces();
        $pastRaces = $this->manager->getRaceRepository()->findLastRaces();
        return $this->render('races/index.html.twig', [
            'races' => $races,
            'pastRaces' => $pastRaces
        ]);
    }

    #[Route('/courses/ajouter', name: 'races_new')]
    public function new(Request $request): Response
    {
        $race = new Race;
        $form = $this->createForm(RaceType::class, $race);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->add($race);
            $this->addFlash('success', 'La course a bien été ajoutée.');
            return $this->redirectToRoute('races_home');
        }

        return $this->render('races/new.html.twig', [
            'form' => $form->createView(),
            'race' => $race
        ]);
    }

    #[Route('/courses/{slug}', name: 'races_show')]
    public function show(Race $race, Request $request, EventCommentRepository $eventCommentRepository): Response
    {
        if ($request->get('memberId')) {
            $member = $this->manager->getMemberRepository()->findOneBy(['id' => $request->get('memberId')]);
            $this->manager->participate($member, $race);
            $this->addFlash('success', 'Action prise en compte');
        }

        $comment = new EventComment;
        $form = $this->createForm(RaceCommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->comment($comment, $race, $this->getUser());
            $this->addFlash('success', 'Le commentaire a bien été ajouté.');
            return $this->redirectToRoute('races_show', ['slug' => $race->getSlug()]);
        }

        return $this->render('races/show.html.twig', [
            'race' => $race,
            'form' => $form->createView(),
            'comments' => $eventCommentRepository->findComments($race),
            'pinnedComments' => $eventCommentRepository->findPinnedComments($race)
        ]);
    }

    #[Route('/courses/supprimer-commentaire/{id}', name: 'races_delete_comment')]
    public function delete(EventComment $comment)
    {
        $this->denyAccessUnlessGranted(EventCommentVoter::DELETE, $comment, 'Vous n\'avez pas les droit suffisant pour effectuer cette action.');
        $event = $comment->getEvent();
        $this->manager->deleteComment($comment);
        $this->addFlash('success', 'Le commentaire a été supprimé');
        return $this->redirectToRoute('races_show', ['slug' => $event->getSlug()]);
    }

    #[Route('/courses/epingler-commentaire/{id}', name: 'races_pin_comment')]
    public function pin(EventComment $comment)
    {
        $this->manager->changeState($comment);
        $this->addFlash('success', 'Action prise en compte');
        return $this->redirectToRoute('races_show', ['slug' => $comment->getEvent()->getSlug()]);
    }

    #[Route('/courses/{slug}/ajouter-participant/{id}', name: 'races_add_member')]
    public function add($id, $slug)
    {
        $member = $this->manager->getMemberRepository()->find($id);
        $race = $this->manager->getRaceRepository()->findOneBy(['slug' => $slug]);
        $this->manager->participate($member, $race);
        $this->addFlash('success', 'Action prise en compte');
        return $this->redirectToRoute('races_show', ['slug' => $race->getSlug()]);
    }

    #[Route('/courses/edition/{slug}', name: 'races_edit')]
    public function edit(Race $race, Request $request)
    {
        $form = $this->createForm(RaceType::class, $race);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->add($race);
            $this->addFlash('success', 'La course a bien été ajoutée.');
            return $this->redirectToRoute('races_home');
        }

        return $this->render('races/new.html.twig', [
            'form' => $form->createView(),
            'race' => $race
        ]);
    }

    #[Route('/courses/{slug}/ajout-resume/{id}', name: 'races_addReport')]
    public function addReport($slug, $id, Request $request)
    {
        $member = $this->manager->getMemberRepository()->find($id);
        $race = $this->manager->getRaceRepository()->findOneBy(['slug' => $slug]);

        $raceReport = new RaceReport;
        $raceReport->setParticipant($member)->setRace($race);
        $form = $this->createForm(RaceReportType::class, $raceReport);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->save($raceReport);
            $this->addFlash('success', 'Le résumé a été enregistré. Merci de ta contribution.');
            return $this->redirectToRoute('races_show', ['slug' => $race->getSlug()]);
        }

        return $this->render('races/addReport.html.twig', [
            'form' => $form->createView(),
            'race' => $race,
            'member' => $member
        ]);
    }

    #[Route('/courses/{slug}/resumes', name: 'races_reports')]
    public function reports(Race $race)
    {
        $this->denyAccessUnlessGranted('ROLE_COMMUNITY');
        return $this->render('races/reports.html.twig', [
            'reports' => $race->getRaceReports(),
            'race' => $race
        ]);
    }

    #[Route('/courses/{slug}/delete', name: 'races_delete')]
    public function deleteRace(Race $race)
    {
        $this->denyAccessUnlessGranted('ROLE_COMITE');
        $this->manager->remove($race);
        $this->addFlash('success', 'La course a bien été supprimée.');
        return $this->redirectToRoute('races_home');
    }
}

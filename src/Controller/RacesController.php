<?php

namespace App\Controller;

use App\Entity\Race;
use App\Entity\User;
use App\Entity\Member;
use App\Form\RaceType;
use App\Entity\EventComment;
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
        return $this->render('races/index.html.twig', [
            'races' => $races,
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
        $eventId = $comment->getEvent()->getId();
        $this->manager->deleteComment($comment);
        $this->addFlash('success', 'Le commentaire a été supprimé');
        return $this->redirectToRoute('races_show', ['id' => $eventId]);
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
}

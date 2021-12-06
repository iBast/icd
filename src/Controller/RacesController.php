<?php

namespace App\Controller;

use App\Entity\EventComment;
use App\Entity\Race;
use App\Form\RaceCommentType;
use App\Form\RaceType;
use App\Manager\RaceManager;
use App\Repository\EventCommentRepository;
use App\Repository\MemberRepository;
use App\Repository\RaceRepository;
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
    public function index(RaceRepository $raceRepository): Response
    {
        $races = $raceRepository->findUpComingRaces();
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
            $this->manager->save($race);
            $this->addFlash('success', 'La course a bien été ajoutée.');
            return $this->redirectToRoute('races_home');
        }

        return $this->render('races/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/courses/{id}', name: 'races_show')]
    public function show(Race $race, Request $request, MemberRepository $memberRepository, EventCommentRepository $eventCommentRepository): Response
    {
        if ($request->get('memberId')) {
            $member = $memberRepository->findOneBy(['id' => $request->get('memberId')]);
            $this->manager->participate($member, $race);
            $this->addFlash('success', 'Action prise en compte');
        }

        if ($request->get('pinComment')) {
            $comment = $eventCommentRepository->find($request->get('pinComment'));
            $this->manager->changeState($comment);
            $this->addFlash('success', 'Action prise en compte');
        }

        $comment = new EventComment;
        $form = $this->createForm(RaceCommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->comment($comment, $race, $this->getUser());
            $this->addFlash('success', 'Le commentaire a bien été ajouté.');
            return $this->redirectToRoute('races_show', ['id' => $race->getId()]);
        }

        return $this->render('races/show.html.twig', [
            'race' => $race,
            'form' => $form->createView(),
            'comments' => $eventCommentRepository->findComments($race),
            'pinnedComments' => $eventCommentRepository->findPinnedComments($race)
        ]);
    }
}

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

use App\Entity\Sport;
use App\Entity\Training;
use App\Form\SportType;
use App\Form\TrainingType;
use App\Manager\TrainingManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingController extends AbstractController
{
    private $manager;

    public function __construct(TrainingManager $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/entrainements', name: 'training')]
    public function index(): Response
    {
        return $this->render('training/index.html.twig', [
            'trainings' => $this->manager->getTrainingRepository()->findAll(),
        ]);
    }

    #[Route('/entrainements/parametres', name: 'training_settings')]
    public function settings(Request $request): Response
    {
        $this->isGranted('ROLE_COMITE');
        $sport = new Sport();
        $form = $this->createForm(SportType::class, $sport);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->save($sport);
            $this->addFlash('success', 'Le sport a été ajouté.');

            return $this->redirectToRoute('training_settings');
        }

        return $this->render('training/settings.html.twig', [
            'sports' => $this->manager->getSportRepository()->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/entrainement/ajout', name: 'training_add')]
    public function add(Request $request): Response
    {
        $training = new Training();

        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->save($training);
            $this->addFlash('success', 'L\'entrainement a été ajouté.');

            return $this->redirectToRoute('training_show', ['id' => $training->getId()]);
        }

        return $this->render('training/edit.html.twig', [
            'form' => $form->createView(),
            'training' => $training,
        ]);
    }

    #[Route('/entrainement/edition/{id}', name: 'training_edit')]
    public function edit(Request $request, Training $training): Response
    {
        $form = $this->createForm(TrainingType::class, $training);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->save($training);
            $this->addFlash('success', 'L\'entrainement a été ajouté.');

            return $this->redirectToRoute('training_show', ['id' => $training->getId()]);
        }

        return $this->render('training/edit.html.twig', [
            'form' => $form->createView(),
            'training' => $training,
        ]);
    }

    #[Route('/entrainement/{id}', name: 'training_show')]
    public function show(Training $training): Response
    {
        return $this->render('training/training.html.twig', [
            'training' => $training,
        ]);
    }
}

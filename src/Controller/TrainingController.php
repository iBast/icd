<?php

namespace App\Controller;

use App\Manager\TrainingManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            'controller_name' => 'TrainingController',
        ]);
    }

    #[Route('/entrainement', name: 'training_show')]
    public function show(): Response
    {
        return $this->render('training/training.html.twig', [
            'controller_name' => 'TrainingController',
        ]);
    }

    #[Route('/entrainements/parametres', name: 'training_settings')]
    public function settings(): Response
    {
        $this->isGranted('ROLE_COMITE');

        return $this->render('training/settings.html.twig', [
            'sports' => $this->manager->getSportRepository()->findAll(),
        ]);
    }
}

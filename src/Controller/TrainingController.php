<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingController extends AbstractController
{
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
}

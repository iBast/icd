<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvoiceController extends AbstractController
{
    #[Route('/invoice', name: 'invoice')]
    public function index(): Response
    {
        return $this->render('invoice/template.html.twig', [
            'controller_name' => 'InvoiceController',
        ]);
    }
}

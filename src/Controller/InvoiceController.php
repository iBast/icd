<?php

namespace App\Controller;

use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InvoiceController extends AbstractController
{
    #[Route('/invoice', name: 'invoice')]
    public function index(Pdf $knpSnappyPdf): Response
    {
        $html = $this->renderView('invoice/template.html.twig');

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'file.pdf'
        );
        /* return $this->render('invoice/template.html.twig', [
            'controller_name' => 'InvoiceController',
        ]);*/
    }
}
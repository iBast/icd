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

use App\Entity\Invoice;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/facture/{id}', name: 'invoice_show')]
    public function show(Invoice $invoice)
    {
        return $this->render('invoice/template.html.twig', [
            'invoice' => $invoice,
        ]);
    }
}

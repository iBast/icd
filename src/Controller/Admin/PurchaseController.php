<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller\Admin;

use App\Manager\PurchaseManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    private $manager;

    public function __construct(PurchaseManager $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/admin/commandes/en-attente', name: 'admin_purchase_ordered_products')]
    public function showOrderedProducts()
    {
        return $this->render('admin/purchase/purchases.html.twig', [
            'items' => $this->manager->getPurchseItemRepository()->findPurchasedItemOrdered(),
        ]);
    }

    #[Route('/admin/commandes/en-attente/export', name: 'admin_purchase_ordered_products_export')]
    public function exportOrderedProducts()
    {
        $content = $this->manager->getExportableData();

        $response = new Response($content);
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.csv"');

        return $response;
    }
}

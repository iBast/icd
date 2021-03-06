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

use App\Entity\Purchase;
use App\Entity\User;
use App\Form\CartConfirmationType;
use App\Manager\PurchaseManager;
use App\Shop\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    protected $cartService;
    protected $em;
    protected $manager;
    protected $error = false;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchaseManager $manager)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->manager = $manager;
    }

    #[Route('/boutique/commande/confirmation', name: 'shop_purchase_confirm')]
    public function confirm(Request $request)
    {
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'Vous devez remplir le formulaire de confirmation');

            return $this->redirectToRoute('shop_cart');
        }

        $cartItems = $this->cartService->getDetailedCartItems();

        if (0 === \count($cartItems)) {
            $this->addFlash('warning', 'Vous ne pouvez pas confirmer une commande avec un panier vide');

            return $this->redirectToRoute('shop_cart');
        }

        /** @var Purchase */
        $purchase = $form->getData();

        $reslut = $this->manager->storePurchase($purchase);
        if (\is_array($reslut)) {
            $this->addFlash($reslut['type'], $reslut['message']);

            return $this->redirectToRoute($reslut['redirection']);
        }

        $this->addFlash('success', 'La commande a ??t?? cr??e.');

        return $this->redirectToRoute('shop_orders');
    }

    #[Route('/boutique/commandes', name: 'shop_orders')]
    public function index()
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('shop/orders.html.twig', [
            'purchases' => $user->getPurchases(),
        ]);
    }
}

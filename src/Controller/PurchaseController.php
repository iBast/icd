<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Shop\CartService;
use App\Form\CartConfirmationType;
use App\Manager\PurchaseManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PurchaseController extends AbstractController
{
    protected $cartService;
    protected $em;
    protected $manager;

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
            $this->addFlash('warning', "Vous devez remplir le formulaire de confirmation");
            return $this->redirectToRoute('shop_cart_show');
        }

        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', "Vous ne pouvez pas confirmer une commande avec un panier vide");
            return $this->redirectToRoute('shop_cart_show');
        }

        /** @var Purchase */
        $purchase = $form->getData();

        $this->manager->storePurchase($purchase);


        return $this->redirectToRoute('shop', [
            'id' => $purchase->getId()
        ]);
    }

    #[Route('/boutique/commandes', name: 'shop_orders')]
    public function index()
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('shop/orders.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
    }
}

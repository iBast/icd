<?php

namespace App\Controller;

use App\Shop\CartService;
use App\Form\CartConfirmationType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShopProductVariantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    protected $productRepository;
    protected $cartService;

    public function __construct(ShopProductVariantRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }


    #[Route('/boutique/panier/ajout', name: 'shop_cart_add')]
    public function add(Request $request)
    {
        if ($request->request->all('product')) {
            $form = $request->request->all('product');
            $product = $this->productRepository->find($form['variant']);
            $qty = $form['quantity'];
        } else {
            $product = $this->productRepository->find($request->query->get('id'));
            $qty = 1;
        }

        if (!$product) {
            throw $this->createNotFoundException("Le produit n'a pas été trouvé");
        }

        $this->cartService->add($product->getId(), $qty);

        $this->addFlash('success', "Le produit a bien été ajouté au panier");

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('shop_cart');
        }

        return $this->redirectToRoute('shop_show', [
            'category_slug' => $product->getProduct()->getCategory()->getSlug(),
            'slug' => $product->getProduct()->getSlug()
        ]);
    }

    #[Route('/boutique/panier', name: 'shop_cart')]
    public function show()
    {
        $form = $this->createForm(CartConfirmationType::class);

        $detailedCart = $this->cartService->getDetailedCartItems();
        $total = $this->cartService->getTotal();

        return $this->render('shop/cart.html.twig', [
            'items' => $detailedCart,
            'total' => $total,
            'confirmationForm' => $form->createView()
        ]);
    }

    #[Route('/boutique/panier/supprimer/{id}', name: 'shop_cart_delete', requirements: ['id' => '\d+'])]
    public function delete($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Ce produit n'existe pas");
        }
        $this->cartService->remove($id);

        $this->addFlash("success", "Le produit a bien été supprimé du panier");

        return $this->redirectToRoute("shop_cart");
    }

    #[Route('/boutique/panier/retirer/{id}', name: 'shop_cart_decrement', requirements: ['id' => '\d+'])]
    public function decrement($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Ce produit n'existe pas");
        }

        $this->cartService->decrement($id);

        $this->addFlash("success", "La quantité a bien été modifiée");

        return $this->redirectToRoute("shop_cart");
    }
}

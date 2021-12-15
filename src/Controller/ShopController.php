<?php

namespace App\Controller;

use App\Entity\ShopCategory;
use App\Entity\ShopProduct;
use App\Manager\ShopManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ShopController extends AbstractController
{
    private $manager;

    public function __construct(ShopManager $manager)
    {
        $this->manager = $manager;
    }

    #[Route('/boutique', name: 'shop')]
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'products' => $this->manager->getProductRepository()->findAll(),
        ]);
    }

    #[Route('/boutique/mes-commandes', name: 'shop_orders')]
    public function orders(): Response
    {

        return $this->render('shop/orders.html.twig', [
            'products' => $this->manager->getProductRepository()->findAll()
        ]);
    }

    #[Route('/boutique/panier', name: 'shop_cart')]
    public function cart(): Response
    {

        return $this->render('shop/cart.html.twig', [
            'products' => $this->manager->getProductRepository()->findAll()
        ]);
    }

    #[Route('/boutique/{slug}', name: 'shop_category')]
    public function category(ShopCategory $category): Response
    {
        $products = $this->manager->getProductRepository()->findBy(['category' => $category]);
        return $this->render('shop/category.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }

    #[Route('/boutique/{category_slug}/{slug}', name: 'shop_show')]
    public function show(ShopProduct $product): Response
    {
        return $this->render('shop/show.html.twig', [
            'product' => $product,
        ]);
    }
}
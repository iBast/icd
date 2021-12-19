<?php

namespace App\Controller;

use App\Entity\ShopCategory;
use App\Entity\ShopProduct;
use App\Form\ProductType;
use App\Manager\ShopManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
            'products' => $this->manager->getProductRepository()->findVisible()
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
    public function show(ShopProduct $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, null, ['product' => $product]);
        return $this->render('shop/show.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}

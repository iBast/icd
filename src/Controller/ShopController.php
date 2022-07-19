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

use App\Entity\ShopCategory;
use App\Entity\ShopProduct;
use App\Entity\ShopProductVariant;
use App\Entity\User;
use App\Form\ProductType;
use App\Form\TrocType;
use App\Manager\ShopManager;
use App\Repository\ShopCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ShopController extends AbstractController
{
    private $manager;

    public function __construct(ShopManager $manager, private EntityManagerInterface $em, private ShopCategoryRepository $categoryRepository)
    {
        $this->manager = $manager;
    }

    #[Route('/boutique', name: 'shop')]
    public function index(): Response
    {
        return $this->render('shop/index.html.twig', [
            'products' => $this->manager->getProductRepository()->findVisible(),
        ]);
    }

    #[Route('/boutique/troc/deposer-une-annonce', name: 'shop_troc')]
    public function troc(Request $request, SluggerInterface $slugger)
    {
        $form = $this->createForm(TrocType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            /** @var User $user */
            $user = $this->getUser();

            /** @var UploadedFile $file */
            $file = $data['picturePath'];

            $product = new ShopProduct();
            $product->setCategory($this->categoryRepository->findOneBy(['name' => 'troc']));
            $product->setName($data['name']);
            $product->setIsVisible(true);
            $product->setPrice($data['price'] * 100);
            $product->setSlug(uuid_create());
            $product->setDescription($data['description']);
            $product->setPictureFile($file);
            $product->setPicturePath($file);
            $product->setSeller($user);
            $this->em->persist($product);

            $variant = new ShopProductVariant();
            $variant->setProduct($product);
            $variant->setStock(1);
            $variant->setName($data['size']);
            $this->em->persist($variant);

            $this->addFlash('success', 'Votre annonce a été créée');
            $this->em->flush();
            return $this->redirectToRoute('shop_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }

        return $this->render('shop/troc.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/boutique/troc/changer-etat/{id}', name: 'troc_change_state')]
    public function trocChangeState(ShopProduct $product)
    {
        if ($product->getSeller() !== $this->getUser()) {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer un article qui ne vous appartient pas');
            return $this->redirectToRoute('logout');
        }
        $product->setIsVisible(!$product->getIsVisible());
        $this->em->flush();

        $this->addFlash('success', 'l\'état a été modifié');
        return $this->redirectToRoute('shop');
    }

    #[Route('/boutique/troc/mes-annonces', name: 'troc_personal')]
    public function trocPersonal()
    {
        return $this->render('shop/personal_sell.html.twig', [
            'annonces' => $this->manager->getProductRepository()->findBy(['seller' => $this->getUser()])
        ]);
    }

    #[Route('/boutique/{slug}', name: 'shop_category')]
    public function category(ShopCategory $category): Response
    {
        $products = $this->manager->getProductRepository()->findBy(['category' => $category, 'isVisible' => true]);

        return $this->render('shop/category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    #[Route('/boutique/{category_slug}/{slug}', name: 'shop_show')]
    public function show(ShopProduct $product, Request $request): Response
    {
        $form = $this->createForm(ProductType::class, null, ['product' => $product]);

        return $this->render('shop/show.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

}

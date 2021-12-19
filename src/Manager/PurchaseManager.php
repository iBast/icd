<?php

namespace App\Manager;

use App\Entity\Purchase;
use App\Shop\CartService;
use App\Entity\PurchaseItem;
use App\Entity\EntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchaseManager extends AbstractManager
{
    protected $security;
    protected $cartService;
    protected $em;

    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
        parent::__construct($em);
    }

    public function initialise(EntityInterface $entity)
    {
        //interface
    }
    public function storePurchase(Purchase $purchase)
    {
        $purchase->setUser($this->security->getUser())
            ->setTotal($this->cartService->getTotal());

        $this->em->persist($purchase);
        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem;
            $purchaseItem
                ->setPurchase($purchase)
                ->setProductVariant($cartItem->variant)
                ->setProductName($cartItem->variant->getProduct()->getName())
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal())
                ->setProductPrice($cartItem->variant->getProduct()->getPrice())
                ->setVariantName($cartItem->variant->getName());

            /*
            if ($purchaseItem->getProductVariant()->getStock() - $cartItem->qty < 0) {
                return;
            }
            $purchaseItem->getProductVariant()->setStock($purchaseItem->getProductVariant()->getStock() - $cartItem->qty); */

            $this->em->persist($purchaseItem);
        }

        $this->em->flush();
    }
}

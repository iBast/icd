<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Repository\PurchaseItemRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ShopProductVariantRepository;
use App\Shop\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchaseManager extends AbstractManager
{
    protected $security;
    protected $cartService;
    protected $em;
    protected $purchaseRepository;
    protected $shopProductVariantRepository;
    protected $purchaseItemRepository;

    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em, PurchaseRepository $purchaseRepository, ShopProductVariantRepository $shopProductVariantRepository, PurchaseItemRepository $purchaseItemRepository)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em = $em;
        $this->purchaseRepository = $purchaseRepository;
        $this->shopProductVariantRepository = $shopProductVariantRepository;
        $this->purchaseItemRepository = $purchaseItemRepository;
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
            $purchaseItem = new PurchaseItem();
            $purchaseItem
                ->setPurchase($purchase)
                ->setProductVariant($cartItem->variant)
                ->setProductName($cartItem->variant->getProduct()->getName())
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal())
                ->setProductPrice($cartItem->variant->getProduct()->getPrice())
                ->setVariantName($cartItem->variant->getName());

            if ($purchaseItem->getProductVariant()->getStock() - $cartItem->qty < 0) {
                $error = $this->error('danger', 'Stock insufisant pour le produit '.$purchaseItem->getProductName().' - '.$purchaseItem->getVariantName().' Stock restant : '.$purchaseItem->getProductVariant()->getStock(), 'shop_cart');

                return $error;
            }
            $purchaseItem->getProductVariant()->setStock($purchaseItem->getProductVariant()->getStock() - $cartItem->qty);

            $this->em->persist($purchaseItem);
        }

        $this->em->flush();
        $this->cartService->empty();
    }

    public function getExportableData()
    {
        $orders = $this->getPurchseItemRepository()->findPurchasedItemOrdered();

        $rows = ['R??f??rence,Produit,Mod??le,Quantit??'];
        foreach ($orders as $order) {
            $data = [
                $order['reference'],
                $order['ProductName'],
                $order['variantName'],
                $order['count'],
            ];

            $rows[] = implode(',', $data);
        }

        return implode("\n", $rows);
    }

    public function acceptOrder(Purchase $purchase)
    {
        $purchase->setStatus(Purchase::STATUS_ACCEPTED);
        $this->save($purchase);
    }

    public function confirmPayment(Purchase $purchase)
    {
        $purchase->setStatus(Purchase::STATUS_PAID);
        $this->save($purchase);
    }

    public function deliverOrder(Purchase $purchase)
    {
        $purchase->setStatus(Purchase::STATUS_DELIVERED);
        $this->save($purchase);
    }

    public function getPurchaseRepository()
    {
        return $this->purchaseRepository;
    }

    public function getProductVariantRepository()
    {
        return $this->shopProductVariantRepository;
    }

    public function getPurchseItemRepository()
    {
        return $this->purchaseItemRepository;
    }
}

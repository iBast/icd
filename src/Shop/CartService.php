<?php

namespace App\Shop;

use App\Shop\CartItem;
use App\Repository\ShopProductVariantRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    protected $requestStack;
    protected $productRepository;

    public function __construct(RequestStack $requestStack, ShopProductVariantRepository $productRepository)
    {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    protected function getCart(): array
    {
        return $this->requestStack->getSession()->get('cart', []);
    }

    protected function saveCart(array $cart)
    {
        return $this->requestStack->getSession()->set('cart', $cart);
    }

    public function add(int $id, $qty = 1)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }
        $cart[$id] = $cart[$id] + $qty;

        $this->saveCart($cart);
    }

    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->requestStack->getSession()->get('cart', []) as $id => $qty) {
            $variant = $this->productRepository->find($id);
            if (!$variant) {
                continue;
            }
            $total += $variant->getProduct()->getPrice() * $qty;
        }
        return $total;
    }

    /**
     * 
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];
        foreach ($this->getCart() as $id => $qty) {
            $product = $this->productRepository->find($id);
            if (!$product) {
                continue;
            }
            $detailedCart[] = new CartItem($product, $qty);
        }
        return $detailedCart;
    }

    public function remove(int $id)
    {
        $cart = $this->getCart();

        unset($cart[$id]);

        $this->saveCart($cart);
    }

    public function decrement(int $id)
    {
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        if ($cart[$id] == 1) {
            return $this->remove($id);
        }

        $cart[$id]--;

        $this->saveCart($cart);
    }

    public function empty()
    {
        $this->saveCart([]);
    }
}

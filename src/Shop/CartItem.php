<?php

namespace App\Shop;

use App\Entity\ShopProductVariant;

class CartItem
{
    public $product;
    public $qty;

    public function __construct(ShopProductVariant $product, int $qty)
    {
        $this->product = $product;
        $this->qty = $qty;
    }

    public function getTotal(): int
    {
        return $this->product->getProduct()->getPrice() * $this->qty;
    }
}

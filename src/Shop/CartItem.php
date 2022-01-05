<?php

namespace App\Shop;

use App\Entity\ShopProductVariant;

class CartItem
{
    public $variant;
    public $qty;

    public function __construct(ShopProductVariant $variant, int $qty)
    {
        $this->variant = $variant;
        $this->qty = $qty;
    }

    public function getTotal(): int
    {
        return $this->variant->getProduct()->getPrice() * $this->qty;
    }
}

<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

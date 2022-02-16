<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('amount', [$this, 'amount']),
        ];
    }

    public function amount($value)
    {
        $finalValue = $value / 100;

        $finalValue = number_format($finalValue, 2, ',', ' ');

        return $finalValue.' €';
    }
}

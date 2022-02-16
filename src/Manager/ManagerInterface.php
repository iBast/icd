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

interface ManagerInterface
{
    public function initialise(EntityInterface $entity);

    public function save(EntityInterface $entity): void;

    public function remove(EntityInterface $entity): void;
}
